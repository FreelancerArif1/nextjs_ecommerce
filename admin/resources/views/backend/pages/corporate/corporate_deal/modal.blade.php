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
                <p>User Name: {{ $corporate_deal->username->name ?? null }}</p>
                <p>Request ID: {{ $corporate_deal->request_id }}</p>
                <p>Product Quantity: {{ $corporate_deal->amount }} BDT</p>
                <p>Price: {{ $corporate_deal->discount }} BDT</p>
                <p>Discount: {{ $corporate_deal->invoice }}</p>
                <p>Work Order: {{ $corporate_deal->work_order }}</p>
                <p>Preferable Date: {{ $corporate_deal->preferable_date }}</p>
                <p>For Delivery: {{ $corporate_deal->for_delivery }}</p>
                <p>Delivery Date: {{ $corporate_deal->delivery_date }}</p>
                <p>Payment Details: {{ $corporate_deal->payment_details }}</p>
                {{-- <p>Status: @if ($corporate_deal->status == 1)
                        Active
                    @else
                        Inactive
                    @endif
                </p> --}}

            </div>
        </div>
    </div>
</div>
