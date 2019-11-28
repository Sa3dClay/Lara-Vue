<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="products";
    
    public $primaryKey="id";
    public $timestumps=true;

    protected $fillable = [
        'name', 'brand', 'quantity', 'price', 'product_pic', 'category', 'descreption', 'visible'
    ];

    public function user(){
        return $this->belongsTo("App\User");
    }
}
