<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Client;
use App\Models\Product;

class Order extends Model 
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_sale_price',
        'product_id',
        'product_sale_price',
        'product_discount_1',
        'invoice_nature',
        'invoice_number',         
        'invoice_date',         
        'invoice_sale_point',
        'quantity',
        'ordered_by',             
        'ordered_on',
        'invoiced_by',
        'invoiced_on',
        'delivered_by',
        'delivered_on',
        'payment_type',
        'payment_amount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($order) {
        $order->invoice_number = time();
        $order->invoice_date = now();
        $order->invoice_nature = 'Standard';
        $order->invoice_sale_point = 'Default Point';
        $order->invoiced_by = auth()->check() ? auth()->user()->name : 'System';
        $order->invoiced_on = now();
        $order->ordered_by = auth()->check() ? auth()->user()->name : 'System';
        $order->ordered_on = now();
    });
}

}