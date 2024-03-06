@extends('backend.layouts.master')
@section('title', 'Flash Deal List - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Marketing > Flash Deals</span>
                <a class="btn btn-success float-right" href="{{ route('admin.flash_deal.create') }}">Create New Flash Deal</a>
            </div>
        </div>
    </div>
    <div class="grid-margin stretch-card" style="width: 100%;">
        <div class="card">
            <form action="{{ route('admin.flash_deal.reorder') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table id="" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Banner</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Slug</th>
                                <th>Total Products</th>
                                <th>Is Grocery</th>
                                <th>Status</th>
                                <th class="text-center" data-priority="1">Action</th>
                            </tr>
                        </thead>
                        <tbody class="dynamic_attributes sortable">
                            @foreach ($flash_deals as $key => $flash_deal)
                                <tr class="attribute_list">
                                    <input type="hidden" name="flash_deal_ids[]" value="{{ $flash_deal->id }}">
                                    <td><i class="mdi mdi-format-list-bulleted"></i></td>

                                    <td>{{ $flash_deal->title}}</td>
                                    <td><img src="/{{ $flash_deal->banner }}" width="120px" style="border-radius: 0px;">
                                    </td>
                                    <td>{{ $flash_deal->start_date }}</td>
                                    <td>{{ $flash_deal->end_date }}</td>
                                    <td>{{ $flash_deal->slug }}</td>
                                    <td>{{count(explode(',',$flash_deal->product_ids))}}</td>
                                    <td>
                                        @if($flash_deal->is_grocery == 1)
                                            <span class="badge badge-info">Yes</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ 'badge_' . strtolower(Helper::getStatusName('default', $flash_deal->status)) }}">
                                            {{ Helper::getStatusName('default', $flash_deal->status) }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if (Auth::user()->can('flash_deals.edit'))
                                            <a class="text-success"
                                                href="{{ route('admin.flash_deal.edit', $flash_deal->id) }}"><i
                                                    class="icon_btn mdi mdi-pencil-box-outline"></i></a>
                                        @endif

                                        @if (Auth::user()->can('flash_deals.copy'))
                                            @php
                                                $link = env('APP_FRONTEND') . '/flash-deal/' . $flash_deal->slug;
                                            @endphp
                                            <a class=" copyBtn" href="#"
                                                onclick="myFunction('{{ $link }}')"><i
                                                    class="icon_btn text-info mdi mdi-content-copy"></i></a>
                                        @endif

                                        @if (Auth::user()->can('flash_deals.pushnotification'))
                                            <a class=""
                                                href="{{ route('admin.flash_deal.send.pushnotification', $flash_deal->id) }}"><i
                                                    class="icon_btn text-warning mdi mdi-bell-ring"></i></a>
                                        @endif

                                        @if (Auth::user()->can('flash_deals.sms'))
                                            <a class="text-success"
                                                href="{{ route('admin.flash_deal.send.sms', $flash_deal->id) }}"><i
                                                    class="icon_btn text-default mdi mdi-message-processing"></i></a>
                                        @endif

                                        @if (Auth::user()->can('flash_deals.delete'))
                                            <a class="text-danger delete_btn"
                                                data-url="{{ route('admin.flash_deal.delete', $flash_deal->id) }}"
                                                data-toggle="modal" data-target="#deleteModal" href="#"><i
                                                    class="icon_btn text-danger mdi mdi-delete"></i></a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="form-group p-2">
                    <p class="text-right">
                        <button class="btn btn-primary" type="submit">Re-Order Flash Deal</button>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!--Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete this item? </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Once you delete this item. You can not restore this item again!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('footer')
    <script>
        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }

        function myFunction(url) {
            copyToClipboard(url);
            Swal.fire({
                icon: 'success',
                title: 'Copied to clipbord!',
                showConfirmButton: true,
                timer: 1500
            })
        }
    </script>
@endpush
