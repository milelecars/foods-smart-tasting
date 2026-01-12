<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\TastingRound;
use App\Models\Snack;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['tastingSession.user', 'tastingSession.tastingRound', 'snack']);
        
        // Filter by round
        if ($request->has('round_id') && $request->round_id) {
            $query->whereHas('tastingSession', function($q) use ($request) {
                $q->where('tasting_round_id', $request->round_id);
            });
        }
        
        // Filter by snack
        if ($request->has('snack_id') && $request->snack_id) {
            $query->where('snack_id', $request->snack_id);
        }

        $reviews = $query->latest()->paginate(20);
        $rounds = TastingRound::all();
        $snacks = Snack::all();

        return view('admin.reviews.index', compact('reviews', 'rounds', 'snacks'));
    }

    public function show(Review $review)
    {
        $review->load(['tastingSession.user', 'tastingSession.tastingRound', 'snack.category']);
        
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Review::with(['tastingSession.user', 'tastingSession.tastingRound', 'snack']);
        
        // Apply filters
        if ($request->has('round_id') && $request->round_id) {
            $query->whereHas('tastingSession', function($q) use ($request) {
                $q->where('tasting_round_id', $request->round_id);
            });
        }
        if ($request->has('snack_id') && $request->snack_id) {
            $query->where('snack_id', $request->snack_id);
        }

        $reviews = $query->get();

        $filename = "reviews_export_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ];

        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ID', 'Round', 'Participant Email', 'Snack', 'Brand', 
                'Taste Rating', 'Texture Rating', 'Appearance Rating', 
                'Overall Rating', 'Comment', 'Created At'
            ]);

            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->tastingSession->tastingRound->name,
                    $review->tastingSession->user->email,
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
}