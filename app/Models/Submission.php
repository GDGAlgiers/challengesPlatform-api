<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = ['participant_id', 'challenge_id', 'track_id', 'attachment', 'status', 'assigned_points'];

    public function hasStatus($status) {
        return ($this->status === $status);
    }

    public function participant() {
        return $this->belongsTo('App\Models\User');
    }

    public function judge() {
        return $this->belongsTo('App\Models\User');
    }

    public function challenge() {
        return $this->belongsTo('App\Models\Challenge');
    }

    public function track() {
        return $this->belongsTo('App\Models\Track');
    }
}

