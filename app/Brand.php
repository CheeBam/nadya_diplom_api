<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        '*'
    ];

    protected $table = 'brands';
    public $timestamps = false;

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
