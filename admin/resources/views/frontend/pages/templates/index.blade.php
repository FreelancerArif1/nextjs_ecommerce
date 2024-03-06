@extends('frontend.layouts.master')
@section('page_title', $page->title )
@section('content')
<div class="container mb-5 " style="height:500px;">
    <ol class="breadcrumb">
        <li><a href="/">Home / &nbsp;</a></li>
        <li class="active"> {{ $page->title }}</li>
    </ol>
	<h3 class="page_title mt-3">{{ $page->title }}</h3>
	{!! $page->description !!}
</div>

@endsection
