@include('auth.admin.header')

<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    .login-container {
        height: 100%;
        overflow: hidden; /* Hide any content that might exceed the container */
    }

    /* Add any additional styles for the background image here */
    .login-bg img {
        width: 100%;
        height: 100vh;
        object-fit: cover; /* Ensure the image covers the entire container */
    }
</style>

<div class="container-fluid login-container">
    <div class="row">
        <!-- Left Column with Background Image -->
        <div class="col-md-6 d-none d-md-block login-bg">
            <!-- Replace 'path/to/background-image.jpg' with the actual URL or path to your background image -->
            <img src="{{ asset('uploads/images/background-image.jpg') }}" alt="Background Image">
        </div>

        <!-- Right Column with Login Box -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="card" style="background: #52abe5;">
                <div class="card-body">
                    <h4 class="text-center">
                        <img src="{{ asset('uploads/images/backend-logo.png') }}" width="120px" alt="Admin Logo">
                    </h4>

                    @if(session()->has('failed'))
                        <div class="alert alert-danger">{{ session()->get('failed') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('admin.login') }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email or Mobile Number</label>
                            <input id="email" type="text" placeholder="Enter email or mobile number" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" placeholder="Enter password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('admin.password.request')}}" class="text-small">Forgot Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('auth.admin.footer')
