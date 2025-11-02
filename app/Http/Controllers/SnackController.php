<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Snack;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;  // Add this
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
        $countries = $this->getCountries();
        return view('admin.snacks.create', compact('categories', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'origin' => 'nullable|string|max:255',
            'shelf_life' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                
                // Ensure the directory exists
                $directory = storage_path('app/public/snacks');
                if (!\File::exists($directory)) {
                    \File::makeDirectory($directory, 0755, true);
                    \Log::info('Created directory: ' . $directory);
                }
                
                // Check if directory is writable
                if (!\File::isWritable($directory)) {
                    \Log::error('Directory is not writable: ' . $directory);
                    return back()->withInput()->with('error', 'Storage directory is not writable. Please check permissions.');
                }
                
                // Store the file
                $imagePath = $file->store('snacks', 'public');
                
                if (!$imagePath) {
                    \Log::error('store() returned null or false');
                    return back()->withInput()->with('error', 'Failed to store image. Please try again.');
                }
                
                // Get the full path to verify
                $fullPath = storage_path('app/public/' . $imagePath);
                
                // Verify the file was actually written
                if (!file_exists($fullPath)) {
                    \Log::error('File not found after storage. Expected: ' . $fullPath);
                    return back()->withInput()->with('error', 'Image was uploaded but file was not saved. Please check storage permissions.');
                }
                
                \Log::info('Image stored successfully', [
                    'path' => $imagePath,
                    'full_path' => $fullPath,
                    'file_size' => filesize($fullPath),
                    'is_readable' => is_readable($fullPath)
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Image upload exception: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->with('error', 'Error uploading image: ' . $e->getMessage());
            }
        }

        try {
            $snack = Snack::create([
                'name' => $request->name,
                'brand' => $request->brand,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'ingredients' => $request->ingredients,
                'origin' => $request->origin,
                'shelf_life' => $request->shelf_life,
                'image_path' => $imagePath
            ]);
            
            \Log::info('Snack created', [
                'id' => $snack->id,
                'name' => $snack->name,
                'image_path' => $snack->image_path
            ]);

            return redirect()->route('admin.snacks.index')
                ->with('success', 'Snack created successfully.');
        } catch (\Exception $e) {
            // If snack creation fails, delete the uploaded image if it exists
            if ($imagePath) {
                try {
                    Storage::disk('public')->delete($imagePath);
                } catch (\Exception $deleteException) {
                    \Log::error('Failed to delete image after snack creation failure: ' . $deleteException->getMessage());
                }
            }
            
            \Log::error('Snack creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create snack: ' . $e->getMessage());
        }
    }

    public function edit(Snack $snack)
    {
        $categories = Category::all();
        $countries = $this->getCountries();
        return view('admin.snacks.edit', compact('snack', 'categories', 'countries'));
    }

    public function update(Request $request, Snack $snack)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'origin' => 'nullable|string|max:255',
            'shelf_life' => 'nullable|string|max:255',
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
            'ingredients' => $request->ingredients,
            'origin' => $request->origin,
            'shelf_life' => $request->shelf_life,
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

    /**
     * Get list of countries from API
     */
    private function getCountries()
    {
        // Cache countries for 24 hours to avoid API calls on every request
        return Cache::remember('countries_list', 60 * 60 * 24, function () {
            try {
                $response = Http::timeout(5)->get('https://restcountries.com/v3.1/all?fields=name,cca2');
                
                if ($response->successful()) {
                    $countries = $response->json();
                    // Sort by country name
                    usort($countries, function($a, $b) {
                        return strcmp($a['name']['common'], $b['name']['common']);
                    });
                    return $countries;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to fetch countries: ' . $e->getMessage());
            }
            
            // Fallback to a basic list if API fails
            return $this->getFallbackCountries();
        });
    }

    /**
     * Fallback countries list if API fails
     */
    private function getFallbackCountries()
    {
        return [
            ['name' => ['common' => 'United States'], 'cca2' => 'US'],
            ['name' => ['common' => 'United Kingdom'], 'cca2' => 'GB'],
            ['name' => ['common' => 'Germany'], 'cca2' => 'DE'],
            ['name' => ['common' => 'France'], 'cca2' => 'FR'],
            ['name' => ['common' => 'Italy'], 'cca2' => 'IT'],
            ['name' => ['common' => 'Spain'], 'cca2' => 'ES'],
            ['name' => ['common' => 'Canada'], 'cca2' => 'CA'],
            ['name' => ['common' => 'Australia'], 'cca2' => 'AU'],
            ['name' => ['common' => 'Japan'], 'cca2' => 'JP'],
            ['name' => ['common' => 'China'], 'cca2' => 'CN'],
            // Add more as needed
        ];
    }
}