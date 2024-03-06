@extends('backend.layouts.master')
@section('title','Ticket Create - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<span class="card-title">Dashboard > Ticket > Replay</span>
      		<a class="btn btn-success float-right" href="{{ route('admin.ticket')}}">View Ticket List</a>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-lg-12">
		<h4 class="text-capitalize heading-theme">TIC{{ $ticket->id}} - {{ $ticket->subject}}</h4>
	</div>
	<div class="col-lg-4 ">
		<div class="grid-margin stretch-card">
			<div class="card">
				<div class="card-body ticket-info">
					<h3 class="heading text-theme">Ticket Info</h3>
					<hr>
					<div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Ticket ID :</div>
                        <div class="ticket_info_name ticket_info_content">
                            TIC{{ $ticket->id}}
                        </div>
                    </div>
                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Subject :</div>
                        <div class="ticket_info_name ticket_info_content">
                            {{ $ticket->subject }}
                        </div>
                    </div>
                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Priority :</div>
                        <div class="ticket_info_name ticket_info_content">
                            <span class="badge badge_default text-white" style="background:{{ $ticket->priority->color}}">{{ $ticket->priority->title }}</span>
                        </div>
                    </div>
                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Department :</div>
                        <div class="ticket_info_name ticket_info_content">
                            <span class="badge badge_default text-white" style="background:{{ $ticket->department->color }}"> {{ $ticket->department->title }}</span>
                        </div>
                    </div>
                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Status :</div>
                        <div class="ticket_info_name ticket_info_content">
                            <span class="badge badge_{{ $ticket->status }}">{{ $ticket->status }}</span>
                        </div>
                    </div>
                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">User Type :</div>
                        <div class="ticket_info_name ticket_info_content text-capitalize">
                            {{ $ticket->user_type }}
                        </div>
                    </div>

                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">User Name :</div>
                        <div class="ticket_info_name ticket_info_content">
                            @if($ticket->user_type == 'seller')
				                {{ $ticket->seller->name }} - {{ $ticket->seller->phone }}
				            @elseif($ticket->user_type == 'customer')
				                {{ $ticket->customer->name }} - {{ $ticket->customer->phone }}
				            @endif
                        </div>
                    </div>
                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Submit Date :</div>
                        <div class="ticket_info_name ticket_info_content">
                            {{ date('d M, Y h:ia', strtotime($ticket->created_at)) }}
                        </div>
                    </div>

                    <div class="ticket_info_list d-flex">
                        <div class="ticket_info_name">Assign To :</div>
                        <div class="ticket_info_name ticket_info_content">
                        	@foreach(\App\Models\Ticket::admins($ticket->id) as $row)
                        		<span class="badge badge-success text-light">{{ $row->name }} - {{ $row->phone }}</span><br>
                            @endforeach
                        </div>
                    </div>

                    <h3 class="heading text-theme">Attachment</h3>
					<hr>

					<div class="ticket_info_list ">
						@if($ticket->attachment)
		                  @foreach(explode(',', $ticket->attachment) as $key => $value)
		                    @if(\App\Models\Ticket::isImage(asset('uploads/tickets/'.$value)) == true)
		                      <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
		                        <img src="{{asset('uploads/tickets/'.$value)}}" class="m-1" height="100px" width="100px" style="border: 1px solid #7AB001; padding: 5px;">
		                      </a>
		                    @else
		                      <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
		                        <img src="{{asset('uploads/tickets/file-icone.png')}}" class="m-1" height="100px" width="100px" style="border: 1px solid #7AB001; padding: 5px;">
		                      </a>
		                    @endif

		                  @endforeach
		                @endif
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-8">
		<div class="grid-margin stretch-card">
			<div class="card">
				<div class="card-body ticket_replay">
					<div class="replay">
						<div class="top-heading d-flex">
							@if($ticket->user_type == 'seller')
								@if($ticket->seller->avatar)
				                	<img src="/{{$ticket->seller->avatar}}" class="user-avator">
				                @else
				                	<img src="{{ asset('default-user.png') }}" class="user-avator">
				                @endif
				                <h4 class="user-name">{{$ticket->seller->name}}</h4>
				            @elseif($ticket->user_type == 'customer')
				                @if($ticket->customer->avatar)
				                	<img src="/{{$ticket->customer->avatar}}" class="user-avator">
				                @else
				                	<img src="{{ asset('default-user.png') }}" class="user-avator">
				                @endif
				                <h4 class="user-name">{{$ticket->customer->name}}</h4>
				            @endif
						</div>
						<div class="replay-body" id="all_replay_area">
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="replay-btn-area">
		  	<button class="replay-btn" onclick="showReplayForm()"><i class="mdi mdi-send"></i></button>
		</div>
		<div class="replay-form card" id="replay_form">
			<form action="" method="post" class="card-body" id="replay_submit_form">
				<input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticket->id }}">
				<h4 class="heading text-theme">Write Your Replay</h4>
				<div class="row">
					<div class="col-lg-12">
						<textarea class="textEditor form-control " name="message" id="message" placeholder="Write your message here" style="height: 100px;" required=""></textarea>
					</div>
					<div class="col-lg-6 mt-1">
						<input type="file" name="attachment[]" id="attachment" multiple="">
					</div>
					<div class="col-lg-6 mt-1 text-right">
						<button class="btn btn-success w-50" id="sendReplay" type="submit">Send</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
		


