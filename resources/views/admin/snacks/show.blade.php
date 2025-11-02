@extends('layouts.app')

@section('title', 'Snack Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $snack->name }}</h1>
            <p class="text-gray-600">Snack details and reviews</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.snacks.edit', $snack) }}" 
               class="inline-flex items-center px-4 py-2 bg-milele-green text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Snack
            </a>
            <a href="{{ route('admin.snacks.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Snacks
            </a>
        </div>
    </div>

    <!-- Snack Info -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Snack Information</h3>
        </div>
        <div class="p-6">
            <div class="flex items-start space-x-6">
                <!-- Image -->
                <div class="flex-shrink-0">
                    @if($snack->image_path)
                        <img src="{{ Storage::url($snack->image_path) }}" alt="{{ $snack->name }}" 
                             class="h-32 w-32 object-cover rounded-lg">
                    @else
                        <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-cookie text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="flex-1">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $snack->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Brand</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $snack->brand }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.categories.show', $snack->category) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    {{ $snack->category->name }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $snack->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $snack->description ?: 'No description provided' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-list-ul mr-1"></i>Ingredients
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $snack->ingredients ?: 'No ingredients listed' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-globe mr-1"></i>Origin
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $snack->origin ?: 'Not specified' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-clock mr-1"></i>Shelf Life
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $snack->shelf_life ?: 'Not specified' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Reviews</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_reviews'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-heart text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Taste</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['avg_taste'] ? number_format($stats['avg_taste'], 1) : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-hand-paper text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Texture</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['avg_texture'] ? number_format($stats['avg_texture'], 1) : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-eye text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Appearance</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['avg_appearance'] ? number_format($stats['avg_appearance'], 1) : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Rating -->
    @if($stats['total_reviews'] > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Overall Rating</h3>
            <div class="flex justify-center items-center space-x-4">
                <div class="flex text-yellow-400 text-4xl">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($stats['avg_overall']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ number_format($stats['avg_overall'], 1) }}
                </div>
                <div class="text-sm text-gray-500">
                    ({{ $stats['total_reviews'] }} reviews)
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Reviews -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taste</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Texture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appearance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($snack->reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $review->session->user->email }}</div>
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
                            <span class="ml-1 text-xs">{{ $review->overall_rating }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $review->created_at->format('M j, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-star text-4xl mb-4"></i>
                            <p class="text-lg">No reviews yet</p>
                            <p class="text-sm">Reviews will appear here once participants start tasting this snack.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
