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
    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function sold_to(){
        return $this->belongsTo(User::class,'sold_to_user_id');
    }
    public function sold_by(){
        return $this->belongsTo(User::class,'sold_by_user_id');
    }
    public function media(){
        return $this->morphMany('App\Models\Media', 'imageable');
    }
}
