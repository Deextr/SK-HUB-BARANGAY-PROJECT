<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reset Password - Barangay System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-center h-full">
        <!-- Reset Password box -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md bg-opacity-100 relative">
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-yellow-600 mb-2">RESET PASSWORD</h2>
            <p class="text-center text-gray-600 text-sm mb-6">Enter your new password below.</p>

            <!-- Error notification -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 border border-red-400 text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Hidden token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           placeholder="Enter your email"
                           required
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               placeholder="Enter new password (min 8 characters)"
                               required
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent pr-12 @error('password') border-red-500 @enderror">
                        <!-- Toggle button -->
                        <button type="button" 
                                id="togglePassword" 
                                aria-label="Show password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.223-3.505M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.958 9.958 0 01-1.58 3.13M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               placeholder="Confirm your new password"
                               required
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent pr-12">
                        <!-- Toggle button -->
                        <button type="button" 
                                id="toggleConfirmPassword" 
                                aria-label="Show password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <svg id="eyeConfirmIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffConfirmIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.223-3.505M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.958 9.958 0 01-1.58 3.13M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-yellow-500 text-white py-2 rounded-lg font-bold hover:bg-yellow-600 transition">
                        RESET PASSWORD
                    </button>
                </div>

                <!-- Back to login link -->
                <div class="mt-4 text-center">
                    <a href="{{ route('login.form') }}" class="text-yellow-600 font-semibold hover:underline text-sm">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to toggle password visibility -->
    <script>
        (function () {
            // Password field toggle
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            const eye = document.getElementById('eyeIcon');
            const eyeOff = document.getElementById('eyeOffIcon');

            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'text') {
                    eye.classList.add('hidden');
                    eyeOff.classList.remove('hidden');
                    toggleBtn.setAttribute('aria-label', 'Hide password');
                } else {
                    eye.classList.remove('hidden');
                    eyeOff.classList.add('hidden');
                    toggleBtn.setAttribute('aria-label', 'Show password');
                }
            });

            // Confirm password field toggle
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
            const eyeConfirm = document.getElementById('eyeConfirmIcon');
            const eyeOffConfirm = document.getElementById('eyeOffConfirmIcon');

            toggleConfirmBtn.addEventListener('click', function () {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);

                if (type === 'text') {
                    eyeConfirm.classList.add('hidden');
                    eyeOffConfirm.classList.remove('hidden');
                    toggleConfirmBtn.setAttribute('aria-label', 'Hide password');
                } else {
                    eyeConfirm.classList.remove('hidden');
                    eyeOffConfirm.classList.add('hidden');
                    toggleConfirmBtn.setAttribute('aria-label', 'Show password');
                }
            });
        })();
    </script>

</body>
</html>
