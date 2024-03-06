@extends('backend.layouts.master')
@section('title', 'Search Create - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Settings > Search Dashboard > Create</span>
                <a class="btn btn-success float-right" href="{{ route('admin.search.dashboard.index') }}">View
                    Search Dashboard</a>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.store.search.dashboard') }}" method="POST">
        @csrf
        <div class="card p-5">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Title <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Link <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" name="link" class="form-control" required>
                    </div>

                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Permission <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-select form-control" name="permission" aria-label="Default select example"
                                required>
                            <option selected value="">Choose one</option>
                            <option value="1">Admin</option>
                            <option value="2">Seller</option>
                        </select>
                    </div>

                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-select form-control" name="status" aria-label="Default select example"
                            required>
                            <option selected value="">Choose one</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>

                </div>
                
            </div>
            <div class="">
                <div class="float-right">
                    <button type="submit" class="btn btn-success">Create Search</button>
                </div>
            </div>
        </div>
    </form>
@endsection
