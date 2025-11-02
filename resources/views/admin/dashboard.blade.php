@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar - Unchanged -->
    <div class="w-64 bg-white shadow-lg">
        <nav class="mt-6">
            <div class="px-6 py-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Main</h3>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 bg-blue-50 border-r-2 border-blue-600">
                <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.tasting-rounds.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-list-ol mr-3"></i>
                Tasting Rounds
            </a>
            <a href="{{ route('admin.snacks.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-cookie mr-3"></i>
                Snacks
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-tags mr-3"></i>
                Categories
            </a>
            <a href="{{ route('admin.participants') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-users mr-3"></i>
                Participants
            </a>
            <a href="{{ route('admin.sessions.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-clock mr-3"></i>
                Sessions
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-star mr-3"></i>
                Reviews
            </a>
            
            <div class="px-6 py-2 mt-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</h3>
            </div>
            <a href="{{ route('admin.analytics') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-chart-bar mr-3"></i>
                Analytics
            </a>
            <a href="{{ route('admin.export', 'csv') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-download mr-3"></i>
                Export Data
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        
        <!-- Page Content -->
        <main class="flex-1 p-6">
            <div class="space-y-6">
                <!-- Header Section -->
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                        <p class="text-gray-600 mt-1">Real-time insights and key metrics</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Last updated: {{ now()->format('M j, Y g:i A') }}
                    </div>
                </div>

                <!-- Key Metrics Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Active Rounds -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Rounds</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $activeRoundsCount ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Currently running</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-list-ol text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Participants -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Participants</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalParticipants }}</p>
                                <p class="text-xs text-gray-500 mt-1">All time</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Reviews -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Reviews</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalReviews }}</p>
                                <p class="text-xs text-gray-500 mt-1">Feedback collected</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-star text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0 }}%
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $completedSessions }}/{{ $totalSessions }} sessions</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <i class="fas fa-check-circle text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Round Focus -->
                <div class="lg:col-span-2">
                    @if($activeRound)
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-list-ol mr-3"></i>
                                Current Active Round
                            </h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                Live
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $activeRound->name }}</h4>
                                    @if($activeRound->description)
                                        <p class="text-gray-600 mb-4">{{ $activeRound->description }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-cookie mr-2 text-blue-500"></i>
                                            <span>{{ $activeRound->roundSnacks->count() }} snacks</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-user mr-2 text-green-500"></i>
                                            <span>{{ $activeRound->participant_count }} participants</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                                            <span>{{ number_format($activeRound->average_rating ?? 0, 1) }} avg rating</span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-clock mr-2 text-purple-500"></i>
                                            <span>{{ $activeRound->tastingSessions->where('status', 'in_progress')->count() }} active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.tasting-rounds.show', $activeRound->id) }}" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-milele-green text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> View Details
                                </a>
                                <a href="{{ route('admin.tasting-rounds.results', $activeRound->id) }}" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-chart-bar mr-2"></i> View Results
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- No Active Round -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-list-ol text-blue-600 mr-2"></i>
                                No Active Round
                            </h3>
                        </div>
                        <div class="p-6 text-center">
                            <i class="fas fa-plus-circle text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Ready to Start?</h3>
                            <p class="text-gray-600 mb-4">Create a tasting round to begin collecting feedback from participants.</p>
                            <a href="{{ route('admin.tasting-rounds.create') }}" 
                                class="inline-flex items-center px-6 py-3 bg-milele-green text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Create Tasting Round
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Detailed Insights Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Rated Snacks -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                                Top Rated Snacks
                            </h3>
                            <a href="{{ route('admin.reviews.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                View All →
                            </a>
                        </div>
                        <div class="p-6">
                            @if($topSnacks->count() > 0)
                                <div class="space-y-3">
                                    @foreach($topSnacks->take(5) as $index => $review)
                                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                        <div class="flex items-center flex-1">
                                            <span class="w-6 h-6 bg-gray-100 text-gray-600 rounded-full text-xs flex items-center justify-center mr-3">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $review->snack->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $review->snack->brand }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400 mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= round($review->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                                @endfor
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 w-12 text-right">
                                                {{ number_format($review->avg_rating, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-chart-line text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500">No rating data available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Reviews -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-history text-blue-600 mr-2"></i>
                                Recent Reviews
                            </h3>
                            <a href="{{ route('admin.reviews.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                View All →
                            </a>
                        </div>
                        <div class="p-6">
                            @if($recentReviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentReviews->take(4) as $review)
                                    <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start mb-1">
                                            <p class="text-sm font-medium text-gray-900 flex-1">{{ $review->snack->name }}</p>
                                            <div class="flex items-center ml-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-1">
                                            <i class="fas fa-user mr-1"></i>{{ Str::limit($review->session->user->email, 20) }}
                                        </p>
                                        @if($review->comment)
                                        <p class="text-xs text-gray-700 mt-1 italic">"{{ Str::limit($review->comment, 60) }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>{{ $review->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500">No recent reviews</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection