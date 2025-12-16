<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Requires Corrections - Barangay System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('Barangay_background.jpg') }}'); background-position: center 30%;">

    <!-- Container with flex -->
    <div class="flex items-center justify-center h-full py-6 px-4">
        <!-- Correction box with scroll -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg w-full max-w-md bg-opacity-100 relative max-h-[90vh] overflow-y-auto">

            <!-- Title -->
            <h2 class="text-xl font-bold text-center text-amber-600 mb-4">ACCOUNT REQUIRES CORRECTIONS</h2>

            <!-- Admin Feedback Box -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-amber-800 font-medium text-xs">Admin Feedback:</p>
                        <p class="text-amber-700 text-xs mt-1">{{ $correction_reason ?? 'No specific reason provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Error notification -->
            @if ($errors->any())
                <div class="mb-3 p-2 rounded border border-red-200 bg-red-50 text-red-700 text-xs">
                    <strong>Please fix the following:</strong>
                    <ul class="list-disc ml-4 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Correction Form -->
            <form method="POST" action="{{ route('resident.account.resubmit') }}" enctype="multipart/form-data">
                @csrf

                <!-- Row 1: First Name and Last Name -->
                <div class="flex flex-row space-x-3 mb-3">
                    <!-- First Name -->
                    <div class="flex-1">
                        <input type="text" name="first_name" id="first_name" 
                            class="w-full h-10 border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                            placeholder="First Name"
                            value="{{ Auth::user()->first_name }}" maxlength="50" required>
                        @error('first_name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="flex-1">
                        <input type="text" name="last_name" id="last_name" 
                            class="w-full h-10 border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                            placeholder="Last Name"
                            value="{{ Auth::user()->last_name }}" maxlength="50" required>
                        @error('last_name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Birthdate Dropdowns -->
                <div class="mb-3">
                    <div class="flex flex-row space-x-2">
                        <!-- Month -->
                        <div class="flex-1">
                            <select name="birth_month" id="birth_month" 
                                class="w-full h-10 border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                required>
                                <option value="" disabled>Month</option>
                                <option value="01" {{ Auth::user()->birth_date?->format('m') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ Auth::user()->birth_date?->format('m') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ Auth::user()->birth_date?->format('m') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ Auth::user()->birth_date?->format('m') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ Auth::user()->birth_date?->format('m') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ Auth::user()->birth_date?->format('m') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ Auth::user()->birth_date?->format('m') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ Auth::user()->birth_date?->format('m') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ Auth::user()->birth_date?->format('m') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ Auth::user()->birth_date?->format('m') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ Auth::user()->birth_date?->format('m') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ Auth::user()->birth_date?->format('m') == '12' ? 'selected' : '' }}>December</option>
                            </select>
                        </div>
                        
                        <!-- Day -->
                        <div class="flex-1">
                            <select name="birth_day" id="birth_day" 
                                class="w-full h-10 border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                required>
                                <option value="" disabled>Day</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ Auth::user()->birth_date?->format('d') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <!-- Year -->
                        <div class="flex-1">
                            <select name="birth_year" id="birth_year" 
                                class="w-full h-10 border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                required>
                                <option value="" disabled>Year</option>
                                @for ($i = date('Y') - 1; $i >= date('Y') - 100; $i--)
                                    <option value="{{ $i }}" {{ Auth::user()->birth_date?->format('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    @error('birth_date')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Row 3: Gender Radio Buttons -->
                <div class="mb-3">
                    <div class="grid grid-cols-2 gap-3 w-full">
                        <label class="flex items-center justify-center border border-gray-300 rounded-md px-4 py-2 cursor-pointer hover:bg-gray-50 w-full">
                            <input type="radio" name="sex" value="Male" class="mr-2 text-yellow-500 focus:ring-yellow-500" {{ Auth::user()->sex == 'Male' ? 'checked' : '' }} required>
                            <span class="text-sm">Male</span>
                        </label>
                        <label class="flex items-center justify-center border border-gray-300 rounded-md px-4 py-2 cursor-pointer hover:bg-gray-50 w-full">
                            <input type="radio" name="sex" value="Female" class="mr-2 text-yellow-500 focus:ring-yellow-500" {{ Auth::user()->sex == 'Female' ? 'checked' : '' }} required>
                            <span class="text-sm">Female</span>
                        </label>
                    </div>
                    @error('sex')
                        <span class="text-red-500 text-xs mt-1 block text-center">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Row 4: ID Upload -->
                <div class="mb-3">
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:bg-gray-50 transition cursor-pointer" id="upload-area">
                        <input type="file" name="id_image" id="id_image" 
                            class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif">
                        <div id="upload-icon" class="mb-2">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                        </div>
                        <div id="upload-text">
                            <p class="text-sm text-gray-700">Upload ID Image</p>
                            <p class="text-xs text-gray-500">JPEG, PNG, JPG or GIF</p>
                        </div>
                        <div id="file-details" class="hidden mt-2 w-full text-left">
                            <div class="flex items-center p-2 bg-gray-50 rounded border border-gray-200">
                                <i class="fas fa-file-image text-yellow-500 mr-2"></i>
                                <span class="text-sm text-gray-700 truncate flex-1" id="file-name"></span>
                                <button type="button" id="remove-file" class="ml-auto text-gray-500 hover:text-red-500 p-1">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->id_image_path)
                        <div class="mt-2">
                            <p class="text-xs text-gray-600 mb-1">Current ID:</p>
                            <img 
                                src="{{ Storage::url(Auth::user()->id_image_path) }}" 
                                alt="Current ID" 
                                class="max-w-full h-auto rounded border border-gray-200"
                            />
                        </div>
                    @endif
                    @error('id_image')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PWD Status -->
                <div class="mb-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_pwd" id="is_pwd" value="1" 
                            class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500"
                            {{ Auth::user()->is_pwd ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">I am a Person With Disability (PWD)</span>
                    </label>
                    @error('is_pwd')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Form Buttons -->
                <div class="pt-2 space-y-3">
                    <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-full font-bold hover:bg-yellow-600 transition">
                        RESUBMIT
                    </button>
                </div>
            </form>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-gray-200 text-gray-700 py-2 rounded-full font-bold hover:bg-gray-300 transition text-center mt-3"
                >
                    LOGOUT
                </button>
            </form>
        </div>
    </div>

    <!-- Script for file upload handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('id_image');
            const uploadIcon = document.getElementById('upload-icon');
            const uploadText = document.getElementById('upload-text');
            const fileDetails = document.getElementById('file-details');
            const fileName = document.getElementById('file-name');
            const removeFile = document.getElementById('remove-file');

            // Click to upload
            uploadArea.addEventListener('click', () => fileInput.click());

            // File selected
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    fileName.textContent = file.name;
                    uploadIcon.classList.add('hidden');
                    uploadText.classList.add('hidden');
                    fileDetails.classList.remove('hidden');
                }
            });

            // Remove file
            removeFile.addEventListener('click', (e) => {
                e.preventDefault();
                fileInput.value = '';
                uploadIcon.classList.remove('hidden');
                uploadText.classList.remove('hidden');
                fileDetails.classList.add('hidden');
            });

            // Drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('bg-gray-100');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('bg-gray-100');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('bg-gray-100');
                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    const file = e.dataTransfer.files[0];
                    fileName.textContent = file.name;
                    uploadIcon.classList.add('hidden');
                    uploadText.classList.add('hidden');
                    fileDetails.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>
