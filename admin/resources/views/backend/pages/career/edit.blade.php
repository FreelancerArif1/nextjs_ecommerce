@extends('backend.layouts.master')
@section('title', 'Career Edit - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Career > Career Edit</span>
                <a class="btn btn-success float-right" href="{{ route('admin.career.list') }}">View
                    Career</a>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.career.update') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $data->id }}">
        <div class="card p-5">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Position <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" name="position" class="form-control" value="{{ $data->position ?? null }}"
                            required>
                    </div>
                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Post Date <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" name="post_date" class="form-control" value="{{ $data->post_date ?? null }}"
                            required>
                    </div>

                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">End Date <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" name="end_date" class="form-control" value="{{ $data->end_date ?? null }}"
                            required>
                    </div>

                </div>
                <div class=" form-group row mb-4">
                    <label for="" class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-select form-control" name="status" aria-label="Default select example"
                            required>
                            <option selected>Choose one</option>
                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $data->status == 2 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea type="text" name="description" class="textEditor form-control">{{ $data->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="float-right">
                    <button type="submit" class="btn btn-success">Update Career</button>
                </div>
            </div>
        </div>
    </form>
@endsection
