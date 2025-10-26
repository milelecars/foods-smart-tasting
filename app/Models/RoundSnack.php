<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoundSnack extends Model
{
    use HasFactory;

    protected $fillable = ['tasting_round_id', 'snack_id', 'sequence_order'];

    public function tastingRound()
    {
        return $this->belongsTo(TastingRound::class);
    }

    public function snack()
    {
        return $this->belongsTo(Snack::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, TastingSession::class, 'tasting_round_id', 'snack_id')
                ->where('snack_id', $this->snack_id);
    }
}