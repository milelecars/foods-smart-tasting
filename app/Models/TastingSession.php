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
        return $this->hasMany(Review::class);
    }

    public function completedReviews()
    {
        return $this->hasMany(Review::class);
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
        $this->update([
            'completed_at' => now(),
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