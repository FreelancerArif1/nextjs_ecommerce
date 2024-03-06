<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use Auth;
use Response;
use File;
use Helper;

use App\Jobs\ProductFeedCsv;

class ProductFeedGenerate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = \DB::table('products')->where('is_active', 1)->where('is_deleted', 0)->get();

        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=update_product_sample.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        );
        $filename =  public_path("files/product-feed.csv");
        $handle = fopen($filename, 'w');
        fputcsv($handle, [
            "id",
            "title",
            "description",
            "availability",
            "condition",
            "price",
            "link",
            "image_link",
            "brand",
        ]);
        foreach ($products as $product) {
            $availability = '';
            if($product->in_stock == 1 && $product->qty > 0){
                $availability = 'in stock';
            }else{
                $availability = 'out of stock';
            }
            
            fputcsv($handle, [
                $product->id,
                $product->title,
                $product->description,
                $availability, 
                "new",
                $product->price.' BDT',
                env('APP_FRONTEND').'/product/'.$product->slug,
                env('APP_URL').'/'.$product->default_image,
                $product->brand->title ?? 'unknown',
            ]);

        }
        fclose($handle);


        $nData = [];
		$message = [
			'type' => 'Product Feed',
			'message' => 'Product feed has been generated successfully! <a href="'.env('APP_URL').'/files/product-feed.csv'.'">Click here</a> to download.'
		];
		$nData['user_id'] = 1;
		$nData['user_type'] = 3;
		$nData['title'] = 'Product Feed Generated!';
		$nData['description'] = json_encode($message);
		$nData['status'] = 1;
		\DB::table('notifications')->insert($nData);
    }
}
