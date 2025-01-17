<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->hasOne(employee::class);
    }

    public function works()
    {
        return $this->hasMany(works::class);
    }

    public function professionalActivities()
    {
        return $this->hasMany(professional_activities::class);
    }

    public function fundingDetails()
    {
        return $this->hasMany(funding_details::class);
    }

    public function education()
    {
        return $this->hasMany(education::class);
    }
}