@push('footer')
  	<script type="text/javascript">
	    jQuery('.customer_select_area').hide();

	    function scrollDown(){
	    	$("html body .replay-body").animate({
				scrollTop: $(
				'html, body .replay-body').get(0).scrollHeight
			}, 1000);
	    }
	    scrollDown();

	    // load all replay
      	function loadAllReplay(){
	        jQuery.ajax({
	            url: "/admin/ticket/view/replay/"+jQuery('#ticket_id').val(),
	            type: "get",
	            data: {},
	            success: function(response) {
	                jQuery('#all_replay_area').html(response);
	            }
	        });
	    }

	    loadAllReplay();

	    jQuery(document).on('change','#user_type',function(e){
	      e.preventDefault();
	      var val = jQuery(this).val();
	       // alert(val);
	      	if (val == 'customer') {
	          	jQuery('.customer_select_area').show();

	          	jQuery('.seller_select_area').hide();
	      	}else{
	          	jQuery('.customer_select_area').hide();

	          	jQuery('.seller_select_area').show();
	      	}
	    });

	    document.getElementById('replay_form').style.display = 'none';

		function showReplayForm() {
		    var x = document.getElementById('replay_form');
		    if (x.style.display === 'none') {
		        x.style.display = 'block';
		    } else {
		        x.style.display = 'none';
		    }
		}  	


		jQuery('#sendReplay').click(function(e){
			e.preventDefault();
			if ((tinymce.EditorManager.get('message').getContent()) == '') {
				Swal.fire({
                    icon: 'error',
                    title: 'Write something on message box!',
                    showConfirmButton: true,
                })
			}else{
				Swal.fire({
	                title: 'Do you want to send ?',
	                text: "",
	                icon: 'question',
	                showCancelButton: true,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: 'Yes, Send now!'
	            }).then((result) => {
		            var formData = new FormData();
		            formData.append('ticket_id', jQuery('#ticket_id').val())
		            formData.append('message', tinymce.EditorManager.get('message').getContent())

		            var totalfiles = document.getElementById('attachment').files.length;
				   	for (var index = 0; index < totalfiles; index++) {
				      	formData.append("image[]", document.getElementById('attachment').files[index]);
				   	}

		            jQuery.ajax({
		                method: 'POST',
		                url: '/admin/ticket/replay/store',
		                headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
		                data: formData,
		                processData: false,
		                contentType: false,
		                success: function (data) {
		                    Swal.fire({
		                        icon: 'success',
		                        title: data.message,
		                        showConfirmButton: false,
		                        timer: 2000,
		                    });
		                    jQuery('#replay_submit_form')[0].reset();
		                    document.getElementById('replay_form').style.display = 'none';
		                    loadAllReplay();
		                    scrollDown();
		                },
		                error: function (xhr) {
		                    var errorMessage = '<div class="card bg-danger">\n' +
		                        '                        <div class="card-body text-center p-5">\n' +
		                        '                            <span class="text-white">';
		                    jQuery.each(xhr.responseJSON.errors, function(key,value) {
		                        errorMessage +=(''+value+'<br>');
		                    });
		                    errorMessage +='</span>\n' +
		                        '                        </div>\n' +
		                        '                    </div>';
		                    Swal.fire({
		                        icon: 'error',
		                        title: 'Oops...',
		                        footer: errorMessage
		                    })
		                },
		            })
		        })
		    }
        });
	</script>
@endpush
@endsection