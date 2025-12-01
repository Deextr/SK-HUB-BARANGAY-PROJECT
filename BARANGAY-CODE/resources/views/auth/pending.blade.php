<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending - Barangay System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-center h-full">
        <!-- Pending box -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md bg-opacity-100 relative">


            <!-- Pending Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-yellow-600 mb-4">Account Pending Approval</h2>

            <!-- Info Message -->
            @if(session('info'))
                <div class="mb-4 p-3 rounded bg-blue-100 border border-blue-400 text-blue-700 text-center">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Message -->
            <div class="text-center mb-6">
                <p class="text-gray-700 mb-4">
                    Your account has been created successfully! However, it is currently pending approval by our administrators.
                </p>
                <p class="text-sm text-gray-600">
                    Please wait while we verify your identity and residence in our barangay. You will be notified once your account is approved.
                </p>
            </div>

            <!-- Status Info -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.725-1.36 3.49 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium">Status: Pending Review</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('login.form') }}" 
                   class="w-full bg-blue-600 text-white py-2 rounded-full font-bold hover:bg-blue-700 transition text-center block">
                    Back to Login
                </a>
                
                <a href="{{ route('register.reset') }}" 
                   class="w-full bg-gray-200 text-gray-700 py-2 rounded-full font-bold hover:bg-gray-300 transition text-center block">
                    Register Another Account
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    If you have any questions, please contact the barangay office.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
