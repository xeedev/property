<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = [
        'imageable_type','imageable_id','url'
    ];

    public function image()
    {
        return $this->morphTo();
    }
}
