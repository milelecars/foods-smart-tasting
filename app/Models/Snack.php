<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snack extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'brand', 'description', 'image_path'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function roundSnacks()
    {
        return $this->hasMany(RoundSnack::class);
    }

    public function tastingRounds()
    {
        return $this->belongsToMany(TastingRound::class, 'round_snacks')
                    ->withPivot('sequence_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('overall_rating');
    }

    public function getAverageTasteRatingAttribute()
    {
        return $this->reviews()->avg('taste_rating');
    }

    public function getAverageTextureRatingAttribute()
    {
        return $this->reviews()->avg('texture_rating');
    }

    public function getAverageAppearanceRatingAttribute()
    {
        return $this->reviews()->avg('appearance_rating');
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}