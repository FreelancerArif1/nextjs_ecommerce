@extends('backend.layouts.master')
@section('page_title', 'Settings')
@section('content')


    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Settings > Designs</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            @include('backend.parts.design_tabs')
                        </div>
                        <div class="col-sm-9">
                            <form class="form-sample" method="post" action="{{ route('admin.design.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content" id="v-pills-tabContent">

                                    <!-- Product Section Starts -->
                                    <div class="tab-pane fade show active" id="Product" role="tabpanel">
                                        @include('backend.parts.designs.home-layout')
                                    </div>
                                    <!-- Product Section ends -->

                                    <!--  Grocery Layout Start-->
                                    <div class="tab-pane fade" id="GroceryLayout" role="tabpanel">
                                        @include('backend.parts.designs.design-grocery-layout')
                                    </div>
                                    <!-- Grocery Layout section end-->


                                    <!-- All Promotional banner Start-->
                                    <div class="tab-pane fade" id="All_promotional_banner" role="tabpanel">
                                        @include('backend.parts.designs.promotion-offer')
                                    </div>
                                    <!-- All Promotional banner End-->


                                    <!-- GENERAL  Section Starts -->
                                    <div class="tab-pane fade" id="General" role="tabpanel">

                                        @include('backend.parts.designs.general')
                                    </div>
                                    <!-- GENERAL Products Section ends -->

                                    <!-- lOGO  Section Starts -->
                                    <div class="tab-pane fade" id="Logo" role="tabpanel">

                                        @include('backend.parts.designs.logo')
                                    </div>
                                    <!-- LOGO Products Section ends -->

                                    <!-- Social Links Section Starts -->
                                    <div class="tab-pane fade" id="SocialLinks" role="tabpanel">
                                        @include('backend.parts.designs.social-links')
                                    </div>
                                    <!-- Social Links Favorites Products Section ends -->


                                    <!-- SMS GATEWAY Section Starts -->
                                    <div class="tab-pane fade" id="sms_gateway" role="tabpanel">
                                        @include('backend.parts.designs.sms-geteway')
                                    </div>
                                    <!-- SMS GATEWAY Section ends -->


                                    <!-- SMS Template Section Starts -->
                                    <div class="tab-pane fade" id="sms_template" role="tabpanel">
                                        @include('backend.parts.designs.sms-template')
                                    </div>
                                    <!-- SMS Template Section ends -->


                                    <!-- Scripts Template Section Starts -->
                                    <div class="tab-pane fade" id="scripts" role="tabpanel">
                                        @include('backend.parts.designs.custom-script')
                                    </div>
                                    <!-- Scripts Template Section ends -->


                                    <!-- Scripts Offer Section Starts -->
                                    <div class="tab-pane fade" id="Offers" role="tabpanel">
                                        @include('backend.parts.designs.regular-offer')
                                    </div>
                                    <!-- Scripts Offer Section ends -->


                                </div>
                                <p class="text-right">
                                    <button type="submit" class="btn btn-primary">Apply
                                        Changes</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(document).on("change", ".sms_gateway_default_provider", function() {
                    var sms_gateway_default_provider = jQuery(this).val();
                    if (sms_gateway_default_provider == 'icombd') {
                        jQuery('.sms_gateway_username_area').removeClass('d-none');
                        jQuery('.sms_gateway_username_area').addClass('d-block');

                        jQuery('.sms_gateway_password_area').removeClass('d-none');
                        jQuery('.sms_gateway_password_area').addClass('d-block');

                        jQuery('.musking_api_key_area').removeClass('d-block');
                        jQuery('.musking_api_key_area').addClass('d-none');

                        jQuery('.nonmusking_api_key_area').removeClass('d-block');
                        jQuery('.nonmusking_api_key_area').addClass('d-none');
                    }else if (sms_gateway_default_provider == 'metrotel') {

                        jQuery('.musking_api_key_area').removeClass('d-none');
                        jQuery('.musking_api_key_area').addClass('d-block');

                        jQuery('.sender_id_area_musking').removeClass('d-none');
                        jQuery('.sender_id_area_musking').addClass('d-block');

                        jQuery('.nonmusking_api_key_area').removeClass('d-block');
                        jQuery('.nonmusking_api_key_area').addClass('d-none');

                        

                        jQuery('.nonmusking_sender_id_area').removeClass('d-block');
                        jQuery('.nonmusking_sender_id_area').addClass('d-none');

                        jQuery('.sms_gateway_username_area').removeClass('d-block');
                        jQuery('.sms_gateway_username_area').addClass('d-none');

                        jQuery('.sms_gateway_password_area').removeClass('d-block');
                        jQuery('.sms_gateway_password_area').addClass('d-none');
                    }
                    else{
                        jQuery('.musking_api_key_area').removeClass('d-none');
                        jQuery('.musking_api_key_area').addClass('d-block');

                        jQuery('.nonmusking_api_key_area').removeClass('d-none');
                        jQuery('.nonmusking_api_key_area').addClass('d-block');

                        jQuery('.sender_id_area_musking').removeClass('d-none');
                        jQuery('.sender_id_area_musking').addClass('d-block');


                        jQuery('.nonmusking_sender_id_area').removeClass('d-none');
                        jQuery('.nonmusking_sender_id_area').addClass('d-block');

                        jQuery('.sms_gateway_username_area').removeClass('d-block');
                        jQuery('.sms_gateway_username_area').addClass('d-none');

                        jQuery('.sms_gateway_password_area').removeClass('d-block');
                        jQuery('.sms_gateway_password_area').addClass('d-none');
                    }
                })
            })
        </script>
    @endpush
@endsection
