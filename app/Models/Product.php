<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ImagesMultiple;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_product',
        'description_product',
        'harga_product',
        'id_kategori',
        'cover_image_product',
        'type_car',
        'nik_car',
        'status'
    ];

    public function images_multiples()
    {
        return $this->hasMany(ImagesMultiple::class);
    }
}
