@extends('layouts.app')

@section('title', 'Edit Tasting Round')

@section('content')
<div class="p-20 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Tasting Round</h1>
            <p class="text-gray-600">Update round information and snacks</p>
        </div>
        <a href="{{ route('admin.tasting-rounds.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Rounds
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.tasting-rounds.update', $tastingRound) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Round Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tastingRound->name) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                           placeholder="e.g., Q4 2024 Product Testing">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                              placeholder="Describe the purpose of this tasting round...">{{ old('description', $tastingRound->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $tastingRound->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Activate this round
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Only one round can be active at a time</p>
                </div>

                <!-- Snacks Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Snacks for This Round</label>
                    
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <select id="category-filter" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Snacks Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                        @foreach($snacks as $snack)
                        @php
                            $roundSnack = $roundSnacks->where('snack_id', $snack->id)->first();
                            $isSelected = $roundSnack ? true : false;
                            $order = $roundSnack ? $roundSnack->sequence_order : $loop->iteration;
                        @endphp
                        <div class="snack-item border border-gray-200 rounded-lg p-3 hover:bg-gray-50 {{ $isSelected ? 'bg-blue-50 border-blue-300' : '' }}" 
                             data-category="{{ $snack->category_id }}">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" name="snacks[{{ $snack->id }}][id]" 
                                       value="{{ $snack->id }}" id="snack_{{ $snack->id }}"
                                       {{ $isSelected ? 'checked' : '' }}
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div class="flex-1">
                                    <label for="snack_{{ $snack->id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                        {{ $snack->name }}
                                    </label>
                                    <p class="text-xs text-gray-500">{{ $snack->brand }}</p>
                                    <p class="text-xs text-blue-600">{{ $snack->category->name }}</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="block text-xs text-gray-500">Order:</label>
                                <input type="number" name="snacks[{{ $snack->id }}][order]" 
                                       min="1" value="{{ old('snacks.' . $snack->id . '.order', $order) }}"
                                       class="mt-1 block w-full text-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('snacks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.tasting-rounds.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-milele-green hover:bg-milele-green hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>Update Round
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category-filter');
    const snackItems = document.querySelectorAll('.snack-item');

    categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;
        
        snackItems.forEach(item => {
            if (selectedCategory === '' || item.dataset.category === selectedCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
