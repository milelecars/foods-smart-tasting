<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TastingSession;
use App\Models\TastingRound;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $query = TastingSession::with(['user', 'tastingRound', 'reviews']);
        
        // Filter by round
        if ($request->has('round_id') && $request->round_id) {
            $query->where('tasting_round_id', $request->round_id);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $sessions = $query->latest()->paginate(20);
        $rounds = TastingRound::all();

        return view('admin.sessions.index', compact('sessions', 'rounds'));
    }

    public function show(Session $session)
    {
        $session->load([
            'user', 
            'tastingRound.roundSnacks.snack', 
            'reviews.snack'
        ]);

        $stats = [
            'total_snacks' => $session->tastingRound->roundSnacks->count(),
            'completed_reviews' => $session->reviews->count(),
            'progress_percentage' => $session->progress,
            'average_rating' => $session->reviews->avg('overall_rating')
        ];

        return view('admin.sessions.show', compact('session', 'stats'));
    }

    public function destroy(Session $session)
    {
        // Delete associated reviews
        $session->reviews()->delete();
        $session->delete();

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session deleted successfully.');
    }

    public function forceComplete(Session $session)
    {
        if (!$session->isCompleted()) {
            $session->complete();
        }

        return redirect()->route('admin.sessions.show', $session->id)
            ->with('success', 'Session marked as completed.');
    }
}