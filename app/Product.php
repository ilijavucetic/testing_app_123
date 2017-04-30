<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    public function category()
    {
        return $this->hasOne('App\Category');
    }


    public function prices()
    {
        return $this->hasMany('App\Price');
    }

    public function taxes()
    {
        return $this->hasMany('App\Tax');
    }

    public function product_images()
    {
        return $this->hasMany('App\Product_image');
    }
    public function order_product()
    {
        return $this->hasMany('App\Order_product');
    }
    public function comment()
    {
        return $this->hasMany('App\comment');
    }



}