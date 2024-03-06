@extends('frontend.layouts.master')
@section('page_title', $page->title )
@section('content')

    <main id="content">
        <ol class="breadcrumb">
            <li><a href="/">Home / &nbsp;</a></li>
            <li class="active">Contact Us</li>
        </ol>

        <div class="container mt-3">
            <div class="row">

                <div class="col-12 col-sm-12 col-md-6">

					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15143.62686544409!2d-81.38838953451175!3d28.531460974085764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e773d8fecdbc77%3A0xac3b2063ca5bf9e!2sOrlando%2C%20FL%2C%20USA!5e0!3m2!1sen!2sbd!4v1631956707144!5m2!1sen!2sbd" style="border:0;" allowfullscreen="" loading="lazy"></iframe>	
                    <div class="mb-4 mb-lg-7">
                        <h6 class="font-weight medium font-size-10 mt-3">Contact Information</h6>
                        <p class="font-weight-medium">We will answer any questions you may have about our online sales, rights or partnership service right here.</p>
                    </div>
                    <div class="mb-4 mb-lg-8">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="font-weight-medium font-size-4">Office</h6>
                                <address class="font-size-2">
                                      <span class="mb-2 font-weight-normal text-dark">
                                         Orlando, Florida  32804
                                      </span>
                                </address>
                                <div>
                                    <a href="mailto:{{ config('concave.cnf_email') }}" class="font-size-2 d-block link-black-100 mb-1">contact@creationsforyou.co</a>
                                    <a href="tel:407-648-2302" class="font-size-2 d-block link-black-100">Call: 407-648-2302</a>
                                </div>
                            </div>
                        </div>
                    </div>
                  <!--  <div class="mb-5 mb-xl-9 pb-xl-1">
                        <h6 class="font-size-4 font-weight-medium">Social Media</h6>
                        <ul class="list-unstyled mb-0 d-flex">
                            <li class="btn pl-0">
                                <a target="_blank" class="link-black-100" href="{{ Helper::getSettings('social_links_instagram') }}">
                                    <span class="fab fa-instagram"></span>
                                </a>
                            </li>
                            <li class="btn">
                                <a target="_blank" class="link-black-100" href="{{ Helper::getSettings('social_links_facebook') }}">
                                    <span class="fab fa-facebook-f"></span>
                                </a>
                            </li>
                            <li class="btn">
                                <a target="_blank" class="link-black-100" href="{{ Helper::getSettings('social_links_youtube') }}">
                                    <span class="fab fa-youtube"></span>
                                </a>
                            </li>
                            <li class="btn">
                                <a target="_blank" class="link-black-100" href="{{ Helper::getSettings('social_links_twitter') }}">
                                    <span class="fab fa-twitter"></span>
                                </a>
                            </li>
                            <li class="btn">
                                <a target="_blank" class="link-black-100" href="{{ Helper::getSettings('social_links_pinterest') }}">
                                    <span class="fab fa-pinterest"></span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
                </div>

                <div class="col-12 col-sm-12 col-md-6">
                    <div class="ml-xl-4">
                        <div>
                            <h6 class="font-weight-medium font-size-10 mb-3 pb-xl-1">Get In Touch</h6>
                            <form class="contact_form" method="post"  action="{{ route('contact.mail')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 mb-5">
                                        <div class="js-form-message">
                                            <label for="exampleFormControlInput1">Name</label>
                                            <input id="exampleFormControlInput1" type="text" class="form-control rounded-0" name="name">
                                            <small class="text-danger">{{ $errors->first('name') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-5">
                                        <div class="js-form-message">
                                            <label for="exampleFormControlInput2">Email</label>
                                            <input id="exampleFormControlInput2" type="email" class="form-control rounded-0" name="email">
                                            <small class="text-danger">{{ $errors->first('email') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mb-5">
                                        <div class="js-form-message">
                                            <label for="exampleFormControlInput3">Subject</label>
                                            <input id="exampleFormControlInput3" type="text" class="form-control rounded-0" name="subject">
                                            <small class="text-danger">{{ $errors->first('subject') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mb-5">
                                        <div class="js-form-message">
                                            <div class="input-group flex-column">
                                                <label for="exampleFormControlInput4">Details please! Your review helps other shoppers.</label>
                                                <textarea id="exampleFormControlInput4" class="form-control rounded-0 pl-3 font-size-2 placeholder-color-3" rows="6" cols="77" name="message" placeholder="What did you like or dislike? What should other shoppers know before buying?"></textarea>
                                                <small class="text-danger">{{ $errors->first('message') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex justify-content-lg-start mb-5">
                                    <div class="col d-flex justify-content-lg-start mb-5">
                                        <button type="submit" class="btn site_btn" style="color:#fff;">Sumbit Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </main>
@endsection
