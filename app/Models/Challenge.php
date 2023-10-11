<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
         'name',
         'author',
         'difficulty',
         'description',
         'points',
         'attachment',
         'external_resource',
         'max_tries',
         'requires_judge',
         'solution',
         'is_locked'
    ];

    //Relationships

    public function submissions() {
        return $this->hasMany('App\Models\Submission');
    }

    public function track() {
        return $this->belongsTo('App\Models\Track');
    }

    public function solvers() {
        return $this->belongsToMany('App\Models\User', 'solves');
    }

    public function locked() {
        return $this->belongsToMany('App\Models\User', 'locks');
    }
}


