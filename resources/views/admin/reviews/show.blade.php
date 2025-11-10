@extends('layouts.app')

@section('title', 'Review Details')

@section('content')
<div class="p-20 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Review Details</h1>
            <p class="text-gray-600">Detailed review information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.reviews.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reviews
            </a>
        </div>
    </div>

    <!-- Review Info -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Review Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="p-20 space-y-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Snack Details</h4>
                        <div class="flex items-start space-x-4">
                            @if($review->snack->image_path)
                                <img src="{{ Storage::url($review->snack->image_path) }}" 
                                     alt="{{ $review->snack->name }}" 
                                     class="h-24 w-24 object-cover rounded-lg">
                            @else
                                <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-cookie text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h5 class="text-lg font-semibold text-gray-900">{{ $review->snack->name }}</h5>
                                <p class="text-sm text-gray-600">{{ $review->snack->brand }}</p>
                                <p class="text-sm text-blue-600">{{ $review->snack->category->name }}</p>
                                @if($review->snack->description)
                                    <p class="text-sm text-gray-500 mt-2">{{ $review->snack->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Participant Information</h4>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->session->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->session->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tasting Round</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.tasting-rounds.show', $review->session->tastingRound) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        {{ $review->session->tastingRound->name }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Session Status</dt>
                                <dd class="mt-1">
                                    @if($review->session->status === 'completed')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Completed
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>In Progress
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="p-20 space-y-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Ratings</h4>
                        <div class="space-y-4">
                            <!-- Taste Rating -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-heart text-red-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Taste</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->taste_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $review->taste_rating }}/5</span>
                                </div>
                            </div>

                            <!-- Texture Rating -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-hand-paper text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Texture</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->texture_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $review->texture_rating }}/5</span>
                                </div>
                            </div>

                            <!-- Appearance Rating -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-eye text-green-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Appearance</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->appearance_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $review->appearance_rating }}/5</span>
                                </div>
                            </div>

                            <!-- Overall Rating -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Overall</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">{{ $review->overall_rating }}/5</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Comment</h4>
                        @if($review->comment)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 italic">"{{ $review->comment }}"</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No comment provided</p>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Review Details</h4>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Session Started</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->session->started_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @if($review->session->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Session Completed</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->session->completed_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                <p class="text-sm text-gray-600">Manage this review</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.snacks.show', $review->snack) }}" 
                   class="inline-flex items-center px-4 py-2 bg-milele-green text-white rounded-lg hover:bg-milele-green hover:opacity-95 transition-colors">
                    <i class="fas fa-cookie mr-2"></i>View Snack
                </a>
                <a href="{{ route('admin.sessions.show', $review->session) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-users mr-2"></i>View Session
                </a>
                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            data-confirm-delete>
                        <i class="fas fa-trash mr-2"></i>Delete Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
