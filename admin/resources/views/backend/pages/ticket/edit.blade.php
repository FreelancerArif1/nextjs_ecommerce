@extends('backend.layouts.master')
@section('title','Ticket Update - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard >Update Ticket</span>
      <a class="btn btn-success float-right" href="{{ route('admin.ticket')}}">View Ticket List</a>
		  </div>
	</div>
</div>
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">

      <form class="form-sample" method="post" action="{{ route('admin.ticket.update',$data->id)}}" enctype="multipart/form-data" >
        @csrf
        <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Subject <span class="text-danger">*</span> </label>
                <div class="col-sm-9">
                  <input type="text" name="subject" value="{{ $data->subject }}" placeholder="Subject"  class="form-control" required/>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Priority<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select name="priority_id" class="form-control" required>
                    @foreach($priority as $p)
                    <option value="{{$p->id}}" @if($p->id == $data->priority_id) selected @endif>{{$p->title}}</option>
                    @endforeach
                    
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Message</label>
                <div class="col-sm-9">
                  <textarea name="message" cols="30" rows="10" class="textEditor">{{$data->message}}</textarea>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">User Type<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select name="user_type" class="form-control" required>
                    @if(Auth::user()->hasRole('seller'))
                      <option value="seller" selected>Seller</option>
                    @else
                      <option value="seller"  @if($data->user_type == 'seller') selected @endif >Seller</option>
                      <option value="customer"  @if($data->user_type == 'customer') selected @endif>Customer</option>
                    @endif
                  </select>
                </div>
              </div>

              @if($data->user_type == 'seller')
                <div class="form-group row seller_select_area">
                  <label class="col-sm-3 col-form-label">Seller<span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                    <select name="seller_id" class="form-control">
                      @if(Auth::user()->hasRole('seller'))
                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                      @else
                          @foreach( $vendorArray as $seller)
                            <option value="{{$seller->id}}"  @if($seller->id == $data->user_id) selected @endif >{{$seller->name ?? ''}}</option>
                          @endforeach
                      @endif
                    </select>
                  </div>
                </div>
              @endif


              @if($data->user_type == 'customer')
                <div class="form-group row customer_select_area">
                  <label class="col-sm-3 col-form-label">Customer<span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                    <select name="customer_id" class="form-control">
                      @foreach( $customers as $customer)
                        <option value="{{$customer->id}}"  @if($data->user_id == $customer->id) selected @endif>{{$customer->name ?? ''}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              @endif


              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Department<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select name="department_id" class="form-control" required>
                      @foreach($department as $d)
                      <option value="{{$d->id}}" @if($d->id == $data->department_id) selected @endif  >{{$d->title}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                  <select name="status" class="form-control" required>
                    @if(Auth::user()->hasRole('seller'))
                      <option value="open" selected>Open</option>
                    @else
                      <option value="open" @if($data->status == 'open') selected @endif >Open</option>
                      <option value="closed" @if($data->status == 'closed') selected @endif >Closed</option>
                    @endif
                  </select>
                </div>
              </div>

              @if(!Auth::user()->hasRole('seller'))
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Assign To</label>
                  <div class="col-sm-9">
                    <select name="admin_ids[]" class="form-control selectpicker"  data-show-subtext="true" data-live-search="true" multiple required>
                      @foreach($adminArray as $admin)
                        <option value="{{ $admin->id }}" @if($data->admin_ids && in_array($admin->id, explode(',',$data->admin_ids))) selected="" @endif>{{ $admin->name }}</option>
                        }
                      @endforeach
                    </select>
                  </div>
                </div>
              @endif


              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Attachment</label>
                <div class="col-sm-9">
                 <input type="file" multiple name="attachment[]">
                </div>

                @if($data->attachment)
                  @foreach(explode(',', $data->attachment) as $key => $value)
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

            <div class="col-md-12">
              <div class="form-group">
                <p class="text-right">
                    <button class="btn btn-primary" name="save" type="submit">Update Ticket</button>
                </p>
              </div>
            </div>

          </div>


        </form>
      </div>
    </div>
  </div>
</div>
      
@push('footer')
  <script type="text/javascript">
    jQuery(document).on('change','#user_type',function(e){
      e.preventDefault();
      var val = $(this).val();
       // alert(val);
      if (val == 'customer') {
          $('.customer_select_area').show();

          $('.seller_select_area').hide();
      }else{
          $('.customer_select_area').hide();

          $('.seller_select_area').show();
      }
    });
  </script>
@endpush

@endsection