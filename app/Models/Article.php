<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'paper_title',
        'abstract',
        'publication_name',
        'year',
        'doi',
        'authors',
        'research_interest',
        'section',
        'link',
        'article', // Assuming 'article' is the file path
    ];
}
