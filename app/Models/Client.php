<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
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
        'partner_id',
    ];
    public function partenaire()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}