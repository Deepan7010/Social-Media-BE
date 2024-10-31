<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the default
    protected $table = 'posts';

    // Specify which attributes can be mass assigned
    protected $fillable = [
        'user_id',
        'description',
        'post',
    ];

    // If using timestamps
    public $timestamps = true;

    // Define the relationship with the User model if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
