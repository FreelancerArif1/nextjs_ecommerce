
@include('auth.admin.header')
<div class="col-lg-4 mx-auto">
    <h4 class="text-center p-2 text-white">
        <img src="{{ asset('uploads/images/backend-logo.png') }}" width="120px" alt="Admin Logo">
    </h4>
    <div class="auto-form-wrapper">
        {{-- <div class="text-white text-center"><h4>Reset Password</h4></div> --}}
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

       

        <div class="form-group text-center">
            <label class="text-white">Select Password Reset Method</label><br>
            <label class="text-white"><input type="radio" class="radio_btn" name="reset_method" value="email" checked > Email</label>
            <label class="text-white"><input type="radio" class="radio_btn" name="reset_method" value="phone"> Phone</label>
        </div>

        <div class="form-group">
            @error('phone')
                <div class="row">
                    <span class="alert alert-danger col-md-12" role="alert">
                        <strong class="text-">{{ $message }}</strong>
                    </span>
                </div>
            @enderror
            @if(session('error'))
                <div class="row">
                    <span class="alert alert-danger col-md-12" role="alert">
                        <strong class="text-">{{ session('error') }}</strong>
                    </span>
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.password.email') }}" id="email_form" class="">
            @csrf

            <div class="form-group ">
                <label for="email" class="label">{{ __('E-Mail Address') }}</label>

                <div class="input-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter email" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">                    
                <button type="submit" class="btn btn-primary submit-btn btn-block">{{ __('Send Password Reset Link') }}</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.reset.send.otp') }}" id="phone_form" class="d-none">
            @csrf

            <div class="form-group ">
                <label for="phone" class="label">{{ __('Phone Number') }}</label>

                <div class="input-group">
                    <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Enter phone"  autocomplete="phone" autofocus>

                    
                </div>
            </div>

            <div class="form-group">                    
                <button type="submit" class="btn btn-primary submit-btn btn-block">{{ __('Send OTP') }}</button>
            </div>
        </form>
    </div>
</div>
        
<script type="text/javascript">
    $('input[type=radio][name=reset_method]').change(function() {
        var value = $(this).val();
        if (value == 'email') {
            $('#email_form').removeClass('d-none');
            $('#email_form').addClass('d-block');

            $('#phone_form').removeClass('d-block');
            $('#phone_form').addClass('d-none');
        }else{
            $('#email_form').removeClass('d-block');
            $('#email_form').addClass('d-none');

            $('#phone_form').removeClass('d-none');
            $('#phone_form').addClass('d-block');
        }
        // alert(value);
    });
</script>
@include('auth.admin.footer')


