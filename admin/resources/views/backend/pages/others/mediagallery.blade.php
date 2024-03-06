@extends('backend.layouts.master')
@section('title','Media Gallery - '.config('concave.cnf_appname'))
@section('content')

<iframe id="iframe" style="width: 100%;height: 100%;" src="/concave-gallery?gallery=self&inputType=multiple" frameborder="0"></iframe>
@endsection
