@extends('backend.layouts.master')
@section('title','Ticket Create - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Create New Ticket</span>
      <a class="btn btn-success float-right" href="{{ route('admin.ticket')}}">View Ticket List</a>
		  </div>
	</div>
</div>
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">

      <form class="form-sample" method="post" action="{{ route('admin.ticket.store')}}" enctype="multipart/form-data" >
        @csrf
        <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Subject <span class="text-danger">*</span> </label>
                <div class="col-sm-9">
                  <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject"  class="form-control" required/>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Priority<span class="text-danger">*</span></label>
                <div class="col-sm-9">
                  <select name="priority_id" class="form-control" required>
                    @foreach($priority as $p)
                    <option value="{{$p->id}}">{{$p->title}}</option>
                    @endforeach
                    
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Message</label>
                <div class="col-sm-9">
                  <textarea name="message" cols="30" rows="10" class="textEditor"></textarea>
                </div>
              </div>
            </div>

           
            <div class="col-md-6">
              @if(!Auth::user()->hasRole('seller'))
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">User Type<span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                    <select name="user_type" class="form-control" id="user_type" required>
                        <option value="seller">Seller</option>
                        <option value="customer">Customer</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row seller_select_area">
                  <label class="col-sm-3 col-form-label">Seller<span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                    <select name="seller_id" class="form-control">
                      @foreach( $vendorArray as $seller)
                        <option value="{{$seller->id}}"  @if(old('user_id') == $seller->id) selected @endif>{{$seller->name ?? ''}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row customer_select_area">
                  <label class="col-sm-3 col-form-label">Customer<span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                    <select name="customer_id" class="form-control">
                      @foreach( $customers as $customer)
                        <option value="{{$customer->id}}"  @if(old('user_id') == $customer->id) selected @endif>{{$customer->name ?? ''}}</option>
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
                      <option value="{{$d->id}}">{{$d->title}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              
              @if(!Auth::user()->hasRole('seller'))
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Status</label>
                  <div class="col-sm-9">
                    <select name="status" class="form-control" required>
                      <option value="open">Open</option>
                      <option value="closed">Closed</option>
                    </select>
                  </div>
                </div>
              @endif

              @if(!Auth::user()->hasRole('seller'))
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Assign To</label>
                  <div class="col-sm-9">
                    <select name="admin_ids[]" class="form-control selectpicker"  data-show-subtext="true" data-live-search="true" multiple required>
                      @foreach($adminArray as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
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
              </div>

              
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <p class="text-right">
                    <button class="btn btn-primary" name="save" type="submit">Add Ticket</button>
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
    $('.customer_select_area').hide();
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