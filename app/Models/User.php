<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'points',
        'role',
        'track_id',
        'ip'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole($role) {
        return ($this->role === $role);
    }

    //Relationships

    public function submissions() {
        return $this->hasMany('App\Models\Submission', 'participant_id');
    }

    public function judges() {
        return $this->hasMany('App\Models\Submission', 'judge_id');
    }

    public function solves() {
        return $this->belongsToMany('App\Models\Challenge', 'solves');
    }

    public function locks() {
        return $this->belongsToMany('App\Models\Challenge', 'locks');
    }

    public function track() {
        return $this->belongsTo('App\Models\Track');
    }

    public function username() {
        return "full_name";
    }


}
