<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = ['participant_id', 'challenge_id', 'attachment', 'status'];

    public function participant() {
        return $this->belongsTo('App\Models\User');
    }

    public function challenge() {
        return $this->belongsTo('App\Models\Challenge');
    }
}

