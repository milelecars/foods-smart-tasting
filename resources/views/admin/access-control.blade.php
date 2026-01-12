@extends('layouts.app')

@section('title', 'Access Control')

@section('content')
<div class="p-6 md:p-10 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 text-gray-900">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-lg md:text-2xl font-bold">Manage Admins</h1>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 text-sm p-4 mb-4 rounded-lg alert-auto-hide">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 text-sm p-4 mb-4 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr class="text-xs md:text-sm text-gray-500 uppercase">
                            <th class="px-2 py-3 text-center">Name</th>
                            <th class="px-2 py-3 text-center">Email</th>
                            <th class="px-2 py-3 text-center">Role</th>
                            <th class="px-2 py-3 text-center">Created At</th>
                            <th class="px-2 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                    <form method="POST" action="{{ route('admin.access-control.update', $user->id) }}" class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center md:space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <input name="name" type="text" value="{{ $user->name }}" class="border border-gray-300 rounded-md p-1 text-sm w-full" />
                                </td>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                        <input name="email" type="email" value="{{ $user->email }}" class="border border-gray-300 rounded-md p-1 text-sm w-full" />
                                </td>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                        <select name="role" class="border border-gray-300 rounded-md p-1 text-sm w-full">
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="participant" {{ $user->role === 'participant' ? 'selected' : '' }}>Participant</option>
                                            <option value="admin / participant" {{ $user->role === 'admin / participant' ? 'selected' : '' }}>Admin / Participant</option>
                                        </select>
                                </td>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                        {{ $user->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-2 py-4 flex justify-center items-center space-x-2">
                                        <button type="submit" title="Update User" class="text-green-600 hover:text-green-700 p-2 rounded-lg hover:bg-green-100 transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 md:w-6 md:h-6">
                                                <path fill-rule="evenodd" d="M19.5 6.75a.75.75 0 0 0-1.06-1.06L9 15.19l-3.44-3.44a.75.75 0 0 0-1.06 1.06l4 4a.75.75 0 0 0 1.06 0l10-10Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.access-control.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Admin" class="text-red-500 hover:text-red-600 p-2 rounded-lg hover:bg-red-100 transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 32 32" class="w-6 h-6 md:w-7 md:h-7">
                                                <path fill="#ef4444" fill-rule="nonzero" stroke-linecap="round" stroke-linejoin="round" d="M 15 4 C 14.476563 4 13.941406 4.183594 13.5625 4.5625 C 13.183594 4.941406 13 5.476563 13 6 L 13 7 L 7 7 L 7 9 L 8 9 L 8 25 C 8 26.644531 9.355469 28 11 28 L 23 28 C 24.644531 28 26 26.644531 26 25 L 26 9 L 27 9 L 27 7 L 21 7 L 21 6 C 21 5.476563 20.816406 4.941406 20.4375 4.5625 C 20.058594 4.183594 19.523438 4 19 4 Z M 15 6 L 19 6 L 19 7 Z M 10 9 L 24 9 L 24 25 C 24 25.554688 23.554688 26 23 26 L 11 26 C 10.445313 26 10 25.554688 10 25 Z M 12 12 L 12 23 L 14 23 L 14 12 Z M 16 12 L 16 23 L 18 23 L 18 12 Z M 20 12 L 20 23 L 22 23 L 22 12 Z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Add New Admin Row --}}
                        <tr>
                            <form method="POST" action="{{ route('admin.access-control.store') }}" class="grid grid-cols-4 gap-2 md:gap-4">
                                @csrf
                                <td class="px-2 py-4 text-xs md:text-sm">
                                    <input type="text" name="name" placeholder="Name" class="border border-gray-300 rounded-md p-1 text-sm w-full" required>
                                </td>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                    <input type="email" name="email" placeholder="Email" class="border border-gray-300 rounded-md p-1 text-sm w-full" required>
                                </td>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                    <select name="role" class="border border-gray-300 rounded-md p-1 text-sm w-full" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="participant">Participant</option>
                                        <option value="admin / participant">Admin / Participant</option>
                                    </select>
                                </td>
                                <td class="px-2 py-4 text-xs md:text-sm">
                                    <button type="submit" class="p-2 bg-milele-green hover:bg-milele-green-600 ml-2 text-white text-xs font-semibold rounded-lg justify-center items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection