<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Disclosure Agreement - Barangay Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-center h-full">
        <!-- NDA box -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl bg-opacity-100 relative">
            
            <!-- Logo removed as per requirements -->

            <!-- Title -->
            <h2 class="text-xl font-bold text-center text-blue-900 mb-4">NON-DISCLOSURE AGREEMENT</h2>

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

            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 border border-red-400 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Agreement Content -->
            <div class="mb-6 text-sm text-gray-700 space-y-4">
                <p class="font-semibold">Please read the following terms and conditions carefully before proceeding with registration:</p>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 max-h-[60vh] overflow-y-auto">
                    <ol class="list-decimal list-inside space-y-3">
                        <li>
                            <strong>Confidentiality:</strong> By registering, you agree to maintain the confidentiality of all information 
                            provided during the registration process and any subsequent interactions with the barangay system.
                        </li>
                        <li>
                            <strong>Data Protection:</strong> Your personal information will be used solely for barangay administrative 
                            purposes and will be protected in accordance with data privacy regulations.
                        </li>
                        <li>
                            <strong>Accurate Information:</strong> You certify that all information provided during registration is 
                            accurate, truthful, and up-to-date. Providing false information may result in account rejection or termination.
                        </li>
                        <li>
                            <strong>Account Responsibility:</strong> You are responsible for maintaining the security of your account 
                            credentials and for all activities that occur under your account.
                        </li>
                        <li>
                            <strong>System Usage:</strong> You agree to use the barangay system in a lawful manner and in accordance 
                            with all applicable rules and regulations.
                        </li>
                        <li>
                            <strong>Approval Process:</strong> Your registration is subject to administrative approval. The barangay 
                            reserves the right to approve or reject any registration at its discretion.
                        </li>
                        <li>
                            <strong>Compliance:</strong> You agree to comply with all barangay policies, procedures, and guidelines 
                            that may be established from time to time.
                        </li>
                    </ol>
                </div>

                <p class="text-xs text-gray-600 italic">
                    By clicking "Accept", you acknowledge that you have read, understood, and agree to be bound by the terms and 
                    conditions stated above. If you do not agree, please click "Reject" to return to the login page.
                </p>
            </div>

            <!-- Action Buttons -->
            <form method="POST" action="{{ route('register.nda.accept') }}" class="mb-4">
                @csrf
                <div class="flex gap-3">
                    <a href="{{ route('login.form') }}" 
                       class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-full font-bold hover:bg-gray-400 transition text-center">
                        REJECT
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-2 rounded-full font-bold hover:bg-blue-700 transition">
                        ACCEPT
                    </button>
                </div>
            </form>

            <!-- Text removed as requested -->
        </div>
    </div>
</body>
</html>

