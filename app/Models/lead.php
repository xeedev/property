<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'demand',
        'sold_by_user_id',
        'sold_to_user_id',
        'actual_commission_amount',
        'commission_received',
        'sold_in'
    ];
}
