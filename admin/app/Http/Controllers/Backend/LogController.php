<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;
use Auth;
use Carbon\Carbon;

class LogController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function getLog()
    {

        if (is_null($this->user) || !$this->user->can('activitylog.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $activityLogs = Activity::latest();
        return view('backend.pages.log.list', compact('activityLogs'));
    }

    public function getActivityLog($start_date, $end_date)
    {
        
        if($start_date != 0 && $end_date != 0){
            $data = Activity::whereBetween('created_at', [$start_date, $end_date]);
        }else{
            $data = Activity::latest()->with('subject','causer');
        }

        return DataTables::of($data)->addIndexColumn()

            ->setRowClass(function ($row) {
                if(!optional($row->causer)->name){
                    return 'd-none';
                }
            })
            ->editColumn('created_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d M,Y h:ia');
                return $formatedDate;
            })
            ->addColumn('checkbox', function($row){
                return '<div class="form-check form-check-flat">
                            <label class="form-check-label">
                                <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="'.$row->id.'"><i class="input-helper"></i>
                            </label>
                        </div>';
            })
            ->editColumn('causer_id', function ($row) {
                return optional($row->causer)->name.' - '.optional($row->causer)->phone;
            })
           
            ->addColumn('action', function ($row) {
                // dd($row->id);
                $btn = '';
                if (Auth::user()->can('activitylog.view')) {
                    $btn = '<a title="Log View" class="icon_btn text-success text-decoration-none logViewBtn" id="' . $row->id . '"  href="#"><i class="mdi mdi-eye"></i></a>';
                }
                if (Auth::user()->can('activitylog.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('activity.log.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['created_at','checkbox', 'action','causer_id'])->make(true);
    }




    public function view($id)
    {
        $log = Activity::find($id);

        $properties = $log->properties;
        $newVal = isset($properties['attributes']) ? $properties['attributes'] : []; 
        $oldVal = isset($properties['old']) ? $properties['old'] : [];
        $log = array_merge_recursive($oldVal,$newVal);
        if($log && $oldVal){
            foreach($log as $key => $val){
                if($val[0] == $val[1]){
                    unset($log[$key]);
                }
            }
        }

        return view('backend.pages.log.view', compact('log'));
    }





    public function destroy(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('activitylog.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        // dd($id);
        $activity = Activity::find($id)->delete();

        return redirect()->route('activity.log')->with('success', 'Activity successfully deleted!');
    }

    public function destroySelected (Request $request){
        if (empty($request->select_all)) {
            session()->flash('success', 'You have to select products!');
            return back();
        }

        foreach ($request->select_all as $key => $value) {
            $activity = Activity::find($value)->delete();
        }
        
        return redirect()->route('activity.log')->with('success', 'Activity successfully deleted!');
    }
}