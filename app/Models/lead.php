<?php

namespace App\Models;

use Carbon\Carbon;
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
    public function sold_to(){
        return $this->belongsTo(User::class,'sold_to_user_id');
    }
    public function sold_by(){
        return $this->belongsTo(User::class,'sold_by_user_id');
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('d-m-Y');
    }
}
