<div class="message new text-justify">
	@if($ticket->user_type == 'seller')
		@if($ticket->seller->avatar)
			<figure class="avatar">
				<img src="/{{$ticket->seller->avatar}}">
			</figure>
        @else
        	<figure class="avatar">
				<img src="{{ asset('default-user.png') }}">
			</figure>
        @endif
    @elseif($ticket->user_type == 'customer')
        @if($ticket->customer->avatar)
        	<figure class="avatar">
				<img src="/{{$ticket->customer->avatar}}">
			</figure>
        @else
        	<figure class="avatar">
				<img src="{{ asset('default-user.png') }}">
			</figure>
        @endif
    @endif
	{!! $ticket->message !!}<br>
	@if($ticket->attachment)
      	@foreach(explode(',', $ticket->attachment) as $key => $value)
            @if(\App\Models\Ticket::isImage(asset('uploads/tickets/'.$value)) == true)
              <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
                <img src="{{asset('uploads/tickets/'.$value)}}" class="m-1" height="100px" width="150px" style="border: 1px solid #7AB001; padding: 5px;">
              </a>
            @else
              <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
                <img src="{{asset('uploads/tickets/file-icone.png')}}" class="m-1" height="100px" width="100px" style="border: 1px solid #7AB001; padding: 5px;">
              </a>
            @endif
      	@endforeach
    @endif
	<div class="timestamp">{{ date('d M, Y h:ia', strtotime($ticket->created_at)) }}</div>
	
	<div class="sender">
		@if($ticket->user_type == 'seller')
			{{$ticket->seller->name}}
        @elseif($ticket->user_type == 'customer')
            {{$ticket->customer->name}}
        @endif
	</div>
</div>

@if($ticket_replay)
	@foreach($ticket_replay as $replay)
		@if(!$replay->admin_id)
			<div class="message new text-justify">
				@if($replay->user_type == 'seller')
					@if($replay->seller->avatar)
						<figure class="avatar">
							<img src="/{{$replay->seller->avatar}}">
						</figure>
	                @else
	                	<figure class="avatar">
							<img src="{{ asset('default-user.png') }}">
						</figure>
	                @endif
	            @elseif($replay->user_type == 'customer')
	                @if($replay->customer->avatar)
	                	<figure class="avatar">
							<img src="/{{$replay->customer->avatar}}">
						</figure>
	                @else
	                	<figure class="avatar">
							<img src="{{ asset('default-user.png') }}">
						</figure>
	                @endif
	            @endif
				{!! $replay->message !!}<br>
				@if($replay->attachment)
                  	@foreach(explode(',', $replay->attachment) as $key => $value)
	                    @if(\App\Models\Ticket::isImage(asset('uploads/tickets/'.$value)) == true)
	                      <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
	                        <img src="{{asset('uploads/tickets/'.$value)}}" class="m-1" height="100px" width="150px" style="border: 1px solid #7AB001; padding: 5px;">
	                      </a>
	                    @else
	                      <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
	                        <img src="{{asset('uploads/tickets/file-icone.png')}}" class="m-1" height="100px" width="100px" style="border: 1px solid #7AB001; padding: 5px;">
	                      </a>
	                    @endif
                  	@endforeach
                @endif
				<div class="timestamp">{{ date('d M, Y h:ia', strtotime($replay->created_at)) }}</div>
				@if($replay->status == 0)
					<div class="checkmark-sent-delivered">✓</div>
				@else
					<div class="checkmark-sent-delivered">✓</div>
					<div class="checkmark-read">✓</div>
				@endif
				<div class="sender">
					@if($replay->user_type == 'seller')
						{{$replay->seller->name}}
		            @elseif($replay->user_type == 'customer')
		                {{$replay->customer->name}}
		            @endif
				</div>
			</div>
		@else
			<div class="message message-personal new text-justify">
				{!! $replay->message !!}<br>
				@if($replay->attachment)
                  	@foreach(explode(',', $replay->attachment) as $key => $value)
	                    @if(\App\Models\Ticket::isImage(asset('uploads/tickets/'.$value)) == true)
	                      <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
	                        <img src="{{asset('uploads/tickets/'.$value)}}" class="m-1" height="100px" width="150px" style="border: 1px solid #7AB001; padding: 5px;">
	                      </a>
	                    @else
	                      <a href="{{asset('uploads/tickets/'.$value)}}" target="_blank">
	                        <img src="{{asset('uploads/tickets/file-icone.png')}}" class="m-1" height="100px" width="100px" style="border: 1px solid #7AB001; padding: 5px;">
	                      </a>
	                    @endif
                  	@endforeach
                @endif
				<div class="reciever-timestamp">{{ date('d M, Y h:ia', strtotime($replay->created_at)) }}</div>
				@if($replay->status == 0)
					<div class="reciever-checkmark-sent-delivered">✓</div>
				@else
					<div class="reciever-checkmark-sent-delivered">✓</div>
					<div class="reciever-checkmark-read">✓</div>
				@endif
				<div class="reciever">{{ $replay->admin->name }}</div>
			</div>
		@endif
	@endforeach
@endif