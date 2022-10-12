<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'is_locked', 'description'];

    //Relationships
    public function challenges() {
        return $this->hasMany('App\Models\Challenge');
    }

    public function participants() {
        return $this->hasMany('App\Models\User');
    }
}

