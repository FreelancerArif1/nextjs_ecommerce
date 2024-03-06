<?php

namespace App\Http\Controllers\Backend;

use App\Models\Union;
use App\Models\Upazila;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Image;
use Auth;

class UnionController extends Controller
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
        if (is_null($this->user) || !$this->user->can('location.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $data = Union::where('is_deleted', 0)->paginate(30);
        return view('backend.pages.location.union.list', compact('data'));
    }



    public function create()
    {
        if (is_null($this->user) || !$this->user->can('location.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = Upazila::where('is_deleted', 0)->get();

        return view('backend.pages.location.union.create', compact('data'));
    }


    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('location.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'upazila_id' => 'required'

        ]);

        $location = new Union();
        $location->title = $request->title;
        $location->upazila_id = $request->upazila_id;


        $location->save();


        return redirect()->route('admin.location.union')->with('success', 'Union successfully created!');
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('location.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = Union::find($id);
        return view('backend.pages.location.union.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('location.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255'
        ]);

        $location = Union::find($id);
        $location->title = $request->title;
        $location->grocery_shipping_allowed = ($request->grocery_shipping_allowed) ? 1 : 0 ;
        $location->save();

        return redirect()->route('admin.location.union')->with('success', 'Union successfully updated!');
    }

    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('location.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $location = Union::find($id);

        //Insert Trash Data
        $type = 'location';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $location;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $location->is_deleted = 1;
        $location->save();

        return redirect()->route('admin.location.union')->with('success', 'Union successfully deleted!');
    }

    public function getUnion()
    {
        $data = Union::where('is_deleted', 0);
        return DataTables::of($data)->addIndexColumn()
            ->addColumn('upazila_title', function ($row) {
                $upazila = Upazila::where('id', $row->upazila_id)
                    ->first();
                return $upazila->title ?? null;
            })

            ->editColumn('grocery_shipping_allowed', function ($row) {
                return ($row->grocery_shipping_allowed == 1) ? 'YES' : 'NO';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                // if (Auth::user()->can('location.view')) {
                //     $btn = '<a class="icon_btn text-success" href="' . route('admin.location.union', $row->id) . '"><i class="mdi mdi-eye"></i></a>';
                // }
                if (Auth::user()->can('location.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.location.union.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('location.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.location.union.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['upazila_title', 'action'])->make(true);
    }
}