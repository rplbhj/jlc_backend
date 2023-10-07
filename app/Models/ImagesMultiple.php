<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ImagesMultiple extends Model
{
    use HasFactory;

    protected $fillable = [
        'images_multiple', 'product_id'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
