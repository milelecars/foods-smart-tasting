@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <nav class="mt-6">
            <div class="px-6 py-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Main</h3>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-tachometer-alt mr-3"></i>
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
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Rounds -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-list-ol text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Rounds</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalRounds }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Participants</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalParticipants }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Reviews -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-star text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Reviews</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalReviews }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Sessions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <i class="fas fa-check-circle text-orange-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Completed Sessions</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $completedSessions }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Round -->
                @if($activeRound)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-play-circle text-green-600 mr-2"></i>
                            Active Tasting Round
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ $activeRound->name }}</h4>
                                @if($activeRound->description)
                                    <p class="text-gray-600 mt-1">{{ $activeRound->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                    <span><i class="fas fa-cookie mr-1"></i> {{ $activeRound->roundSnacks->count() }} snacks</span>
                                    <span><i class="fas fa-user mr-1"></i> {{ $activeRound->participant_count }} participants</span>
                                    <span><i class="fas fa-star mr-1"></i> {{ number_format($activeRound->average_rating ?? 0, 1) }} avg rating</span>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.tasting-rounds.show', $activeRound->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> View
                                </a>
                                <a href="{{ route('admin.tasting-rounds.results', $activeRound->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-chart-bar mr-2"></i> Results
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- No Active Round Message -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="fas fa-pause-circle text-gray-400 mr-2"></i>
                            No Active Round
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center py-8">
                            <i class="fas fa-plus-circle text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Create Your First Tasting Round</h3>
                            <p class="text-gray-600 mb-4">Get started by creating a tasting round and adding snacks for participants to review.</p>
                            <a href="{{ route('admin.tasting-rounds.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Create Tasting Round
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Top Snacks & Recent Reviews -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Rated Snacks -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                                Top Rated Snacks
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($topSnacks->count() > 0)
                                <div class="space-y-4">
                                    @foreach($topSnacks as $review)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $review->snack->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $review->snack->brand }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= round($review->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm font-medium text-gray-900">
                                                {{ number_format($review->avg_rating, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-info-circle text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Reviews Yet</h3>
                                    <p class="text-gray-600 mb-4">Top rated snacks will appear here once participants start reviewing.</p>
                                    <p class="text-sm text-gray-500">Create a tasting round and add snacks to get started!</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Reviews -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                Recent Reviews
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($recentReviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentReviews as $review)
                                    <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm font-medium text-gray-900">{{ $review->snack->name }}</p>
                                            <div class="flex items-center">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-1">
                                            <i class="fas fa-user mr-1"></i>{{ $review->session->user->email }}
                                        </p>
                                        @if($review->comment)
                                        <p class="text-sm text-gray-700 mt-1 italic">"{{ Str::limit($review->comment, 100) }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">
                                            <i class="fas fa-clock mr-1"></i>{{ $review->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-clipboard-list text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Reviews Yet</h3>
                                    <p class="text-gray-600 mb-4">Recent reviews will appear here once participants start tasting.</p>
                                    <p class="text-sm text-gray-500">Set up a tasting round to begin collecting feedback!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('admin.tasting-rounds.create') }}" 
                               class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-plus-circle text-blue-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">New Round</p>
                                    <p class="text-sm text-gray-600">Create tasting round</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('admin.snacks.create') }}" 
                               class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <i class="fas fa-cookie text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Add Snack</p>
                                    <p class="text-sm text-gray-600">New product</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('admin.analytics') }}" 
                               class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <i class="fas fa-chart-bar text-purple-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Analytics</p>
                                    <p class="text-sm text-gray-600">View insights</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('admin.export', 'csv') }}" 
                               class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                                <i class="fas fa-download text-orange-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Export Data</p>
                                    <p class="text-sm text-gray-600">Download CSV</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection