@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="p-20 space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-list-ol text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Rounds</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $roundStats->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Participants</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $roundStats->sum('completed_sessions') }}</p>
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
                        {{ number_format($categoryRatings->avg('avg_rating'), 1) }}/5
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Reviews</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $categoryRatings->sum('review_count') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Ratings Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ratings by Category</h3>
            <div class="h-64">
                <canvas id="categoryRatingsChart"></canvas>
            </div>
        </div>

        <!-- Rating Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rating Distribution</h3>
            <div class="h-64">
                <canvas id="ratingDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Round Performance -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Round Performance</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Round Name
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Participants
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Avg Rating
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roundStats as $round)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $round->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $round->completed_sessions }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $round->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $round->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($round->average_rating, 1) }}/5
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Performing Snacks -->
    @if($snackPerformance->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top Performing Snacks</h3>
        </div>
        <div class="p-6">
            <div class="grid gap-4">
                @foreach($snackPerformance as $snack)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $snack->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $snack->brand }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($snack->avg_overall, 1) }}</p>
                            <p class="text-xs text-gray-500">Overall</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($snack->avg_taste, 1) }}</p>
                            <p class="text-xs text-gray-500">Taste</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">{{ $snack->reviews_count }}</p>
                            <p class="text-xs text-gray-500">Reviews</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Export Section -->
    <!-- <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Data Export</h3>
        </div>
        <div class="p-6">
            <div class="flex space-x-4">
                <a href="{{ route('admin.export', 'csv') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Export All Data as CSV
                </a>
                <a href="{{ route('admin.reviews.export') }}" 
                   class="inline-flex items-center px-4 py-2 bg-milele-green text-white rounded-lg hover:bg-milele-green hover:opacity-95 transition-colors">
                    <i class="fas fa-file-csv mr-2"></i> Export Reviews Only
                </a>
            </div>
        </div>
    </div> -->

    <!-- Monthly Reviews Trend -->
    <!-- <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Reviews Trend</h3>
        <div class="h-64">
            <canvas id="monthlyReviewsChart"></canvas>
        </div>
    </div> -->

    <!-- Rating Breakdown -->
    <!-- <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Rating Breakdown</h3>
        <div class="h-64">
            <canvas id="ratingBreakdownChart"></canvas>
        </div>
    </div> -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Ratings Chart
    const categoryCtx = document.getElementById('categoryRatingsChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: @json($categoryRatings->pluck('name')),
            datasets: [{
                label: 'Average Rating',
                data: @json($categoryRatings->pluck('avg_rating')),
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Rating Distribution Chart
    const distributionCtx = document.getElementById('ratingDistributionChart').getContext('2d');
    
    // Prepare rating distribution data
    const ratingLabels = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
    const ratingData = [0, 0, 0, 0, 0];
    
    @foreach($ratingDistribution as $dist)
        ratingData[{{ $dist->rating }} - 1] = {{ $dist->count }};
    @endforeach

    new Chart(distributionCtx, {
        type: 'pie',
        data: {
            labels: ratingLabels,
            datasets: [{
                data: ratingData,
                backgroundColor: [
                    'rgba(239, 68, 68, 0.6)',  // 1 Star - Red
                    'rgba(249, 115, 22, 0.6)', // 2 Stars - Orange
                    'rgba(234, 179, 8, 0.6)',  // 3 Stars - Yellow
                    'rgba(34, 197, 94, 0.6)',  // 4 Stars - Green
                    'rgba(59, 130, 246, 0.6)'  // 5 Stars - Blue
                ],
                borderColor: [
                    'rgba(239, 68, 68, 1)',
                    'rgba(249, 115, 22, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(59, 130, 246, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

   // Monthly Reviews Trend Chart
    // const monthlyCtx = document.getElementById('monthlyReviewsChart').getContext('2d');
    // new Chart(monthlyCtx, {
    //     type: 'line',
    //     data: {
    //         labels: @json($monthlyReviews->pluck('formatted_date')),
    //         datasets: [{
    //             label: 'Reviews per Month',
    //             data: @json($monthlyReviews->pluck('count')),
    //             borderColor: 'rgba(139, 92, 246, 1)',
    //             backgroundColor: 'rgba(139, 92, 246, 0.1)',
    //             tension: 0.4,
    //             fill: true
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false
    //     }
    // });

    // Rating Breakdown Chart
    // const breakdownCtx = document.getElementById('ratingBreakdownChart').getContext('2d');
    // new Chart(breakdownCtx, {
    //     type: 'radar',
    //     data: {
    //         labels: ['Taste', 'Texture', 'Appearance', 'Overall'],
    //         datasets: [{
    //             label: 'Average Ratings',
    //             data: [
    //                 {{ $ratingBreakdown->avg('taste_rating') }},
    //                 {{ $ratingBreakdown->avg('texture_rating') }},
    //                 {{ $ratingBreakdown->avg('appearance_rating') }},
    //                 {{ $ratingBreakdown->avg('overall_rating') }}
    //             ],
    //             backgroundColor: 'rgba(34, 197, 94, 0.2)',
    //             borderColor: 'rgba(34, 197, 94, 1)',
    //             pointBackgroundColor: 'rgba(34, 197, 94, 1)'
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         scales: {
    //             r: {
    //                 beginAtZero: true,
    //                 max: 5,
    //                 ticks: {
    //                     stepSize: 1
    //                 }
    //             }
    //         }
    //     }
    // });
});
</script>
@endsection