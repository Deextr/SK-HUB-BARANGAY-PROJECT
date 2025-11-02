<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-start h-full pl-40"> <!-- adjust pl to move right -->
        <!-- register box -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md bg-opacity-100 relative">

            <!-- Logo -->
            <div class="flex justify-center mb-4 -mt-4">
                <div class="w-28 h-28 rounded-full overflow-hidden shadow-xl">
                    <img src="{{ asset('LOGO.png') }}" 
                         alt="Barangay Logo" 
                         class="w-full h-full object-cover scale-125">
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-xl font-bold text-center text-blue-900 mb-4">REGISTER</h2>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <!-- First Name -->
                <div class="mb-3">
                    <label class="block mb-1 font-semibold text-gray-700" for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" 
                           class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label class="block mb-1 font-semibold text-gray-700" for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" 
                           class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Email -->
                <div class="mb-3">
                    <label class="block mb-1 font-semibold text-gray-700" for="email">Email</label>
                    <input type="email" name="email" id="email" 
                           class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Password with show/hide -->
                <div class="mb-3 relative">
                    <label class="block mb-1 font-semibold text-gray-700" for="password">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                               class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12" required>
                        <button type="button" id="togglePassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
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
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password with show/hide -->
                <div class="mb-3 relative">
                    <label class="block mb-1 font-semibold text-gray-700" for="password_confirmation">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12" required>
                        <button type="button" id="togglePasswordConfirm" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.223-3.505M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.958 9.958 0 01-1.58 3.13M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ID Image Upload -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-gray-700" for="id_image">Upload ID Image</label>
                    <input type="file" name="id_image" id="id_image" 
                           class="w-full border px-3 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           accept="image/*" required>
                    <p class="text-xs text-gray-500 mt-1">Please upload a clear image of your valid ID (Max: 2MB)</p>
                    @error('id_image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 rounded-full font-bold hover:bg-blue-700 transition">
                        REGISTER
                    </button>
                </div>
            </form>

            <p class="mt-3 text-center text-sm">Already have an account? 
                <a href="{{ route('login.form') }}" class="text-blue-600 hover:underline font-semibold">Login</a>
            </p>
        </div>
    </div>

    <!-- Script to toggle password visibility -->
    <script>
        (function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            const eyeOff = document.getElementById('eyeOffIcon');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eye.classList.toggle('hidden');
                eyeOff.classList.toggle('hidden');
            });

            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirm = document.getElementById('password_confirmation');
            const eyeConfirm = document.getElementById('eyeIconConfirm');
            const eyeOffConfirm = document.getElementById('eyeOffIconConfirm');

            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirm.setAttribute('type', type);
                eyeConfirm.classList.toggle('hidden');
                eyeOffConfirm.classList.toggle('hidden');
            });
        })();
    </script>
</body>
</html>