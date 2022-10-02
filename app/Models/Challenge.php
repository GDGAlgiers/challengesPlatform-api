<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = ['track_id', 'name', 'difficulty', 'points', 'attachment'];

    //Relationships

    public function submitors()
    {
        return $this->belongsToMany('App\Models\User', 'submissions', 'challenge_id', 'user_id')->withPivot('status');
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


