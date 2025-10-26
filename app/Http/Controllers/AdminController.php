<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TastingRound;
use App\Models\Review;
use App\Models\TastingSession;
use App\Models\Snack;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $activeRound = TastingRound::where('is_active', true)->first();
        $totalRounds = TastingRound::count();
        $totalReviews = Review::count();
        $totalParticipants = User::where('role', 'participant')->count();
        $completedSessions = TastingSession::where('status', 'completed')->count();

        // Handle top snacks - only load if reviews exist
        $topSnacks = collect();
        if ($totalReviews > 0) {
            $topSnacks = Review::select('snack_id', DB::raw('AVG(overall_rating) as avg_rating'))
                ->groupBy('snack_id')
                ->with('snack')
                ->orderByDesc('avg_rating')
                ->limit(5)
                ->get();
        }

        // Handle recent reviews - only load if reviews exist
        $recentReviews = collect();
        if ($totalReviews > 0) {
            $recentReviews = Review::with(['snack', 'session.user'])
                ->latest()
                ->limit(10)
                ->get();
        }

        $roundStats = TastingRound::withCount(['tastingSessions as completed_sessions' => function($query) {
            $query->where('status', 'completed');
        }])->get();

        return view('admin.dashboard', compact(
            'activeRound', 'totalRounds', 'totalReviews', 
            'totalParticipants', 'completedSessions', 'topSnacks', 
            'recentReviews', 'roundStats'
        ));
    }

    public function analytics()
    {
        $roundStats = TastingRound::withCount(['tastingSessions as completed_sessions' => function($query) {
            $query->where('status', 'completed');
        }])->get();

        $ratingDistribution = Review::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('FLOOR(overall_rating) as rating')
        )
        ->groupBy('rating')
        ->orderBy('rating')
        ->get();

        $categoryRatings = Review::join('snacks', 'reviews.snack_id', '=', 'snacks.id')
            ->join('categories', 'snacks.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('AVG(reviews.overall_rating) as avg_rating'),
                DB::raw('COUNT(*) as review_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $snackPerformance = Snack::withCount('reviews')
            ->withAvg('reviews as avg_taste', 'taste_rating')
            ->withAvg('reviews as avg_texture', 'texture_rating')
            ->withAvg('reviews as avg_appearance', 'appearance_rating')
            ->withAvg('reviews as avg_overall', 'overall_rating')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('avg_overall')
            ->limit(10)
            ->get();

        $monthlyReviews = Review::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
    
        $ratingBreakdown = Review::select(
            'taste_rating',
            'texture_rating', 
            'appearance_rating',
            'overall_rating'
        )->get();
    
        return view('admin.analytics', compact(
            'roundStats', 'ratingDistribution', 'categoryRatings', 
            'snackPerformance', 'monthlyReviews', 'ratingBreakdown'
        ));
    }

    public function exportData($type)
    {
        $filename = "tasting_data_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ];

        $reviews = Review::with(['snack', 'session.user', 'session.tastingRound'])
            ->get();

        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Round', 'Participant Email', 'Snack', 'Brand', 'Taste', 'Texture', 
                'Appearance', 'Overall', 'Comments', 'Date'
            ]);

            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->session->tastingRound->name,
                    $review->session->user->email,
                    $review->snack->name,
                    $review->snack->brand,
                    $review->taste_rating,
                    $review->texture_rating,
                    $review->appearance_rating,
                    $review->overall_rating,
                    $review->comment,
                    $review->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function participants()
    {
        $participants = User::where('role', 'participant')
            ->withCount(['tastingSessions as completed_sessions' => function($query) {
                $query->where('status', 'completed');
            }])
            ->with(['tastingSessions' => function($query) {
                $query->with('tastingRound');
            }])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.participants', compact('participants'));
    }
}