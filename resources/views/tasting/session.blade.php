@extends('layouts.app')

@section('title', 'Tasting Session')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700">
                    Snack {{ $progress['current'] }} of {{ $progress['total'] }}
                </span>
                <span class="text-sm font-medium text-gray-700">
                    {{ round($progress['percentage']) }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-milele-green h-3 rounded-full transition-all duration-300" 
                     style="width: {{ $progress['percentage'] }}%"></div>
            </div>
        </div>

        <!-- Snack Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            @if($currentSnack->snack->image_path)
            <div class="h-48 bg-gray-200">
                <img src="{{ asset('storage/' . $currentSnack->snack->image_path) }}" 
                     alt="{{ $currentSnack->snack->name }}" 
                     class="w-full h-full object-cover">
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">
                            {{ $currentSnack->snack->name }}
                        </h2>
                        <p class="text-lg text-gray-600 mb-2">{{ $currentSnack->snack->brand }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        #{{ $currentSnack->sequence_order }}
                    </span>
                </div>

                @if($currentSnack->snack->description)
                <p class="text-gray-700 mb-6">{{ $currentSnack->snack->description }}</p>
                @endif

                <form action="{{ route('tasting.submit-review', $session->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="snack_id" value="{{ $currentSnack->snack_id }}">

                    <!-- Rating Fields -->
                    <div class="p-20 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Taste Rating</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="relative">
                                    <input type="radio" name="taste_rating" value="{{ $i }}" 
                                           class="sr-only" required>
                                    <div class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-blue-500 hover:bg-blue-50 rating-option">
                                        <span class="text-lg font-medium text-gray-600">{{ $i }}</span>
                                    </div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Texture Rating</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="relative">
                                    <input type="radio" name="texture_rating" value="{{ $i }}" 
                                           class="sr-only" required>
                                    <div class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-blue-500 hover:bg-blue-50 rating-option">
                                        <span class="text-lg font-medium text-gray-600">{{ $i }}</span>
                                    </div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Appearance Rating</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="relative">
                                    <input type="radio" name="appearance_rating" value="{{ $i }}" 
                                           class="sr-only" required>
                                    <div class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-blue-500 hover:bg-blue-50 rating-option">
                                        <span class="text-lg font-medium text-gray-600">{{ $i }}</span>
                                    </div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Overall Rating</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="relative">
                                    <input type="radio" name="overall_rating" value="{{ $i }}" 
                                           class="sr-only" required>
                                    <div class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-blue-500 hover:bg-blue-50 rating-option">
                                        <span class="text-lg font-medium text-gray-600">{{ $i }}</span>
                                    </div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Comments (Optional)
                            </label>
                            <textarea name="comment" id="comment" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Share your thoughts about this snack..."></textarea>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" 
                                class="w-full bg-milele-green text-white py-3 px-4 rounded-lg font-medium hover:bg-milele-green hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Submit Review & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle each rating type separately (taste_rating, texture_rating, etc.)
    const ratingGroups = ['taste_rating', 'texture_rating', 'appearance_rating', 'overall_rating'];
    
    ratingGroups.forEach(groupName => {
        const radios = document.querySelectorAll(`input[type="radio"][name="${groupName}"]`);
        
        radios.forEach(radio => {
            const label = radio.closest('label');
            const ratingOption = label.querySelector('.rating-option');
            
            // When radio changes
            radio.addEventListener('change', function() {
                // Reset all options in this group
                radios.forEach(r => {
                    const rLabel = r.closest('label');
                    const rOption = rLabel.querySelector('.rating-option');
                    rOption.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
                    rOption.classList.add('border-gray-300', 'text-gray-600');
                });
                
                // Highlight the selected one
                if (this.checked) {
                    ratingOption.classList.remove('border-gray-300', 'text-gray-600');
                    ratingOption.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700');
                }
            });
            
            // Make the div clickable to select the radio
            ratingOption.addEventListener('click', function(e) {
                e.preventDefault();
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    });
});
</script>
@endsection