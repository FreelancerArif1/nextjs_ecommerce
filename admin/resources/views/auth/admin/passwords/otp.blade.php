@include('auth.admin.header')
<div class="col-lg-4 mx-auto">
	<h4 class="text-center p-2 text-white">
	    <img src="{{ asset('uploads/images/backend-logo.png') }}" width="120px" alt="Admin Logo">
	</h4>
  	<div class="auto-form-wrapper">
    	<div class="form-group">
            @error('phone')
                <div class="row">
                    <span class="alert alert-danger col-md-12" role="alert">
                        <strong class="text-">{{ $message }}</strong>
                    </span>
                </div>
            @enderror
            @if(isset($error))
                <div class="row">
                    <span class="alert alert-danger col-md-12" role="alert">
                        <strong class="text-">{{ $error }}</strong>
                    </span>
                </div>
            @endif
        </div>
    	<form action="{{ route('admin.change.password.withotp') }}" method="post">
		    @csrf
		    <div class="form-group">
		        <label class="label">Phone Number</label>
		        <div class="input-group">
		           	<input id="phone" type="text" placeholder="Enter Phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $phone }}" required autocomplete="phone" autofocus readonly="">
		            @error('phone')
		              	<span class="invalid-feedback" role="alert">
		                	<strong>{{ $message }}</strong>
		              	</span>
		            @enderror
		        </div>
		    </div>
      		<div class="form-group">
		        <label class="label">Enter OTP</label>
		        <div class="input-group">
		           	<input id="otp" type="text" placeholder="Enter OTP" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus>
		            @error('otp')
		              	<span class="invalid-feedback" role="alert">
		                	<strong>{{ $message }}</strong>
		              	</span>
		            @enderror
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="label">Password</label>
		        <div class="input-group">
		          	<input id="password" placeholder="Enter new password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
					@error('password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="label">Confirm Password</label>
		        <div class="input-group">
		          	<input id="confirm_password" placeholder="Confirm password" class="form-control" type="password" required="">
		        </div>
		        <div class="con_p_error">
		          		
		        </div>
		    </div>
      		<div class="form-group">
        		<button type="submit" class="btn btn-primary submit-btn btn-block" disabled="">Change Password</button>
      		</div>
    	</form>
  	</div>
</div>

<script type="text/javascript">
    $("#password, #confirm_password").keyup(function(){
    	var password = $('#password').val();
    	var confirm_password = $('#confirm_password').val();
    	if (password != '' && confirm_password != '') {
    		if (confirm_password === password) {
	    		$('.con_p_error').html('<p class="text-success">Password match!</p>');
	    		$('.submit-btn').prop('disabled', false);
	    	}else{
	    		$('.con_p_error').html('<p class="text-danger">Password not match!</p>');
	    		$('.submit-btn').prop('disabled', true);
	    	}
    	}else{
    		$('.con_p_error').html('<p class="text-danger">Enter password!</p>');
	    	$('.submit-btn').prop('disabled', true);
    	}
    });
</script>

@include('auth.admin.footer')
