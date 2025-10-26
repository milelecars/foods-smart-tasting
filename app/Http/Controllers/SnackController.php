<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Snack;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SnackController extends Controller
{
    public function index()
    {
        $snacks = Snack::with('category')->latest()->get();
        return view('admin.snacks.index', compact('snacks'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.snacks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('snacks', 'public');
        }

        Snack::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image_path' => $imagePath
        ]);

        return redirect()->route('admin.snacks.index')
            ->with('success', 'Snack created successfully.');
    }

    public function edit(Snack $snack)
    {
        $categories = Category::all();
        return view('admin.snacks.edit', compact('snack', 'categories'));
    }

    public function update(Request $request, Snack $snack)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $snack->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($snack->image_path) {
                Storage::disk('public')->delete($snack->image_path);
            }
            $imagePath = $request->file('image')->store('snacks', 'public');
        }

        $snack->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image_path' => $imagePath
        ]);

        return redirect()->route('admin.snacks.index')
            ->with('success', 'Snack updated successfully.');
    }

    public function destroy(Snack $snack)
    {
        // Check if snack is used in any rounds
        if ($snack->roundSnacks()->count() > 0) {
            return redirect()->route('admin.snacks.index')
                ->with('error', 'Cannot delete snack that is used in tasting rounds.');
        }

        // Delete image if exists
        if ($snack->image_path) {
            Storage::disk('public')->delete($snack->image_path);
        }

        $snack->delete();

        return redirect()->route('admin.snacks.index')
            ->with('success', 'Snack deleted successfully.');
    }

    public function show(Snack $snack)
    {
        $snack->load(['category', 'reviews.session.user', 'tastingRounds']);
        
        $stats = [
            'total_reviews' => $snack->reviews->count(),
            'avg_taste' => $snack->reviews->avg('taste_rating'),
            'avg_texture' => $snack->reviews->avg('texture_rating'),
            'avg_appearance' => $snack->reviews->avg('appearance_rating'),
            'avg_overall' => $snack->reviews->avg('overall_rating')
        ];

        return view('admin.snacks.show', compact('snack', 'stats'));
    }
}