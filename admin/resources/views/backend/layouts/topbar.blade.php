
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-left">
          <a style="margin-left:30px;" class="navbar-brand brand-logo" href="{{route('admin.index')}}">
            <img style="width: 110px !important;" src="{{ asset('uploads/images/backend-logo.png') }}" alt="logo" /> </a>
            <a class="navbar-brand brand-logo-mini" style="text-align: center;width: 100%;" href="{{route('admin.index')}}">
            <img style="width: 45px !important;" src="{{ asset('uploads/images/backend-logo.png') }}" alt="logo" /> </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">

          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a  class="nav-link hover-text-success" href="javascript:void(0)" id="toggle_sidebar" >
                <i class="mdi mdi-swap-horizontal"></i>
              </a>
            </li>

              <li class="nav-item">
                <a target="_blank" class="nav-link hover-text-success" href="{{env('APP_FRONTEND')}}" >
                  <i class="mdi mdi-web"></i>
                </a>
              </li>
          </ul>

          <ul class="navbar-nav m-auto" style="width: 50%;">
            <li class="nav-item" style="width: 100%;position: relative;">
              <div class="form-group has-search mb-0">
                <i class="mdi mdi-magnify form-control-feedback"></i>
                <input type="text" class="form-control" id="search_dashboard" placeholder="Search Dashboard...">
              </div>
              <div id="search_dashboard_results"></div>
            </li>
          </ul>

          <ul class="navbar-nav ml-auto">

            <li class="nav-item">
              <a title="Full Screen" class="nav-link hover-text-success" href="javascript:void(0)" onclick="toggleFullScreen(document.body)">
                <i class="menu-icon mdi mdi-fullscreen"></i>
              </a>
            </li>

            @if(Auth::user()->can('trash.view'))
            <li class="nav-item {{ (Route::is('admin.trash') ) ? 'icon_active' : '' }}">
              <a title="Trash System" class="nav-link hover-text-success" href="{{ route('admin.trash')}}" >
                <i class="menu-icon mdi mdi-delete"></i>
              </a>
            </li>
            @endif

            

            @if(Auth::user()->can('activitylog.view'))
            <li class="nav-item {{ (Route::is('activity.log') ) ? 'icon_active' : '' }}">
              <a title="Activity Log" class="nav-link hover-text-success" href="{{ route('activity.log')}}" >
                <i class="menu-icon mdi mdi-information"></i>
              </a>
            </li>
            @endif

            @if(Auth::user()->can('orderautorenewal.view'))
            <li class="nav-item {{ (Route::is('admin.orderautorenewal') ) ? 'icon_active' : '' }}">
              <a title="Order Auto Renewal" class="nav-link hover-text-success" href="{{ route('admin.orderautorenewal')}}" >
                <i class="menu-icon mdi mdi-cart-plus"></i>
              </a>
            </li>
            @endif
            
            @if(Auth::user()->can('cache.view'))
              <li class="nav-item">
                <a title="Clear Cache" class="nav-link hover-text-success" href="{{ route('admin.cache.clear')}}" >
                  <i class="menu-icon mdi mdi-broom"></i>
                </a>
              </li>

              <li class="nav-item">
                <a title="Run Schedule Jobs" class="nav-link hover-text-success" href="{{ route('admin.schedule.run')}}" >
                  <i class="menu-icon mdi mdi-recycle"></i>
                </a>
              </li>

            @endif


            <li class="nav-item dropdown">
              <a class="nav-link count-indicator" href=" {{ route('admin.notifications')}}" >
                <i class="mdi mdi-bell"></i>
                @php 
                  if(Auth::user()->hasRole('seller')){
                    $notification  = \DB::table('notifications')->where('user_type',2)->where('user_id',Auth::id())->where('status',1)->count();
                  }else{
                    $notification  = \DB::table('notifications')->where('user_type','!=',1)->where('status',1)->count();
                  }
                @endphp
                <span class="count" id="sync_notification" data-notification="{{$notification ?? 0}}" style="background: transparent !important;color: #62b9e8 !important;"> {{$notification ?? 0}}</span>
              </a>
            </li>

            <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              @if(!is_null(Auth::user()->avatar))  
                <img class="img-xs rounded-circle" src="{{ '/'.Auth::user()->avatar }}" alt="Profile image"> 
              @else
              <img class="img-md rounded-circle" src="{{ asset('/uploads/images/default/no-image.png') }}" alt="Profile image">
              @endif
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                @if(!is_null(Auth::user()->avatar))  
                  <img class="img-md rounded-circle" src="{{ '/'.Auth::user()->avatar }}" alt="Profile image">
                  @else
                  <img class="img-md rounded-circle" src="{{ asset('/uploads/images/default/no-image.png') }}" alt="Profile image">
                  @endif
                  <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name}}</p>
                  <p class="font-weight-light text-muted mb-0">{{ Auth::user()->email}}</p>
                </div>

                @if(\Auth::user()->hasRole('seller'))
                  <a href="{{ route('admin.vendor.edit.profile')}}" class="dropdown-item">My Profile</a>
                @else
                  <a href="{{ route('admin.administrator.edit',Auth::id())}}" class="dropdown-item">My Profile</a>
                @endif
                  <a href="{{ route('admin.change.password') }}" class="dropdown-item">Change Password</a>
                  
                  <form action="{{ route('admin.logout')}}" method="post">
                    @csrf
                    <button type="submit" href="{{ route('admin.logout') }}" class="dropdown-item">Sign Out<i class="dropdown-item-icon ti-power-off"></i></button>
                  </form>
                
              </div>
            </li>
          </ul>

        </div>
      </nav>
      <!-- partial -->