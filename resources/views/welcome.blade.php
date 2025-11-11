<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Milele Foods Smart Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
                
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            .brand-gradient {
                background: linear-gradient(135deg, #FF6B35 0%, #FF8E53 100%);
            }
            .brand-gradient-light {
                background: linear-gradient(135deg, #FFF5F0 0%, #FFFAF5 100%);
            }
        </style>
    </head>
    <body class="brand-gradient-light min-h-screen flex items-center justify-center">
        <main class="flex-1 flex items-center justify-center min-h-screen">
            <div class="max-w-md w-full px-4 py-8 flex flex-col items-center justify-center space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <!-- Milele Food Logo -->
                    <div class="flex justify-center">
                        <div class="w-60 h-40 flex items-center justify-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Milele Foods Logo" class="w-full h-full object-contain">
                        </div>
                    </div>

                </div>
                
                <!-- Login Card -->
                <div class="bg-white rounded-2xl shadow-xl py-8 px-12">
                    <div class="text-center mb-3">
                        <h1 class="text-xl font-bold text-gray-900 mb-8">
                            Milele Foods Smart Portal
                        </h1>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('tasting.start') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email Input with @milele.com domain -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" 
                                    name="email" 
                                    id="email"
                                    required
                                    placeholder="username@milele.com"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                    value="{{ old('email') }}"
                                    pattern="[a-zA-Z0-9._%+-]+@milele\.com$"
                                    title="Please use your @milele.com email address">
                            </div>

                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden round_id field (default to first active round) -->
                        @if($activeRounds->count() > 0)
                            <input type="hidden" name="round_id" value="{{ $activeRounds->first()->id }}">
                        @else
                            <!-- Show message when no active rounds exist
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <div>
                                        <p class="font-medium">No Active Tasting Rounds</p>
                                        <p class="text-sm">There are currently no active tasting rounds available. Please contact your administrator to set up a tasting round.</p>
                                    </div>
                                </div>
                            </div> -->
                        @endif

                        <!-- Optional explicit role, used when prompting -->
                        <input type="hidden" name="role" id="chosen_role" value="">

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-milele-green hover:bg-milele-green hover:opacity-95 text-sm text-white py-3 px-6 rounded-lg font-semibold">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Access Tasting Portal
                        </button>
                    </form>
                </div>


                <!-- Footer -->
                <div class="flex flex-col items-center justify-end">
                    <footer class="mt-auto text-center py-6">
                        <p class="text-gray-500 text-sm">
                            &copy; {{ date('Y') }} Milele Food. All rights reserved.
                        </p>
                        <p class="text-gray-400 text-xs mt-1">
                            Internal Employee Portal - Authorized Access Only
                        </p>
                    </footer>
                </div>
            </div>
        </main>

        <!-- Role Selection Modal -->
        @if(session('role_selection_required'))
            <div id="roleModal" class="fixed inset-0 z-50 flex items-center justify-center brand-gradient-light">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Choose your role</h3>
                    <p class="text-gray-600 mb-6">Your account has both Admin and Participant roles. Continue as:</p>

                    <div class="grid grid-cols-2 gap-4">
                        <form method="POST" action="{{ route('tasting.start') }}">
                            @csrf
                            <input type="hidden" name="role" value="admin">
                            <input type="hidden" name="email" value="{{ session('user_email') }}">
                            @if(isset($activeRounds) && $activeRounds->count() > 0)
                                <input type="hidden" name="round_id" value="{{ $activeRounds->first()->id }}">
                            @endif
                            <button type="submit" class="w-full bg-milele-green text-white py-3 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 transition">
                                <i class="fas fa-user-shield mr-2"></i>Admin
                            </button>
                        </form>

                        <form method="POST" action="{{ route('tasting.start') }}">
                            @csrf
                            <input type="hidden" name="role" value="participant">
                            <input type="hidden" name="email" value="{{ session('user_email') }}">
                            @if(isset($activeRounds) && $activeRounds->count() > 0)
                                <input type="hidden" name="round_id" value="{{ $activeRounds->first()->id }}">
                            @endif
                            <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                                <i class="fas fa-user mr-2"></i>Participant
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('welcome') }}" class="mt-6 block w-full text-center text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
                </div>
            </div>
        @endif


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-hide flash messages after 5 seconds
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.bg-red-50, .bg-green-50');
                    alerts.forEach(alert => {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    });
                }, 5000);

                // Validate email domain on form submission
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const email = document.getElementById('email');
                        const emailValue = email.value.trim().toLowerCase();
                        
                        // Check if email ends with @milele.com
                        if (!emailValue.endsWith('@milele.com')) {
                            e.preventDefault();
                            email.classList.add('border-red-500');
                            alert('Please use your @milele.com company email address.');
                            return;
                        }

                        // Basic email validation
                        if (!email.value) {
                            e.preventDefault();
                            email.classList.add('border-red-500');
                            alert('Please enter your email address.');
                        }
                    });

                    // Remove red border when user starts typing
                    const emailInput = document.getElementById('email');
                    emailInput.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            });
        </script>
    </body>
</html>