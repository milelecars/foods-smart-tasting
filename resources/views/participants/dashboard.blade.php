@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class=" max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome {{ $user->name }}!</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-list-ol text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sessions'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed_sessions'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['in_progress_sessions'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg Rating</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Rounds Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-list-ol mr-2"></i>Available Tasting Rounds
            </h2>
            
            @if(isset($availableRounds) && $availableRounds->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availableRounds as $round)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $round->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($round->description, 100) }}</p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <span><i class="fas fa-user mr-1"></i> {{ $round->creator->name ?? 'Unknown' }}</span>
                                <span><i class="fas fa-cookie mr-1"></i> {{ $round->roundSnacks->count() }} snacks</span>
                            </div>
                            
                            <form action="{{ route('tasting.start-session') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <input type="hidden" name="round_id" value="{{ $round->id }}">
                                <button type="submit" class="w-full bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                    <i class="fas fa-play mr-1"></i> Start Tasting
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Available Tasting Rounds</h3>
                    <p class="text-gray-600 mb-4">There are currently no active tasting rounds available for you.</p>
                    <p class="text-sm text-gray-500">Check back later or contact your administrator to set up a new tasting round.</p>
                </div>
            @endif
        </div>

        <!-- Completed Rounds Section -->
        @if(isset($completedRounds) && $completedRounds->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-check-circle mr-2"></i>Completed Tasting Rounds
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($completedRounds as $round)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 opacity-75">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $round->name }}</h3>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">
                                    <i class="fas fa-check mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($round->description, 100) }}</p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span><i class="fas fa-user mr-1"></i> {{ $round->creator->name ?? 'Unknown' }}</span>
                                <span><i class="fas fa-cookie mr-1"></i> {{ $round->roundSnacks->count() }} snacks</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
