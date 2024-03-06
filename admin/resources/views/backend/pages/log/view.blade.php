@if(isset($log))	
	{{-- @if ($log->properties)
	    <div class="row">
	        <div class="col-md-6">
	            @if (json_decode($log->properties))
	                <h4>New Values</h4>
	                @php $attributes = isset(json_decode($log->properties)->attributes) ? json_decode($log->properties)->attributes : [] @endphp
	                @foreach ($attributes as $key => $val)
	                    <p><b>{{ $key }}:</b>{!! $val !!}
	                    </p>
	                @endforeach
	            @endif
	        </div>
	        <div class="col-md-6">
	            @if (json_decode($log->properties))
	                <h4>Old Values</h4>
	                @php $old = isset(json_decode($log->properties)->old) ? json_decode($log->properties)->old : [] @endphp
	                @foreach ($old as $key => $val)
	                    <p><b>{{ $key }}:</b>{!! $val !!}
	                    </p>
	                @endforeach
	            @endif
	        </div>
	    </div>
	@endif --}}

			
	@if($log)
	<table style="width: 100%;" class="table-striped">
				<thead>
					<tr>
						<td><b>Field Key</b></td>
						<td><b>New Value</b></td>
						<td><b>Old Value</b></td>
					</tr>
				</thead>
				<tbody>
					@foreach($log as $key => $val)
						@if(is_array($val))
							@if($val)
								<tr>
									<td>{{$key}}</td>
									<td>{{$val[1] ?? '' }}</td>
									<td>{{$val[0] ?? '' }}</td>
								</tr>
							@endif
						@else
							<tr>
								<td>{{$key}}</td>
								<td>{{$val ?? '' }}</td>
								<td></td>
							</tr>
						@endif
					@endforeach
				</tbody>
		</table>
	@else
		<p class="text-center p-5">No data was change during action!</p>
	@endif



	</div>



@endif