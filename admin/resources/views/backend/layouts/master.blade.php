<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @hasSection('title') @yield('title') @else {{ config('concave.cnf_appname') }} | Dashboard  @endif</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/iconfonts/ionicons/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/iconfonts/typicons/src/font/typicons.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/jquery-ui/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/shared/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/demo_1/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/bootstrap/css/bootstrap-select.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" />
    <script src="{{ asset('backend/assets/vendors/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

    <!--SweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    
    @if(\Auth::user()->hasRole('seller'))
     <link rel="stylesheet" href="{{asset('backend/assets/chat/chat_style.css')}}" />
    @endif

  </head>
  <body @if(\Auth::user()->hasRole('seller')) class="seller_panel" @endif>
    <style>
      #sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
      #sortable li {margin: 0 3px 5px 3px;padding: 8px 0 0;padding-left: 35px;font-size: 1.4em;}
      #sortable li span { position: absolute;margin-left: -12px;margin-top: 3px;cursor:grab;}
      #sortable li i{cursor:pointer;}
      .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
      .sortable li {margin: 0 3px 5px 3px;padding: 8px 0 0;padding-left: 35px;font-size: 1.4em;}
      .sortable li span { position: absolute;margin-left: -12px;margin-top: 3px;cursor:grab;}
      .sortable li i{cursor:pointer;}
      .close_option{font-size: 20px;padding: 5px;cursor: pointer;position: absolute;right: 2px; top: -5px;}
      .option_drag{position: absolute;top: 50%;transform: translateY(-50%);font-size: 22px;margin-left: 5px;cursor: grab;}
    </style>
  
    <div class="container-scroller">
      @include('backend.layouts.topbar')
        <div class="container-fluid page-body-wrapper">
            @include('backend.layouts.left_sidebar')

          <div class="main-panel">
            <div class="content-wrapper">
              @include('backend.parts.messages')
              @yield('content')
            </div>
          </div>
        </div>
    </div>

    @include('backend.layouts.modal')


    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('backend/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('backend/assets/js/demo_1/dashboard.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('backend/assets/vendors/jquery/nestable.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom.js') }}"></script>
    <!-- End custom js for this page-->

    <!-- Buttons examples -->
    <script src="{{asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script  src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>




    <script>
      jQuery(document).on('click','.initConcaveMedia',function(){
         var inputName,inputType,imageWidth,imageHeight;
         
         inputName = jQuery(this).attr('data-input-name');
         inputType = jQuery(this).attr('data-input-type');
         imageWidth = jQuery(this).attr('data-image-width');
         imageHeight = jQuery(this).attr('data-image-height');
         imageResize = jQuery(this).attr('data-resize');
         fileLocation = jQuery(this).attr('data-file-location');
         
         jQuery(this).addClass('triggeredButton');
          jQuery.ajax({
            url: "{{route('concave.gallery')}}",
                type: "get",
                data: {inputName:inputName,inputType:inputType,imageWidth:imageWidth,imageHeight:imageHeight,imageResize:imageResize,fileLocation:fileLocation} ,
                success: function (response) {
                    jQuery('body').prepend(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  console.log(textStatus, errorThrown);
                }
          });
      })
  </script>

    @include('concaveit_media::includes/styles')
   <script>
      jQuery(document).on('click','.selected_image_remove',function(){
         var removeItem = jQuery(this).attr('data-file-url');
         jQuery(this).closest('span').remove();
         jQuery('input[value="'+removeItem+'"]').remove();
      });
   </script>

  	@stack('footer')

    
  </body>
</html>