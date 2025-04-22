<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $primaryKey = 'client_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'client_id',
        'client_name',
        'client_city',
        'client_zone',
        'client_type',
        'client_address',
        'client_email',
        'client_phone',
        'client_discount',
        'created_by',
        'updated_by',
    ];
}
