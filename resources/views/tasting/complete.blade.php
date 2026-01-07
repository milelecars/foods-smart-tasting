@extends('layouts.app')

@section('title', 'Session Complete - Smart Tasting Portal')

@section('content')
<div class="">
    <div class="flex flex-col items-center justify-center gap-10 min-h-screen py-12 max-w-2xl mx-auto px-4 text-center">
        <!-- Success Icon -->
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-green-600 text-5xl"></i>
        </div>


        <!-- Thank You Message -->
        <div class="flex flex-col justify-center gap-6 bg-white rounded-xl shadow-lg p-8 mb-8">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-md text-gray-600">
                    <div class="text-center md:text-left">
                        <div class="mb-1">
                            <i class="fas fa-user mr-2"></i>
                            <strong>Participant</strong>
                        </div>
                        <div>{{ $session->user->email }}</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="mb-1">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            <strong>Duration</strong>
                        </div>
                        <div>
                            @if($session->completed_at && $session->started_at)
                                @php
                                    // Ensure completed_at is after started_at for calculation
                                    $completed = $session->completed_at;
                                    $started = $session->started_at;
                                    
                                    // If completed is before started, use started time + 1 minute as fallback
                                    if ($completed->lt($started)) {
                                        $completed = $started->copy()->addMinute();
                                    }
                                    
                                    $duration = $started->diff($completed);
                                    $hours = $duration->h;
                                    $minutes = $duration->i;
                                @endphp
                                @if($hours > 0)
                                    {{ $hours }}h {{ $minutes }}m
                                @else
                                    {{ $minutes }}m
                                @endif
                            @else
                                In Progress
                            @endif
                        </div>
                    </div>
                    <!-- Show Started FIRST (chronologically before Completed) -->
                    <div class="text-center md:text-left">
                        <div class="mb-1">
                            <i class="fas fa-play-circle mr-2"></i>
                            <strong>Started</strong>
                        </div>
                        <div class="text-gray-900">{{ $session->started_at->format('M j, Y g:i A') }}</div>
                    </div>
                    <!-- Show Completed SECOND (chronologically after Started) -->
                    <div class="text-center md:text-left">
                        <div class="mb-1">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Completed</strong>
                        </div>
                        <div class="text-gray-900">
                            @if($session->completed_at)
                                @php
                                    // Ensure displayed completed time is after started time
                                    $completed = $session->completed_at;
                                    if ($session->started_at && $completed->lt($session->started_at)) {
                                        // If completed is before started, show started + 1 minute or current time
                                        $completed = $session->started_at->copy()->addMinute();
                                    }
                                @endphp
                                {{ $completed->format('M j, Y g:i A') }}
                            @else
                                <span class="text-gray-500">Not yet completed</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="{{ url('/dashboard') }}" 
               class="inline-flex items-center justify-center w-full md:w-auto bg-milele-green text-white py-3 px-6 rounded-lg font-semibold hover:bg-milele-green hover:opacity-95 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Return to Homepage
            </a>
            
            <!-- @if($session->tastingRound->is_active)
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
            @endif -->
        </div>

    </div>
</div>
@endsection