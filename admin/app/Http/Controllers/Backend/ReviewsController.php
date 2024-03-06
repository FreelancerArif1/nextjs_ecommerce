<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Auth;
use App\Models\Review;
use App\Models\User;
use App\Models\Admins;
use Yajra\DataTables\DataTables;

class ReviewsController extends Controller
{
    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index(){

        if(is_null($this->user) || !$this->user->can('review.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $reviews = Review::orderBy('id','desc')->with('product')->get();
        return view('backend.pages.reviews.list',compact('reviews'));
    }

    
    public function getReviewList(){

        if(is_null($this->user) || !$this->user->can('review.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if(Auth::user()->hasRole('seller')){
            $comment_ids = [];
            $allData = Review::orderBy('id','desc')->where('parent_id', null)->with('product')->get();
            foreach($allData as $d){
                if($d->product->seller_id == Auth::id()){
                    $comment_ids[] = $d->id;
                }
            }
            $data = Review::orderBy('id','desc')->whereIn('id',$comment_ids)->with('product')->get();
        }else{
            $data = Review::orderBy('id','desc')->where('parent_id', null)->with('product')->get();
        }

        return Datatables::of($data)->addIndexColumn()
        ->addColumn('product', function($row){

            $html = '';
            $imageHtml = '';

            if($row->product->default_image){
                $imageHtml = '<img class="list_img mr-3" src="/'.$row->product->default_image.'">';
            }else{
                $imageHtml = '<img class="list_img mr-3" src="'.asset('uploads/images/default/no-image.png').'">';
            }

            $html ='<div class="media">'.$imageHtml.'<div class="media-body"><p class="product_title">'.$row->product->title.'</p></div>';
            return $html;
           
        })
        ->addColumn('user', function($row){
            $user = \App\Models\User::where('id',$row->commented_id)->first();
            return  $user->name;
           
        })
        ->addColumn('comment', function($row){
            return  unserialize($row->comment)['comment'];
           
        })
        ->addColumn('seller', function($row){
            $seller = \App\Models\Admins::where('id',$row->product->seller_id)->first();
            return $seller->shopinfo->name;
        })

        ->addColumn('status', function($row){
            return  '<label class="badge badge_'.strtolower(\Helper::getStatusName('default',$row->approved)).'">'.\Helper::getStatusName('default',$row->approved).'</label>';
        })

        ->addColumn('action', function($row){
            $btn = '';
     
			
            if(Auth::user()->can('review.edit')){
                if($row->approved == 1)
                    $btn = '<a title="Disapprove" class="icon_btn text-danger" href="/admin/reviews/edit/'.$row->id.'/0"><i class="mdi mdi-minus-circle"></i></a>';
                elseif($row->approved == 0){
                    $btn = $btn.'<a title="Approve" class="icon_btn text-success" href="/admin/reviews/edit/'.$row->id.'/1"><i  style="font-size: 18px;" class="mdi mdi-spellcheck"></i></a>';
                }
            }
            if(Auth::user()->can('review.replay')){
				$user = \App\Models\User::where('id',$row->commented_id)->first();
                $btn = $btn.'<a title="Replay" class="icon_btn text-danger reply_btn" data-comments_id="'.$row->id.'" data-user="'.$user->name.'" data-toggle="modal" data-target="#replayModal" href="#"><i class="mdi mdi-reply"></i></a>';
            }
            if(Auth::user()->can('review.delete')){
                $btn = $btn.'<a title="Delete" class="icon_btn text-danger delete_btn" data-url="'.route('admin.review.delete',$row->id).'" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })

        ->rawColumns(['product','user','comment','seller','status','action'])->make(true);
    }

    public function getSingleReviewList($id){
        if(is_null($this->user) || !$this->user->can('review.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }


        $html = '';


        $comments = \DB::table('comments')->where('id', $id)->get();
		foreach($comments as $key => $item){
			$unserialize_comnt = unserialize($item->comment);
			$item->comment_text = $unserialize_comnt['comment'];
           
			if(array_key_exists('image', $unserialize_comnt) && gettype($unserialize_comnt['image']) == 'string'){
				if($unserialize_comnt['image']){
					$item->image = explode(",",$unserialize_comnt['image']);
				}else{
					$item->image = [];
				}
			}
			$user = User::where('id',$item->commented_id)->first();
			$item->user_name = $user->name ?? '';
            $html.='<p> <b>'.$item->user_name.'</b> : '.$item->comment_text.'</p>';
			$rep = \DB::table('comments')->where('parent_id',$item->id)->where('approved',1)->get();
			if($rep){
                foreach($rep as $k => $v){
                    $unserialize_comnt = unserialize($v->comment);
                    $v->comment_text = $unserialize_comnt['comment'];
                    
                    if(array_key_exists('image', $unserialize_comnt)){
                        $images = '';
                        foreach($unserialize_comnt['image'] as $key => $value){
                            $images.= '<img src="/'. $value.'" style="    width: 60px;height: 50px;object-fit: contain;background: #dfdfdf; margin-left: 10px; border: 1px solid #c3c3c3;" alt="img">';
                        }
                    }

                    $user = Admins::where('id',$v->commented_id)->first();
                    $v->user_name = $user->name ?? '';
                    $html.='<p class="ml-3 replay_item"> <span class="mdi mdi-reply"></span> <b>'.$v->user_name.'</b> : '.$v->comment_text.'<br>'.$images.'</p>';
                }
                
               
            }

		}
		return $html;
    }
    
    public function edit($id,$approved){
        if(is_null($this->user) || !$this->user->can('review.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $review = Review::find($id);
        $review->approved = $approved;
        $review->save();
        return back();
    }




    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('review.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $review = Review::find($id);
        $review->delete();
        return redirect()->route('admin.review')->with('success', 'Review successfully deleted!');
    }
    public function replay(Request $request){
        if(is_null($this->user) || !$this->user->can('review.replay')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
		$comments_id = $request->comments_id;
		$comments_data['image'] =  [];
		$comments_data['comment'] =  $request->replay;
        $comments_data['image'] = $request->images;
		$parentComment = \DB::table('comments')->where('id',$comments_id)->first();
		\DB::table('comments')->insert([
			'parent_id' => $comments_id,
			'commentable_id' => $parentComment->commentable_id,
			'commentable_type' => 'App\Models\Product',
			'commented_id' => Auth::id(),
			'commented_type' => 'App\Models\User',
			'comment'	=> serialize($comments_data),
			'approved' => 1,
		]);
		
        return redirect()->route('admin.review')->with('success', 'Replay has been successfully saved!');
    }
	


}
