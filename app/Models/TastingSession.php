<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TastingSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tasting_round_id', 'started_at', 'completed_at', 'status'];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tastingRound()
    {
        return $this->belongsTo(TastingRound::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'tasting_session_id');  // Specify the foreign key
    }

    public function completedReviews()
    {
        return $this->hasMany(Review::class, 'tasting_session_id');  // Specify the foreign key
    }

    public function getProgressAttribute()
    {
        try {
            $totalSnacks = $this->tastingRound->roundSnacks()->count();
            $completedReviews = $this->reviews()->count();
            
            return $totalSnacks > 0 ? ($completedReviews / $totalSnacks) * 100 : 0;
        } catch (\Exception $e) {
            return 0; // Return 0 if there's any error
        }
    }

    public function complete()
    {
        $completedAt = now();
        
        // Ensure completed_at is always after started_at
        if ($this->started_at && $completedAt->lt($this->started_at)) {
            // If for some reason now() is before started_at, use started_at + 1 minute
            $completedAt = $this->started_at->copy()->addMinute();
        }
        
        $this->update([
            'completed_at' => $completedAt,
            'status' => 'completed'
        ]);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getNextSnack()
    {
        $reviewedSnackIds = $this->reviews()->pluck('snack_id');
        
        return $this->tastingRound->roundSnacks()
            ->whereNotIn('snack_id', $reviewedSnackIds)
            ->orderBy('sequence_order')
            ->first();
    }
}