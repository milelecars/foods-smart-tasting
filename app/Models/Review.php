<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'tasting_session_id', 'snack_id', 'taste_rating', 'texture_rating',
        'appearance_rating', 'overall_rating', 'comment'
    ];

    protected $casts = [
        'taste_rating' => 'integer',
        'texture_rating' => 'integer',
        'appearance_rating' => 'integer',
        'overall_rating' => 'integer',
    ];

    public function tastingSession()
    {
        return $this->belongsTo(TastingSession::class, 'tasting_session_id');
    }

    // Alias for tastingSession for convenience
    public function session()
    {
        return $this->tastingSession();
    }

    public function snack()
    {
        return $this->belongsTo(Snack::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            TastingSession::class,
            'id', // Foreign key on tasting_sessions table
            'id', // Foreign key on users table
            'tasting_session_id', // Local key on reviews table
            'user_id' // Local key on tasting_sessions table
        );
    }

    public function tastingRound()
    {
        return $this->hasOneThrough(
            TastingRound::class,
            TastingSession::class,
            'id', // Foreign key on tasting_sessions table
            'id', // Foreign key on tasting_rounds table
            'tasting_session_id', // Local key on reviews table
            'tasting_round_id' // Local key on tasting_sessions table
        );
    }

    public function getAverageAttribute()
    {
        return ($this->taste_rating + $this->texture_rating + $this->appearance_rating + $this->overall_rating) / 4;
    }

    public function getFormattedCommentAttribute()
    {
        return $this->comment ?: 'No comment provided';
    }
}
