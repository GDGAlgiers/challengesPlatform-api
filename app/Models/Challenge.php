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
         'difficulty',
         'description',
         'points',
         'attachment',
         'max_tries',
         'requires_judge',
         'solution'
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


