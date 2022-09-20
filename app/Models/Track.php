<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'max_earned_points'];

    //Relationships
    public function challenges() {
        return $this->hasMany('App\Models\Challenge');
    }
}
