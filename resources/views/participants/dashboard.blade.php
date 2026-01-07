@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class=" max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="p-20 space-y-6">
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
                        <div class="border border-gray-200 rounded-lg p-4 transition-shadow hover:shadow-md focus-within:ring-2 focus-within:ring-milele-green focus-within:ring-offset-2">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $round->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($round->description, 100) }}</p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-7">
                                <!-- <span><i class="fas fa-user mr-1"></i> {{ $round->creator->name ?? 'Unknown' }}</span> -->
                                <span class="flex items-start justify-between">
                                    <svg class="mr-1 w-4" fill="currentColor" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M270.797,150.851l72.873-72.873c17.839-17.839,17.839-46.761,0-64.598c-17.839-17.839-46.759-17.839-64.598,0L256,36.451 l-23.072-23.072c-17.839-17.839-46.761-17.839-64.598,0c-17.839,17.839-17.839,46.761,0,64.598l72.873,72.873 c-126.175,4.302-226.144,63.214-226.144,135.269c0,16.648,13.47,30.118,30.118,30.118c35.134,0,35.134-30.118,70.269-30.118 c35.135,0,35.135,30.118,70.271,30.118c35.137,0,35.137-30.118,70.273-30.118c35.137,0,35.137,30.118,70.272,30.118 c35.14,0,35.14-30.118,70.281-30.118c35.141,0,35.141,30.118,70.281,30.118c16.648,0,30.118-13.47,30.118-30.118 C496.942,214.063,396.972,155.152,270.797,150.851z"></path> </g> </g> <g> <g> <path d="M402.283,335.478c-1.667-1.428-3.863-3.31-5.07-4.167c-0.352-0.02-0.986-0.02-1.343,0 c-1.208,0.857-3.403,2.739-5.07,4.168c-10.574,9.062-30.256,25.933-64.538,25.933s-53.963-16.872-64.538-25.936 c-1.667-1.428-3.86-3.308-5.066-4.167c-0.354-0.02-0.985-0.02-1.336,0c-1.209,0.858-3.402,2.738-5.069,4.167 c-10.574,9.064-30.256,25.936-64.538,25.936c-34.281,0-53.963-16.872-64.536-25.936c-1.666-1.429-3.86-3.308-5.067-4.167 c-0.351-0.02-0.979-0.018-1.333,0c-1.208,0.858-3.4,2.738-5.066,4.167c-10.047,8.614-28.33,24.266-59.54,25.802l25.12,150.722 h361.412l25.12-150.722C430.616,359.741,412.332,344.09,402.283,335.478z M203.295,466.824h-45.176v-60.235h45.176V466.824z M353.883,466.824h-45.177v-60.235h45.177V466.824z"></path> </g> </g> </g></svg>
                                    {{ $round->roundSnacks->count() }} snacks
                                </span>
                            </div>

                            <div class="flex items-center justify-center text-sm">
                                <form action="{{ route('tasting.start-session') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <input type="hidden" name="round_id" value="{{ $round->id }}">
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center text-white px-6 py-1 rounded-md font-semibold hover:opacity-95 transition-colors focus:outline-none focus:ring-2 focus:ring-milele-green focus:ring-offset-2"
                                        style="background-color: var(--color-milele-green);"
                                    >
                                        <span>Start</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
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
                                <!-- <span><i class="fas fa-user mr-1"></i> {{ $round->creator->name ?? 'Unknown' }}</span> -->
                                <span class="flex items-start justify-between">
                                    <svg class="mr-1 w-4" fill="currentColor" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M270.797,150.851l72.873-72.873c17.839-17.839,17.839-46.761,0-64.598c-17.839-17.839-46.759-17.839-64.598,0L256,36.451 l-23.072-23.072c-17.839-17.839-46.761-17.839-64.598,0c-17.839,17.839-17.839,46.761,0,64.598l72.873,72.873 c-126.175,4.302-226.144,63.214-226.144,135.269c0,16.648,13.47,30.118,30.118,30.118c35.134,0,35.134-30.118,70.269-30.118 c35.135,0,35.135,30.118,70.271,30.118c35.137,0,35.137-30.118,70.273-30.118c35.137,0,35.137,30.118,70.272,30.118 c35.14,0,35.14-30.118,70.281-30.118c35.141,0,35.141,30.118,70.281,30.118c16.648,0,30.118-13.47,30.118-30.118 C496.942,214.063,396.972,155.152,270.797,150.851z"></path> </g> </g> <g> <g> <path d="M402.283,335.478c-1.667-1.428-3.863-3.31-5.07-4.167c-0.352-0.02-0.986-0.02-1.343,0 c-1.208,0.857-3.403,2.739-5.07,4.168c-10.574,9.062-30.256,25.933-64.538,25.933s-53.963-16.872-64.538-25.936 c-1.667-1.428-3.86-3.308-5.066-4.167c-0.354-0.02-0.985-0.02-1.336,0c-1.209,0.858-3.402,2.738-5.069,4.167 c-10.574,9.064-30.256,25.936-64.538,25.936c-34.281,0-53.963-16.872-64.536-25.936c-1.666-1.429-3.86-3.308-5.067-4.167 c-0.351-0.02-0.979-0.018-1.333,0c-1.208,0.858-3.4,2.738-5.066,4.167c-10.047,8.614-28.33,24.266-59.54,25.802l25.12,150.722 h361.412l25.12-150.722C430.616,359.741,412.332,344.09,402.283,335.478z M203.295,466.824h-45.176v-60.235h45.176V466.824z M353.883,466.824h-45.177v-60.235h45.177V466.824z"></path> </g> </g> </g></svg>
                                    {{ $round->roundSnacks->count() }} snacks
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
