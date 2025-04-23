<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'courrier_email',
        'courrier_phone',
        'created_by',
        'updated_by',
    ];
}
