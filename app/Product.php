<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        '*'
    ];

    protected $table = 'products';
    public $timestamps = false;

    public function brand()
    {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
}
