<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}