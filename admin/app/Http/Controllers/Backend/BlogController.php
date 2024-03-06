<?php

namespace App\Http\Controllers\Backend;

use App\Models\Blog;
use App\Models\BlogLocalization;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Image;
use Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('blog.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $blogs = Blog::latest()->get();
        return view('backend.pages.blog.list', compact('blogs'));
    }




    public function getBlogList()
    {

        if (is_null($this->user) || !$this->user->can('blog.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = Blog::where('is_deleted', 0);

        return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('checkbox', function ($row) {
                return '<div class="form-check form-check-flat">
                        <label class="form-check-label">
                            <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="' . $row->id . '"><i class="input-helper"></i>
                        </label>
                    </div>';
            })

            ->editColumn('title', function ($row) {
                $html = '';
                $imageHtml = '';

                if ($row->image) {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('/' . $row->image) . '">';
                } else {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('uploads/images/default/no-image.png') . '">';
                }

                $html = '<div class="media">' . $imageHtml . '<div class="media-body"><p class="product_title">' . $row->title . '</p></div>';
                return $html;
            })

            ->editColumn('created_at', function ($row) {
                return  date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->editColumn('is_active', function ($row) {
                return  '<label class="badge badge_' . strtolower(\Helper::getStatusName('default', $row->is_active)) . '">' . \Helper::getStatusName('default', $row->is_active) . '</label>';
            })

            ->editColumn('category_id', function ($row) {
                return $row->category->title ?? '';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('blog.view')) {
                    $btn = '<a class="icon_btn text-info blog_quick_view_btn" href="" data-id="' . $row->id . '" ><i class="mdi mdi-eye"></i></a>';
                }

                if (Auth::user()->can('blog.edit')) {
                    $btn = $btn . '<a class="icon_btn text-success" href="' . route('admin.blog.edit', $row->id) . '"><i class="mdi mdi-pencil-box-outline"></i></a>';
                }

                if (Auth::user()->can('blog.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.blog.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['checkbox', 'title', 'category_id', 'created_at', 'is_active', 'action'])->make(true);
    }



    public function create()
    {
        if (is_null($this->user) || !$this->user->can('blog.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.blog.create');
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('blog.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
            'slug'  => 'required | unique:blogs'
        ]);

        $blog = new Blog;
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->category_id = $request->category_id;

        $related_products = '';
        if ($request->related_products) {
            foreach ($request->related_products as $key => $val) {
                $related_products .= $val . ',';
            }
        }
        $blog->related_products = $related_products;

        $blog->description = $request->description;

        $blog->specification = $request->specification ?? null;

        $blog->meta_title = $request->meta_title;
        $blog->meta_keyword = $request->meta_keyword;
        $blog->meta_description = $request->meta_description;
        $blog->image = $request->image;
        $blog->gallery_images = ($request->gallery_images) ? implode(',', $request->gallery_images) : NULL;
        $blog->is_active = $request->is_active ? 1 : 0;
        $blog->save();


        //Localization Data
        $lanData = [];
        foreach ($request->all() as $key => $val) {
            if (strpos($key, "__") > 0) {
                $paramKey = explode('__', $key)[0];
                $lang = explode('__', $key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach ($lanData as $data) {
            $blog_localization = new BlogLocalization();
            $blog_localization->blog_id = $blog->id;
            $blog_localization->title = $data['title'];
            $blog_localization->lang_code = $data['lang_code'];
            $blog_localization->description = $data['description'];
            $blog_localization->save();
        }

        return redirect()->route('admin.blog')->with('success', 'Blog successfully created!');
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('blog.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $blog = Blog::findOrFail($id);

        return view('backend.pages.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('blog.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
        ]);

        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->category_id = $request->category_id;

        $related_products = '';
        if ($request->related_products) {
            foreach ($request->related_products as $key => $val) {
                $related_products .= $val . ',';
            }
        }
        $blog->related_products = $related_products;

        $blog->description = $request->description;
        $blog->specification = $request->specification ?? null;
        $blog->meta_title = $request->meta_title;
        $blog->meta_keyword = $request->meta_keyword;
        $blog->meta_description = $request->meta_description;
        $blog->image = $request->image;
        if (isset($request->gallery_images)) {
            $blog->gallery_images = ($request->gallery_images) ? implode(',', $request->gallery_images) : NULL;
        }
        $blog->is_active = $request->is_active ? 1 : 0;
        $blog->save();

        //Localization Data
        $lanData = [];
        foreach ($request->all() as $key => $val) {
            if (strpos($key, "__") > 0) {
                $paramKey = explode('__', $key)[0];
                $lang = explode('__', $key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach ($lanData as $data) {

            $blog_localization = BlogLocalization::where('blog_id', $blog->id)->where('lang_code', $data['lang_code'])->first();

            if ($blog_localization !== null) {
                $blog_localization->title = $data['title'];
                $blog_localization->description = $data['description'];
                $blog_localization->save();
            } else {
                $blog_localization = new BlogLocalization();
                $blog_localization->blog_id = $blog->id;
                $blog_localization->title = $data['title'];
                $blog_localization->lang_code = $data['lang_code'];
                $blog_localization->description = $data['description'];
                $blog_localization->save();
            }
        }
        return redirect()->route('admin.blog', $blog->id)->with('success', 'Blog successfully updated!');
    }

    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('blog.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $blog = Blog::find($id);

        //Insert Trash Data
        $type = 'blog';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $blog;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $blog->is_deleted = 1;
        $blog->save();

        return redirect()->route('admin.blog')->with('success', 'Blog successfully deleted!');
    }

    public function view($id)
    {

        $blog = Blog::find($id);

        return view('backend.pages.blog.view')->with(
            array(
                'blog' => $blog,
            )
        );
    }

    public function action(Request $request)
    {

        if (empty($request->select_all)) {
            session()->flash('success', 'You have to select blog!');
            return back();
        }

        if ($request->action ==  "active") {
            foreach ($request->select_all as $id) {
                Blog::where('id', $id)->update(['is_active' => 1]);
            }
            session()->flash('success', 'Blog successfully activated !');
            return back();
        }

        if ($request->action ==  "inactive") {
            foreach ($request->select_all as $id) {
                Blog::where('id', $id)->update(['is_active' => 0]);
            }
            session()->flash('success', 'Blog successfully inctivated !');
            return back();
        }

        if ($request->action ==  "delete") {
            foreach ($request->select_all as $id) {
                Blog::where('id', $id)->update(['is_deleted' => 1]);
                $blog = Blog::find($id);
                //Insert Trash Data
                $type = 'blog';
                $type_id = $id;
                $reason = $request->reason ?? 'Bulk Delete';
                $data = $blog;
                \Helper::setTrashInfo($type, $type_id, $reason, $data);
            }
            session()->flash('success', 'Blog successfully deleted !');
            return back();
        }
    }
}