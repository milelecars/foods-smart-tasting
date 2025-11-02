@extends('layouts.app')

@section('title', 'Edit Snack')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Snack</h1>
            <p class="text-gray-600">Update snack information</p>
        </div>
        <a href="{{ route('admin.snacks.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Snacks
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.snacks.update', $snack) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Snack Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $snack->name) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $snack->brand) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('brand') border-red-300 @enderror">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-300 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $snack->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                              placeholder="Describe this snack...">{{ old('description', $snack->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ingredients -->
                <div>
                    <label for="ingredients" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-list-ul mr-1"></i>Ingredients
                    </label>
                    <textarea name="ingredients" id="ingredients" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('ingredients') border-red-300 @enderror"
                              placeholder="List ingredients (e.g., Wheat flour, Sugar, Cocoa powder...)">{{ old('ingredients', $snack->ingredients) }}</textarea>
                    @error('ingredients')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">List all ingredients separated by commas or on new lines</p>
                </div>

                <!-- Origin -->
                <div>
                    <label for="origin" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-globe mr-1"></i>Origin
                    </label>
                    <select name="origin" id="origin" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('origin') border-red-300 @enderror">
                        <option value="">Select a country (optional)</option>
                        @foreach($countries as $country)
                            <option value="{{ $country['name']['common'] }}" {{ old('origin', $snack->origin) == $country['name']['common'] ? 'selected' : '' }}>
                                {{ $country['name']['common'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('origin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Select the country of origin for this snack</p>
                </div>

                <!-- Shelf Life -->
                <div>
                    <label for="shelf_life" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-clock mr-1"></i>Shelf Life
                    </label>
                    <input type="text" name="shelf_life" id="shelf_life" value="{{ old('shelf_life', $snack->shelf_life) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('shelf_life') border-red-300 @enderror"
                           placeholder="e.g., 6 months, 12 months, Best before: 12/2025">
                    @error('shelf_life')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($snack->image_path)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Image</label>
                    <div class="mt-1">
                        <img src="{{ Storage::url($snack->image_path) }}" alt="{{ $snack->name }}" 
                             class="h-32 w-32 object-cover rounded-lg">
                    </div>
                </div>
                @endif

                <!-- New Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">
                        {{ $snack->image_path ? 'Replace Image' : 'Add Image' }}
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Upload a new image for this snack (optional)</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.snacks.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-milele-green hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>Update Snack
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
