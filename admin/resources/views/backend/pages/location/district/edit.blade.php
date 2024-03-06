@extends('backend.layouts.master')
@section('title','District Update - '.config('concave.cnf_appname'))
@section('content')
    
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Location > Update District</span>
                <a class="btn btn-success float-right" href="{{ route('admin.location.district')}}">View District List</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.location.district.update', $data->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" value="{{$data->title}}" placeholder="Title" />
                                    </div>
                               </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Division</label>
                                    <div class="col-sm-9">
                                        <select name="division_id" class="form-control"> 

                                          <option value="{{$data->division->id }}">{{$data->division->title}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                           <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" type="submit">Update District</button>
                                    </p>
                                </div>
                            </div>
                        </div>                   
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
