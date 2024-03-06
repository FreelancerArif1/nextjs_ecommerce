<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Response;
use File;
use Helper;

class ProductFeedCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;
    public $handle;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $handle)
    {
        $this->product = $product;
        $this->handle = $handle;
    }



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $availability = '';
        if($this->product->in_stock == 1 && $this->product->qty > 0){
            $availability = 'in stock';
        }else{
            $availability = 'out of stock';
        }
        fputcsv($this->handle, [
            $this->product->id,
            $this->product->title,
            $this->product->description,
            $availability, 
            "new",
            $this->product->price.' BDT',
            env('APP_FRONTEND').'/product/'.$this->product->slug,
            env('APP_URL').'/'.$this->product->default_image,
            $this->product->brand->title ?? 'null',
        ]);
    }
}
