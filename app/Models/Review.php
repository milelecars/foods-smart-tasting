<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'snack_id', 'taste_rating', 'texture_rating',
        'appearance_rating', 'overall_rating', 'comment'
    ];

    protected $casts = [
        'taste_rating' => 'integer',
        'texture_rating' => 'integer',
        'appearance_rating' => 'integer',
        'overall_rating' => 'integer',
    ];

    public function session()
    {
        return $this->belongsTo(TastingSession::class, 'session_id');
    }

    public function snack()
    {
        return $this->belongsTo(Snack::class);
    }

    public function user()
    {
        return $this->session->user();
    }

    public function tastingRound()
    {
        return $this->session->tastingRound();
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
