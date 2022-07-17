<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_number',
        'detail',
        'status',
        'sold_by_user_id',
        'sold_to_user_id'
    ];
}
