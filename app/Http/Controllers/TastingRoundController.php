<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TastingRound;
use App\Models\RoundSnack;
use App\Models\Snack;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TastingRoundController extends Controller
{
    public function index()
    {
        $rounds = TastingRound::with(['creator', 'sessions', 'snacks'])
            ->withCount(['sessions as completed_sessions_count' => function($query) {
                $query->where('status', 'completed');
            }])
            ->latest()
            ->get();

        return view('admin.rounds.index', compact('rounds'));
    }

    public function create()
    {
        $snacks = Snack::with('category')->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();
        
        return view('admin.rounds.create', compact('snacks', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'snacks' => 'required|array|min:1',
            'snacks.*.id' => 'required|exists:snacks,id',
            'snacks.*.order' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            // Create round
            $round = TastingRound::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => auth()->id(),
                'is_active' => $request->boolean('is_active', false)
            ]);

            // Activate round if requested
            if ($round->is_active) {
                $round->activate();
            }

            // Add snacks with sequence
            foreach ($request->snacks as $snackData) {
                RoundSnack::create([
                    'tasting_round_id' => $round->id,
                    'snack_id' => $snackData['id'],
                    'sequence_order' => $snackData['order']
                ]);
            }
        });

        return redirect()->route('admin.tasting-rounds.index')
            ->with('success', 'Tasting round created successfully.');
    }

    public function show(TastingRound $tastingRound)
    {
        $tastingRound->load([
            'creator',
            'roundSnacks.snack.category',
            'sessions.user',
            'sessions.reviews'
        ]);

        $stats = [
            'total_participants' => $tastingRound->sessions->count(),
            'completed_sessions' => $tastingRound->sessions->where('status', 'completed')->count(),
            'average_rating' => $tastingRound->reviews()->avg('overall_rating'),
            'total_reviews' => $tastingRound->reviews()->count()
        ];

        return view('admin.rounds.show', compact('tastingRound', 'stats'));
    }

    public function edit(TastingRound $tastingRound)
    {
        $snacks = Snack::with('category')->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $roundSnacks = $tastingRound->roundSnacks;

        return view('admin.rounds.edit', compact('tastingRound', 'snacks', 'categories', 'roundSnacks'));
    }

    public function update(Request $request, TastingRound $tastingRound)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'snacks' => 'required|array|min:1',
            'snacks.*.id' => 'required|exists:snacks,id',
            'snacks.*.order' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request, $tastingRound) {
            // Update round
            $tastingRound->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', false)
            ]);

            // Activate round if requested
            if ($tastingRound->is_active) {
                $tastingRound->activate();
            }

            // Remove existing snacks
            $tastingRound->roundSnacks()->delete();

            // Add updated snacks with sequence
            foreach ($request->snacks as $snackData) {
                RoundSnack::create([
                    'tasting_round_id' => $tastingRound->id,
                    'snack_id' => $snackData['id'],
                    'sequence_order' => $snackData['order']
                ]);
            }
        });

        return redirect()->route('admin.tasting-rounds.index')
            ->with('success', 'Tasting round updated successfully.');
    }

    public function destroy(TastingRound $tastingRound)
    {
        DB::transaction(function () use ($tastingRound) {
            $tastingRound->roundSnacks()->delete();
            $tastingRound->sessions()->each(function ($session) {
                $session->reviews()->delete();
                $session->delete();
            });
            $tastingRound->delete();
        });

        return redirect()->route('admin.tasting-rounds.index')
            ->with('success', 'Tasting round deleted successfully.');
    }

    public function activate(TastingRound $tastingRound)
    {
        $tastingRound->activate();

        return redirect()->route('admin.tasting-rounds.index')
            ->with('success', 'Tasting round activated successfully.');
    }

    public function results(TastingRound $tastingRound)
    {
        $tastingRound->load(['roundSnacks.snack.reviews']);

        $results = $tastingRound->roundSnacks->map(function ($roundSnack) {
            $snack = $roundSnack->snack;
            $reviews = $snack->reviews->where('session.tasting_round_id', $roundSnack->tasting_round_id);

            return [
                'snack' => $snack,
                'sequence_order' => $roundSnack->sequence_order,
                'total_reviews' => $reviews->count(),
                'avg_taste' => $reviews->avg('taste_rating'),
                'avg_texture' => $reviews->avg('texture_rating'),
                'avg_appearance' => $reviews->avg('appearance_rating'),
                'avg_overall' => $reviews->avg('overall_rating'),
                'reviews' => $reviews
            ];
        });

        return view('admin.rounds.results', compact('tastingRound', 'results'));
    }
}