@extends('layouts.admin')

@section('title', 'Session Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Session Details</h1>
            <p class="text-gray-600">Detailed session information and reviews</p>
        </div>
        <div class="flex space-x-3">
            @if($session->status !== 'completed')
                <form action="{{ route('admin.sessions.force-complete', $session) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                            data-confirm-complete>
                        <i class="fas fa-check mr-2"></i>Mark Complete
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.sessions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Sessions
            </a>
        </div>
    </div>

    <!-- Session Info -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Session Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Participant Details</h4>
                        <div class="flex items-center space-x-4">
                            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="text-lg font-semibold text-gray-900">{{ $session->user->name }}</h5>
                                <p class="text-sm text-gray-600">{{ $session->user->email }}</p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($session->user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Tasting Round</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-900">{{ $session->tastingRound->name }}</h5>
                            @if($session->tastingRound->description)
                                <p class="text-sm text-gray-600 mt-2">{{ $session->tastingRound->description }}</p>
                            @endif
                            <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                <span><i class="fas fa-cookie mr-1"></i> {{ $session->tastingRound->roundSnacks->count() }} snacks</span>
                                @if($session->tastingRound->is_active)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-play mr-1"></i>Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Session Status</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Status</span>
                                @if($session->status === 'completed')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Completed
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>In Progress
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Progress</span>
                                <span class="text-sm text-gray-900">{{ $stats['completed_reviews'] }}/{{ $stats['total_snacks'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $stats['progress_percentage'] }}%"></div>
                            </div>
                            @if($stats['average_rating'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Average Rating</span>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($stats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium">{{ number_format($stats['average_rating'], 1) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Timeline</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Started</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $session->started_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @if($session->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Completed</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $session->completed_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $session->started_at->diffForHumans($session->completed_at, true) }}</dd>
                            </div>
                            @else
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $session->started_at->diffForHumans() }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Reviews</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Snack</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taste</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Texture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appearance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($session->reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $review->snack->name }}</div>
                            <div class="text-sm text-gray-500">{{ $review->snack->brand }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                                $roundSnack = $session->tastingRound->roundSnacks->where('snack_id', $review->snack->id)->first();
                            @endphp
                            {{ $roundSnack ? $roundSnack->sequence_order : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->taste_rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                @endfor
                            </div>
                            <span class="ml-1 text-xs">{{ $review->taste_rating }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->texture_rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                @endfor
                            </div>
                            <span class="ml-1 text-xs">{{ $review->texture_rating }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->appearance_rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                @endfor
                            </div>
                            <span class="ml-1 text-xs">{{ $review->appearance_rating }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                @endfor
                            </div>
                            <span class="ml-1 text-xs font-medium">{{ $review->overall_rating }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($review->comment)
                                <div class="max-w-xs truncate" title="{{ $review->comment }}">
                                    {{ $review->comment }}
                                </div>
                            @else
                                <span class="text-gray-400">No comment</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $review->created_at->format('M j, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-star text-4xl mb-4"></i>
                            <p class="text-lg">No reviews yet</p>
                            <p class="text-sm">Reviews will appear here as the participant tastes snacks.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Missing Snacks -->
    @if($session->status !== 'completed')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Remaining Snacks</h3>
        </div>
        <div class="p-6">
            @php
                $reviewedSnackIds = $session->reviews->pluck('snack_id')->toArray();
                $allSnacks = $session->tastingRound->roundSnacks->sortBy('sequence_order');
                $remainingSnacks = $allSnacks->whereNotIn('snack_id', $reviewedSnackIds);
            @endphp
            
            @if($remainingSnacks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($remainingSnacks as $roundSnack)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $roundSnack->snack->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $roundSnack->snack->brand }}</p>
                                <p class="text-xs text-blue-600">Order: {{ $roundSnack->sequence_order }}</p>
                            </div>
                            <div class="text-gray-400">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-4 text-green-500"></i>
                    <p class="text-lg">All snacks have been reviewed!</p>
                    <p class="text-sm">This session is ready to be completed.</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const completeButtons = document.querySelectorAll('[data-confirm-complete]');
    completeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to mark this session as completed? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection
