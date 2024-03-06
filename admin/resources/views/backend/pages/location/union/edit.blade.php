@extends('backend.layouts.master')
@section('title','Union Update - '.config('concave.cnf_appname'))
@section('content')
    
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Location > Update Union</span>
                <a class="btn btn-success float-right" href="{{ route('admin.location.union')}}">View District List</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.location.union.update', $data->id) }}">
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
                                    <label class="col-sm-3 col-form-label">Upazila</label>
                                    <div class="col-sm-9">
                                        <select name="division_id" class="form-control">                                           
                                          <option value="{{$data->upazila->id}}">{{$data->upazila->title}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Allow Grocery Shipping</label>
                                    <div class="col-sm-9">
                                        <label class="form-check-label">
                                            <label class="switch"><input name="grocery_shipping_allowed" id="grocery_shipping_allowed" type="checkbox" @if (old('grocery_shipping_allowed')) checked="" @endif><span
                                                    class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                           <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" type="submit">Update Union</button>
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
