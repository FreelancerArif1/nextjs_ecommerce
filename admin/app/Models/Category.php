<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\CategoryLocalization;

class Category extends Model
{

    public $fillable = [
        'parent_id',
        'title',
        'slug',
        'description',
        'image',
        'is_active'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_active', 1)->where('hide_on_menu', 0)->where('is_deleted', 0)->orderBy('sort_order', 'ASC');
    }

    public function categories_admin()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_deleted', 0)->orderBy('sort_order', 'ASC');
    }

    public function submenues()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_active', 1)->where('hide_on_menu', 0)->where('is_deleted', 0)->orderBy('sort_order', 'asc');
    }

    public static function categoryTree()
    {
        $items = [];
        $childs = [];
        $categories = Category::where('is_active', 1)->where('hide_on_menu', 0)->where('is_deleted', 0)->orderBy('sort_order', 'ASC')->get();
        foreach ($categories as $category) {
            $items[] = (object) [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'title' => $category->title,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
            ];
        }

        foreach ($items as $item) $childs[$item->parent_id][] = $item;
        foreach ($items as $item) if (isset($childs[$item->id])) $item->childs = $childs[$item->id];
        return self::categoryOutputList($childs[0]);
    }


    public static function categoryOutputList($TreeArray)
    {
        $i = 0;
        foreach ($TreeArray as $arr) {
            echo '<option data-level="' . $i . '">';
            if (is_array($arr)) {
                self::categoryOutputList($arr);
                $i++;
            } else {
                if (is_object($arr)) {
                    echo $arr->title;
                    if (isset($arr->childs)) {
                        self::categoryOutputList($arr->childs);
                        $i++;
                    }
                }
            }
            echo '</option>';
        }
    }


    public function orderdetailsproduct()
    {
        return $this->hasManyThrough(
            OrderDetails::class,
            Product::class,
            'category_id',
            'product_id',
            'id',
            'id'
        );
    }
}