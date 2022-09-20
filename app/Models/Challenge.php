<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = ['track_id', 'name', 'difficulty', 'points', 'attachment'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function track() {
        return $this->belongsTo('App\Models\Track');
    }

}


