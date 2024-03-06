<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\CareerRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CareerController extends Controller
{
    public function careerList()
    {
        return view('backend.pages.career.list');
    }

    public function getCareertList()
    {

        $data = Career::where('is_deleted', 0)->latest();
        return DataTables::of($data)->addIndexColumn()
            ->editColumn('status', function ($row) {
                $text = '';
                if ($row->status == 1) {
                    $text = '<p>Active</p>';
                } else if ($row->status == 2) {
                    $text = '<p>Inactive</p>';
                }

                return $text;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('career.view')) {
                    $btn = '<a class="icon_btn text-info career_quick_view_btn" href="" data-id="' . $row->id . '" ><i class="mdi mdi-eye"></i></a>';
                }
                if (Auth::user()->can('career.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.career.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('career.delete')) {
                    // $btn = $btn . '<a class="icon_btn text-danger delete_btn" href="' . route('admin.career.delete', $row->id) . '"><i class="mdi mdi-delete"></i></a>';

                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.career.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['description', 'status', 'action'])->make(true);
    }

    public function careerCreate()
    {
        return View('backend.pages.career.create');
    }

    public function careerStore(Request $request)
    {
        Career::insert([
            'position' => $request->position,
            'post_date' => $request->post_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'status' => $request->status,
            'created_at' => Carbon::now()
        ]);
        return redirect()->back()->with('success', 'Career successfully created!');
    }

    public function careerEdit($id)
    {
        $data = Career::where('id', $id)->first();
        return View('backend.pages.career.edit', get_defined_vars());
    }

    public function careerUpdate(Request $request)
    {
        // dd($request->id);
        Career::find($request->id)->update([
            'position' => $request->position,
            'post_date' => $request->post_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'status' => $request->status,
            'updated_at' => Carbon::now()
        ]);
        return redirect()->back()->with('success', 'Career successfully updated!');
    }

    public function careerDelete(Request $request, $id)
    {

        $career = Career::find($id);

        //Insert Trash Data
        $type = 'career';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $career;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $career->is_deleted = 1;
        $career->save();

        return redirect()->back()->with('success', 'Career successfully deleted!');
    }

    public function careerView($id)
    {
        $career = Career::find($id);

        return view('backend.pages.career.modal')->with(
            array(
                'career' => $career,
            )
        );
    }

    public function careerRequest()
    {
        return view('backend.pages.career.careerRequest');
    }

    public function getCareertRequest()
    {
        $data = CareerRequest::where('is_deleted', 0)->with('career');
        return DataTables::of($data)->addIndexColumn()
            ->editColumn('job_id', function ($row) {
                return $row->career->position ?? '';
            })
            ->editColumn('file', function ($row) {
                $download = '';
                if (Auth::user()->can('career.request')) {
                    $download = '<a class="btn btn-danger" target="_blank" href="/uploads/jobapplication/' . $row->file . '">Download</a>';
                }
                return $download;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('career.request')) {
                    $btn = '<a class="icon_btn text-info career_request_quick_view_btn" href="" data-id="' . $row->id . '" ><i class="mdi mdi-eye"></i></a>';
                }

                if (Auth::user()->can('career.request.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.career.request.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['file', 'job_id', 'action'])->make(true);
    }

    public function careerRequestView($id)
    {
        $career_request = CareerRequest::find($id);

        return view('backend.pages.career.careerRequestModal')->with(
            array(
                'career_request' => $career_request,
            )
        );
    }

    public function careerRequestDelete(Request $request, $id)
    {
        $career_request = CareerRequest::find($id);

        //Insert Trash Data
        $type = 'career request';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $career_request;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $career_request->is_deleted = 1;
        $career_request->save();

        return redirect()->back()->with('success', 'Career request successfully deleted!');
    }
}