<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'role'];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function createdRounds()
    {
        return $this->hasMany(TastingRound::class, 'created_by');
    }

    public function tastingSessions()
    {
        return $this->hasMany(TastingSession::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, TastingSession::class);
    }
}