<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        '*'
    ];

    protected $table = 'purchases';
    public $timestamps = false;

    public function products()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
