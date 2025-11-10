<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TastingRound extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active', 'created_by'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function roundSnacks()
    {
        return $this->hasMany(RoundSnack::class)->orderBy('sequence_order');
    }

    public function snacks()
    {
        return $this->belongsToMany(Snack::class, 'round_snacks')
                    ->withPivot('sequence_order')
                    ->orderBy('sequence_order');
    }

    public function tastingSessions()
    {
        return $this->hasMany(TastingSession::class);
    }
    
    public function reviews()
    {
        return $this->hasManyThrough(
            Review::class,
            TastingSession::class,
            'tasting_round_id', 
            'tasting_session_id',     
            'id',                
            'id'                 
        );
    }

    public function getParticipantCountAttribute()
    {
        return $this->tastingSessions()->where('status', 'completed')->count() ?? 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('overall_rating') ?? 0;
    }

    public function activate()
    {
        // Deactivate all other rounds
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this round
        $this->update(['is_active' => true]);
    }
}