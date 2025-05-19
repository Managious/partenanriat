<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_name', 'product_brand', 'product_category', 'product_subcategory',
        'product_supplier', 'product_cmup', 'product_price', 'product_location', 'product_ean_code',
        'product_stock_1', 'product_image_link', 'created_by', 'updated_by'
    ];

}
