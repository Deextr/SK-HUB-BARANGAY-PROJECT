<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Forgot Password - Barangay System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-center h-full">
        <!-- Forgot Password box -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md bg-opacity-100 relative">
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-yellow-600 mb-2">FORGOT PASSWORD</h2>
            <p class="text-center text-gray-600 text-sm mb-6">Enter your email address and we'll send you a link to reset your password.</p>

            <!-- Success notification -->
            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 border border-green-400 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

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
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
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

                <!-- Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-yellow-500 text-white py-2 rounded-lg font-bold hover:bg-yellow-600 transition">
                        SEND RESET LINK
                    </button>
                </div>

                <!-- Back to login link -->
                <div class="mt-4 text-center space-y-2">
                    <div>
                        <a href="{{ route('login.form') }}" class="text-yellow-600 font-semibold hover:underline text-sm">
                            Back to Login
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
