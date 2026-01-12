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
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->tastingSession->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->tastingSession->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tasting Round</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.tasting-rounds.show', $review->tastingSession->tastingRound) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        {{ $review->tastingSession->tastingRound->name }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Session Status</dt>
                                <dd class="mt-1">
                                    @if($review->tastingSession->status === 'completed')
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
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->tastingSession->started_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @if($review->tastingSession->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Session Completed</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $review->tastingSession->completed_at->format('M j, Y g:i A') }}</dd>
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
                   <svg class="mr-3 w-4" fill="currentColor" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M270.797,150.851l72.873-72.873c17.839-17.839,17.839-46.761,0-64.598c-17.839-17.839-46.759-17.839-64.598,0L256,36.451 l-23.072-23.072c-17.839-17.839-46.761-17.839-64.598,0c-17.839,17.839-17.839,46.761,0,64.598l72.873,72.873 c-126.175,4.302-226.144,63.214-226.144,135.269c0,16.648,13.47,30.118,30.118,30.118c35.134,0,35.134-30.118,70.269-30.118 c35.135,0,35.135,30.118,70.271,30.118c35.137,0,35.137-30.118,70.273-30.118c35.137,0,35.137,30.118,70.272,30.118 c35.14,0,35.14-30.118,70.281-30.118c35.141,0,35.141,30.118,70.281,30.118c16.648,0,30.118-13.47,30.118-30.118 C496.942,214.063,396.972,155.152,270.797,150.851z"></path> </g> </g> <g> <g> <path d="M402.283,335.478c-1.667-1.428-3.863-3.31-5.07-4.167c-0.352-0.02-0.986-0.02-1.343,0 c-1.208,0.857-3.403,2.739-5.07,4.168c-10.574,9.062-30.256,25.933-64.538,25.933s-53.963-16.872-64.538-25.936 c-1.667-1.428-3.86-3.308-5.066-4.167c-0.354-0.02-0.985-0.02-1.336,0c-1.209,0.858-3.402,2.738-5.069,4.167 c-10.574,9.064-30.256,25.936-64.538,25.936c-34.281,0-53.963-16.872-64.536-25.936c-1.666-1.429-3.86-3.308-5.067-4.167 c-0.351-0.02-0.979-0.018-1.333,0c-1.208,0.858-3.4,2.738-5.066,4.167c-10.047,8.614-28.33,24.266-59.54,25.802l25.12,150.722 h361.412l25.12-150.722C430.616,359.741,412.332,344.09,402.283,335.478z M203.295,466.824h-45.176v-60.235h45.176V466.824z M353.883,466.824h-45.177v-60.235h45.177V466.824z"></path> </g> </g> </g></svg> 
                   View Snack
                </a>
                <a href="{{ route('admin.sessions.show', $review->tastingSession) }}" 
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
