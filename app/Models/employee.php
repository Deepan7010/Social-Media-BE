<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
