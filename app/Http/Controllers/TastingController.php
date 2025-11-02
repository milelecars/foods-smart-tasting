<?php

namespace App\Http\Controllers;

use App\Models\TastingRound;
use App\Models\TastingSession;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TastingController extends Controller
{
    public function welcome()
    {
        try {
            $activeRounds = TastingRound::where('is_active', true)
                ->with(['creator', 'snacks'])
                ->get();
        } catch (\Exception $e) {
            // If there's an error loading relationships, get rounds without snacks
            $activeRounds = TastingRound::where('is_active', true)
                ->with(['creator'])
                ->get();
        }

        return view('welcome', compact('activeRounds'));
    }

    public function participantsDashboard(Request $request)
    {
        // Get user email from session or request
        $email = $request->session()->get('user_email') ?? $request->get('email');
        
        if (!$email) {
            return redirect()->route('welcome')->with('error', 'Please log in to access your dashboard.');
        }

        // Find user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('welcome')->with('error', 'User not found.');
        }

        try {
            // Get user's sessions
            $sessions = TastingSession::where('user_id', $user->id)
                ->with(['tastingRound', 'reviews'])
                ->latest()
                ->get();

            // Get active rounds
            $activeRounds = TastingRound::where('is_active', true)
                ->with(['creator', 'roundSnacks'])
                ->get();

            // Get rounds the user has already completed
            $completedRoundIds = TastingSession::where('user_id', $user->id)
                ->where('status', 'completed')
                ->pluck('tasting_round_id')
                ->toArray();

            // Separate rounds into available and completed
            $availableRounds = $activeRounds->filter(function($round) use ($completedRoundIds) {
                return !in_array($round->id, $completedRoundIds);
            });

            $completedRounds = $activeRounds->filter(function($round) use ($completedRoundIds) {
                return in_array($round->id, $completedRoundIds);
            });

            // Calculate stats
            $stats = [
                'total_sessions' => $sessions->count(),
                'completed_sessions' => $sessions->where('status', 'completed')->count(),
                'in_progress_sessions' => $sessions->where('status', 'in_progress')->count(),
                'total_reviews' => $sessions->sum(function($session) {
                    return $session->reviews->count();
                }),
                'average_rating' => $sessions->flatMap->reviews->avg('overall_rating')
            ];

            return view('participants.dashboard', compact('user', 'sessions', 'availableRounds', 'completedRounds', 'stats'));
            
        } catch (\Exception $e) {
            // Log the error and redirect with a generic message
            \Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->route('welcome')->with('error', 'There was an error loading your dashboard. Please try again.');
        }
    }

    public function startTastingSession(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'round_id' => 'required|exists:tasting_rounds,id'
        ]);

        // Check if email is from milele.com domain
        if (!str_ends_with(strtolower($request->email), '@milele.com')) {
            return back()->with('error', 'Please use your @milele.com company email address.');
        }

        $round = TastingRound::findOrFail($request->round_id);
        
        if (!$round->is_active) {
            return back()->with('error', 'This tasting round is not currently active.');
        }

        // Check if user exists in database (authorized user)
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'You are not authorized to access the tasting portal. Please contact administrator.');
        }

        // Check for existing active session
        $existingSession = TastingSession::where('user_id', $user->id)
            ->where('tasting_round_id', $round->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingSession) {
            return redirect()->route('tasting.session', $existingSession->id);
        }

        // Check for completed session
        $completedSession = TastingSession::where('user_id', $user->id)
            ->where('tasting_round_id', $round->id)
            ->where('status', 'completed')
            ->first();

        if ($completedSession) {
            return back()->with('error', 'You have already completed this tasting round.');
        }

        // Create new session
        $session = TastingSession::create([
            'user_id' => $user->id,
            'tasting_round_id' => $round->id,
            'started_at' => now(),
            'status' => 'in_progress'
        ]);

        return redirect()->route('tasting.session', $session->id);
    }

    public function startSession(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'round_id' => 'nullable|integer' // Make round_id optional
        ]);

        // Check if email is from milele.com domain
        if (!str_ends_with(strtolower($request->email), '@milele.com')) {
            return back()->with('error', 'Please use your @milele.com company email address.');
        }

        // Check if user exists in database (authorized user)
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'You are not authorized to access the tasting portal. Please contact administrator.');
        }

        // Store user email in session for dashboard access
        $request->session()->put('user_email', $user->email);

        // Route based on user role
        if ($user->isAdmin()) {
            // Admin users go to admin dashboard
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        } else {
            // Participant users go to participants dashboard
            // Handle case when no round_id is provided (no active rounds)
            if (!$request->round_id) {
                return redirect()->route('participants.dashboard')
                    ->with('info', 'Welcome! No active tasting rounds are currently available. Check back later or contact your administrator.');
            }

            $round = TastingRound::findOrFail($request->round_id);
            
            if (!$round->is_active) {
                return back()->with('error', 'This tasting round is not currently active.');
            }

            return redirect()->route('participants.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }
    }

    public function showSession($sessionId)
    {
        $session = TastingSession::with([
            'tastingRound.roundSnacks.snack.category', 
            'reviews'
        ])->findOrFail($sessionId);

        // Check if session is completed
        if ($session->isCompleted()) {
            return redirect()->route('tasting.complete', $session->id);
        }

        $snacks = $session->tastingRound->roundSnacks;
        $completedReviews = $session->reviews->pluck('snack_id')->toArray();

        $currentSnack = $snacks->first(function ($roundSnack) use ($completedReviews) {
            return !in_array($roundSnack->snack_id, $completedReviews);
        });

        if (!$currentSnack) {
            // All snacks reviewed, complete session
            $session->complete();
            return redirect()->route('tasting.complete', $session->id);
        }

        $progress = [
            'current' => $currentSnack->sequence_order,
            'total' => $snacks->count(),
            'percentage' => (($currentSnack->sequence_order - 1) / $snacks->count()) * 100
        ];

        return view('tasting.session', compact('session', 'currentSnack', 'progress'));
    }

    public function submitReview(Request $request, $sessionId)
    {
        $request->validate([
            'snack_id' => 'required|exists:snacks,id',
            'taste_rating' => 'required|integer|between:1,5',
            'texture_rating' => 'required|integer|between:1,5',
            'appearance_rating' => 'required|integer|between:1,5',
            'overall_rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);

        $session = TastingSession::findOrFail($sessionId);

        // Check if review already exists for this snack in this session
        $existingReview = Review::where('session_id', $session->id)
            ->where('snack_id', $request->snack_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this snack.');
        }

        Review::create([
            'session_id' => $session->id,
            'snack_id' => $request->snack_id,
            'taste_rating' => $request->taste_rating,
            'texture_rating' => $request->texture_rating,
            'appearance_rating' => $request->appearance_rating,
            'overall_rating' => $request->overall_rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('tasting.session', $session->id);
    }

    public function complete($sessionId)
    {
        $session = TastingSession::with(['tastingRound', 'reviews.snack'])->findOrFail($sessionId);
        
        // Ensure session is marked as completed
        if (!$session->isCompleted()) {
            $session->complete();
        }

        $stats = [
            'total_snacks' => $session->tastingRound->roundSnacks->count(),
            'completed_reviews' => $session->reviews->count(),
            'average_rating' => $session->reviews->avg('overall_rating')
        ];

        return view('tasting.complete', compact('session', 'stats'));
    }

    public function sessionProgress($sessionId)
    {
        $session = TastingSession::with(['tastingRound.roundSnacks.snack', 'reviews'])->findOrFail($sessionId);
        
        $progress = [
            'completed' => $session->reviews->count(),
            'total' => $session->tastingRound->roundSnacks->count(),
            'percentage' => $session->progress
        ];

        return response()->json($progress);
    }
}