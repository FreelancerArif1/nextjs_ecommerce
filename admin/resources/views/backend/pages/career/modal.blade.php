<div class="row">
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
            <div>
                <p>Post Date: {{ $career->post_date }}</p>
                <p>End Date: {{ $career->end_date }}</p>
                <h4>Position: {{ $career->position }}</h4>
                <div>
                    {!! $career->description !!}
                </div>
            </div>
        </div>
    </div>
</div>
