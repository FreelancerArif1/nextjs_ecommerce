<div class="row" style="min-height:300px;overflow-y: auto;">
    <div class="col-md-12">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <div class="container">
            <div class="col-md-12 row">
                <div class="col-md-4 col-lg-4 col-sm-12 col-12">
                    <div class="form-group row mb-2">
                        <label for="" class="col-md-3">First Name:</label>
                        <div class="col-md-9">
                            <h6>{{ $career_request->first_name }}</h6>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="" class="col-md-3">Last Name:</label>
                        <div class="col-md-9">
                            <h6>{{ $career_request->last_name }}</h6>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="" class="col-md-3">Email:</label>
                        <div class="col-md-9">
                            <h6>{{ $career_request->email }}</h6>

                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="" class="col-md-3">Phone:</label>
                        <div class="col-md-9">
                            <h6>{{ $career_request->phone_number }}</h6>

                        </div>
                    </div>

                </div>
                <div class="col-md-8 col-lg-8 col-sm-12 col-12">
                    <div class="form-group mb-2">
                        <label for="" class="">Cover Letter:</label>
                        <div class="">
                            <h6 class="text-justify">{{ $career_request->cover_letter }}</h6>

                        </div>
                    </div>

                    <embed src="/uploads/jobapplication/{{ $career_request->file }}" width="100%" height="100%"
                        style="min-height: 300px">
                </div>
            </div>

        </div>
    </div>
</div>
