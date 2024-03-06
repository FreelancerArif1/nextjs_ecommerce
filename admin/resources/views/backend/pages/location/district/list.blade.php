@extends('backend.layouts.master')
@section('title', 'Blog List - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Location > District</span>
                <a class="btn btn-success float-right" href="{{ route('admin.location.district.create') }}">Create New
                    District</a>
            </div>
        </div>
    </div>


    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="designed_table">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Division</th>
                            <th>Title</th>
                            <th data-priority="1" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->division->title ?? '' }}</td>
                                <td>{{ $d->title }}</td>
                                <td class="text-center">
                                    @if (Auth::user()->can('location.edit'))
                                        <a class="text-success"
                                            href="{{ route('admin.location.district.edit', $d->id) }}"><i
                                                class="mdi mdi-pencil-box-outline"></i></a>
                                    @endif
                                    @if (Auth::user()->can('location.delete'))
                                        <a class="text-danger delete_btn"
                                            data-url="{{ route('admin.location.district.delete', $d->id) }}"
                                            data-toggle="modal" data-target="#deleteModal" href="#"><i
                                                class="mdi mdi-delete"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
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
                    <p>Once you delete this item, you can restore this from trash list!</p>
                    <textarea name="reason" id="reason" placeholder="Write reason, why you want to delete this item."
                        class="form-control"></textarea>
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
    <script type="text/javascript">
        function fetchData() {
            // alert(filter_by);
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,

                ajax: {
                    url: "{{ url('admin/location/district/get-district') }}",
                    type: 'GET',
                    // data: {
                    //     'filter_by': filter_by,
                    //     'start_date': start_date,
                    //     'end_date': end_date,
                    // }
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "language": {
                    "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                },
                "order": [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        "className": "text-center",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'division_title',
                        name: 'division_title',
                        "className": "text-center",
                    },
                    {
                        data: 'title',
                        name: 'title',
                        "className": "text-center",
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        "className": "text-center"
                    },

                ]
            });
        }
        fetchData();


        // jQuery(document).on('change', '#filter_option', function(e) {
        //     e.preventDefault();
        //     var val = $(this).val();
        //     if (val == 'date range') {
        //         $('#start_date_area').removeClass('d-none');
        //         $('#start_date_area').addClass('d-block');

        //         $('#end_date_area').removeClass('d-none');
        //         $('#end_date_area').addClass('d-block');
        //     } else {
        //         $('#start_date_area').removeClass('d-block');
        //         $('#start_date_area').addClass('d-none');

        //         $('#end_date_area').removeClass('d-block');
        //         $('#end_date_area').addClass('d-none');
        //     }
        // });



        // $('#filter_data').click(function() {
        //     // e.preventDefault();
        //     jQuery('#dataTable').DataTable().destroy();
        //     fetchData($('#filter_option').val(), $('#start_date').val(), $('#end_date').val());
        // });
    </script>
@endpush
