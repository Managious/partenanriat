<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // protected $primaryKey = 'supplier_id';
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        /*'supplier_id',*/ 'supplier_name', 'supplier_city', 'supplier_zone', 'supplier_type',
        'supplier_address', 'supplier_email', 'supplier_phone', 'created_by', 'updated_by'
    ];

//     protected static function boot()
// {
//     parent::boot();

//     static::creating(function ($supplier) {
//         $supplier->supplier_id = 'SUP-' . time();
//     });
// }
}
