@extends('backend.layouts.master')
@section('title','Upazila Create - '.config('concave.cnf_appname'))
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Location > Create Upazila</span>
                <a class="btn btn-success float-right" href="{{ route('admin.location.upazila')}}">View Upazila List</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.location.upazila.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" placeholder="Title" />
                                    </div>
                                </div>
                            </div>
                        </div>
                           <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">District</label>
                                    <div class="col-sm-9">
                                        <select name="district_id" class="form-control" >
                                        @foreach($data as $d)
                                            <option value="{{$d->id}}">{{$d->title}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                             <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" type="submit">Create Upazila</button>
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
