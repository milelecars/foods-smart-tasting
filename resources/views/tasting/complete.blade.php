@extends('layouts.app')

@section('title', 'Session Complete - Smart Tasting Portal')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <!-- Success Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-green-600 text-5xl"></i>
            </div>
        </div>

        <!-- Thank You Message -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You!</h1>
            <p class="text-xl text-gray-600 mb-6">
                You've successfully completed the 
                <span class="font-semibold text-blue-600">{{ $session->tastingRound->name }}</span> 
                tasting session.
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ $stats['completed_reviews'] }}</div>
                    <div class="text-sm text-blue-700">Snacks Reviewed</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600 mb-1">
                        {{ number_format($stats['average_rating'], 1) }}/5
                    </div>
                    <div class="text-sm text-green-700">Average Rating</div>
                </div>
            </div>

            <!-- Session Details -->
            <div class="border-t border-gray-200 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left text-sm text-gray-600">
                    <div>
                        <i class="fas fa-user mr-2"></i>
                        <strong>Participant:</strong> {{ $session->user->email }}
                    </div>
                    <div>
                        <i class="fas fa-clock mr-2"></i>
                        <strong>Completed:</strong> {{ $session->completed_at->format('M j, Y g:i A') }}
                    </div>
                    <div>
                        <i class="fas fa-play-circle mr-2"></i>
                        <strong>Started:</strong> {{ $session->started_at->format('M j, Y g:i A') }}
                    </div>
                    <div>
                        <i class="fas fa-hourglass-half mr-2"></i>
                        <strong>Duration:</strong> 
                        {{ $session->started_at->diff($session->completed_at)->format('%hh %im') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Feedback Matters</h2>
            <div class="grid gap-6 md:grid-cols-3 text-left">
                <div class="flex items-start">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Drive Innovation</h3>
                        <p class="text-gray-600 text-sm">Your ratings help product teams improve and innovate.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-orange-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-users text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Influence Decisions</h3>
                        <p class="text-gray-600 text-sm">Your preferences guide future product development.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-cookie text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Discover Favorites</h3>
                        <p class="text-gray-600 text-sm">Help identify the next winning snack flavors.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center w-full md:w-auto bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Return to Homepage
            </a>
            
            @if($session->tastingRound->is_active)
            <form action="{{ route('tasting.start') }}" method="POST" class="inline-block w-full md:w-auto">
                @csrf
                <input type="hidden" name="email" value="{{ $session->user->email }}">
                <input type="hidden" name="round_id" value="{{ $session->tasting_round_id }}">
                <button type="submit" 
                        class="inline-flex items-center justify-center w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Start New Session (Same Round)
                </button>
            </form>
            @endif
        </div>

        <!-- Support -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>Have questions or feedback about the tasting experience?</p>
            <p>Contact the tasting coordinator for assistance.</p>
        </div>
    </div>
</div>
@endsection