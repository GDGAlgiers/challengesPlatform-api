<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'token'];
    //Relationship

    public function participants()
    {
        return $this->hasMany('App\Models\User');
    }
}

