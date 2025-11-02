<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Barangay Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-start h-full pl-40"> <!-- adjust pl to move right -->
        <!-- Login box -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md bg-opacity-100 relative">
            
            <!-- Logo -->
            <div class="flex justify-center mb-6 -mt-6">
                <div class="w-35 h-40 rounded-full overflow-hidden shadow-xl">
                    <img src="{{ asset('LOGO.png') }}" 
                         alt="Barangay Logo" 
                         class="w-full h-full object-cover scale-125">
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">LOGIN</h2>

            <!-- Error notification -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 border border-red-400 text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-yellow-100 border border-yellow-400 text-yellow-700">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold">Email:</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Password with show/hide -->
                <div class="mb-4 relative">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password:</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12"
                        >
                        <!-- Toggle button -->
                        <button
                            type="button"
                            id="togglePassword"
                            aria-label="Show password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        >
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>

                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.223-3.505M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.958 9.958 0 01-1.58 3.13M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-6 text-sm">
                    <label class="flex items-center space-x-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded">
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 rounded-full font-bold hover:bg-blue-700 transition">
                        LOGIN
                    </button>
                </div>

                <!-- Register link -->
                <div class="mt-4 text-center">
                    <span class="text-gray-600">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
                        Register
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script to toggle password visibility -->
    <script>
        (function () {
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
        })();
    </script>

</body>
</html>