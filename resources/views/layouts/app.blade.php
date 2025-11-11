<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Smart Tasting Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="px-6 w-full">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900 flex items-center transition-colors">
                        <svg class="mr-3" width="30px" height="28px" viewBox="0 0 48.00 48.00" id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" fill="#178401" stroke="#178401" stroke-width="3.8880000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.384"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;stroke:#178401;stroke-linecap:round;stroke-linejoin:round;}</style></defs><path class="cls-1" d="M40.5,5.5H7.5a2,2,0,0,0-2,2v33a2,2,0,0,0,2,2h33a2,2,0,0,0,2-2V7.5A2,2,0,0,0,40.5,5.5Z"></path><polyline class="cls-1" points="10.57 37.47 10.57 10.5 24 37.5 37.43 10.54 37.43 37.5"></polyline></g></svg>
                        Smart Tasting Portal
                    </a>
                </div>
                <div class="flex items-center space-x-1">
                    @if(session('user_email'))
                        <?php 
                            $currentUser = \App\Models\User::where('email', session('user_email'))->first();
                            $currentRole = session('user_role', 'participants');
                        ?>
                        @if($currentUser && $currentRole === 'participant')
                            <a href="{{ route('participants.dashboard') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md transition-colors">
                                <i class="fas fa-tachometer-alt mr-1.5"></i>Dashboard
                            </a>
                        @endif
                        <a href="{{ url('/') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md transition-colors">
                            <i class="fas fa-sign-out-alt mr-1.5"></i>Logout
                        </a>
                    @else
                        <a href="{{ url('/') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md transition-colors">
                            <i class="fas fa-sign-in-alt mr-1.5"></i>Login
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with optional admin sidebar -->
    <?php 
        $currentUser = session('user_email') ? \App\Models\User::where('email', session('user_email'))->first() : null;
        $currentRole = session('user_role', 'participant');
        $isAdminView = $currentUser && $currentRole === 'admin';
    ?>
    <div class="flex flex-1">
        @if($isAdminView)
            <aside class="w-64 bg-white border-r border-gray-200">
                <nav class="py-6">
                    <div class="px-6 pb-3">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</h3>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/dashboard') || Request::is('admin') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-tachometer-alt mr-3 w-4"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.tasting-rounds.index') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/tasting-rounds*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-list-ol mr-3 w-4"></i>
                        Tasting Rounds
                    </a>
                    <a href="{{ route('admin.snacks.index') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/snacks*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="mr-3 w-4" fill="currentColor" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M270.797,150.851l72.873-72.873c17.839-17.839,17.839-46.761,0-64.598c-17.839-17.839-46.759-17.839-64.598,0L256,36.451 l-23.072-23.072c-17.839-17.839-46.761-17.839-64.598,0c-17.839,17.839-17.839,46.761,0,64.598l72.873,72.873 c-126.175,4.302-226.144,63.214-226.144,135.269c0,16.648,13.47,30.118,30.118,30.118c35.134,0,35.134-30.118,70.269-30.118 c35.135,0,35.135,30.118,70.271,30.118c35.137,0,35.137-30.118,70.273-30.118c35.137,0,35.137,30.118,70.272,30.118 c35.14,0,35.14-30.118,70.281-30.118c35.141,0,35.141,30.118,70.281,30.118c16.648,0,30.118-13.47,30.118-30.118 C496.942,214.063,396.972,155.152,270.797,150.851z"></path> </g> </g> <g> <g> <path d="M402.283,335.478c-1.667-1.428-3.863-3.31-5.07-4.167c-0.352-0.02-0.986-0.02-1.343,0 c-1.208,0.857-3.403,2.739-5.07,4.168c-10.574,9.062-30.256,25.933-64.538,25.933s-53.963-16.872-64.538-25.936 c-1.667-1.428-3.86-3.308-5.066-4.167c-0.354-0.02-0.985-0.02-1.336,0c-1.209,0.858-3.402,2.738-5.069,4.167 c-10.574,9.064-30.256,25.936-64.538,25.936c-34.281,0-53.963-16.872-64.536-25.936c-1.666-1.429-3.86-3.308-5.067-4.167 c-0.351-0.02-0.979-0.018-1.333,0c-1.208,0.858-3.4,2.738-5.066,4.167c-10.047,8.614-28.33,24.266-59.54,25.802l25.12,150.722 h361.412l25.12-150.722C430.616,359.741,412.332,344.09,402.283,335.478z M203.295,466.824h-45.176v-60.235h45.176V466.824z M353.883,466.824h-45.177v-60.235h45.177V466.824z"></path> </g> </g> </g></svg>
                        Snacks
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/categories*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-tags mr-3 w-4"></i>
                        Categories
                    </a>
                    <a href="{{ route('admin.participants') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/participants*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-users mr-3 w-4"></i>
                        Participants
                    </a>
                    <a href="{{ route('admin.sessions.index') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/sessions*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-clock mr-3 w-4"></i>
                        Sessions
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/reviews*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-star mr-3 w-4"></i>
                        Reviews
                    </a>
                    <div class="px-6 pt-6 pb-3">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</h3>
                    </div>
                    <a href="{{ route('admin.analytics') }}" class="flex items-center px-6 py-3 {{ Request::is('admin/analytics*') ? ' bg-green-50 border-r-4 border-[#178401] font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <i class="fas fa-chart-bar mr-3 w-4"></i>
                        Analytics
                    </a>
                    <a href="{{ route('admin.export', 'csv') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-download mr-3 w-4"></i>
                        Export Data
                    </a>
                </nav>
            </aside>
        @endif

        <main class="flex-1">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-6">
            <p class="text-center text-gray-500">
                © 2025 Smart Tasting Portal. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-auto-hide');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>