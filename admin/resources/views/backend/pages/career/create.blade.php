@extends('backend.layouts.master')
@section('title', 'Career Create - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Career > Create New Career</span>
                <a class="btn btn-success float-right" href="{{ route('admin.career.list') }}">View
                    Career</a>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.career.store') }}" method="POST">
        @csrf
        <div class="card p-5">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Position <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" name="position" class="form-control" required>
                    </div>
                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Post Date <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" name="post_date" class="form-control" required>
                    </div>

                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">End Date <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-select form-control" name="status" aria-label="Default select example"
                            required>
                            <option selected>Choose one</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3 col-form-label">Description <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <textarea type="text" name="description" class="textEditor form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="float-right">
                    <button type="submit" class="btn btn-success">Create Career</button>
                </div>
            </div>
        </div>
    </form>
@endsection
