@extends('layouts.resident_panel')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">Success</h3>
                <div class="mt-1 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Error</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-6">
            <!-- Profile Picture Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-user-circle text-yellow-500 mr-3"></i>
                    Profile Picture
                </h2>
                <p class="text-sm text-gray-600 mb-6">Upload or update your profile picture.</p>

                <!-- Current Profile Picture with Preview -->
                <div class="flex flex-col items-center mb-6">
                    <div class="relative">
                        @if($user->profile_picture)
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-yellow-300 shadow-lg mb-3 cursor-pointer hover:shadow-xl hover:border-yellow-400 transition-all duration-200" 
                            onclick="openImageModal('{{ asset('storage/' . $user->profile_picture) }}')">
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                alt="Profile Picture" 
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-200" 
                                id="profilePreview">
                        </div>
                        @else
                        <div class="w-40 h-40 rounded-full bg-yellow-100 border-4 border-yellow-300 flex items-center justify-center shadow-lg mb-3 cursor-not-allowed">
                            <i class="fas fa-user text-yellow-400 text-6xl"></i>
                        </div>
                        @endif
                        
                        <!-- Upload Status Indicator -->
                        <div id="uploadStatus" class="hidden absolute -top-2 -right-2 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 text-center">
                        {{ $user->profile_picture ? 'Click the image to preview in full size' : 'No profile picture uploaded yet' }}
                    </p>
                </div>

                <!-- Upload Form -->
                <form id="profilePictureForm" action="{{ route('resident.settings.update-profile-picture') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- File Input -->
                    <div>
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Picture
                        </label>
                        <div class="relative">
                            <input type="file" 
                                id="profile_picture" 
                                name="profile_picture" 
                                accept="image/*"
                                class="hidden"
                                onchange="previewImage(event)">
                            <div class="flex items-center space-x-3">
                                <button type="button" 
                                        onclick="document.getElementById('profile_picture').click()"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-upload"></i>
                                    Choose File
                                </button>
                                @if($user->profile_picture)
                                <button type="button" 
                                        onclick="removeProfilePicture()"
                                        class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-trash"></i>
                                    Remove
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- File Name Display -->
                        <div id="fileName" class="mt-2 text-sm text-gray-600 hidden"></div>
                        
                        <!-- Validation Rules -->
                        <p class="text-xs text-gray-500 mt-2">
                            • Maximum file size: 5MB<br>
                            • Supported formats: JPG, PNG, GIF<br>
                            • Recommended size: 400x400 pixels
                        </p>
                        
                        @error('profile_picture')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="uploadButton"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-save"></i>
                        Update Profile Picture
                    </button>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-lock text-yellow-500 mr-3"></i>
                    Change Password
                </h2>
                <p class="text-sm text-gray-600 mb-6">Update your password to keep your account secure.</p>

                <form id="passwordForm" action="{{ route('resident.settings.update-password') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Current Password
                        </label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                               placeholder="Enter your current password"
                               required>
                        @error('current_password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Enter your new password"
                               required>
                        @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Confirm your new password"
                               required>
                        @error('password_confirmation')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="button" 
                            onclick="openPasswordConfirmModal()"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2 mt-6">
                        <i class="fas fa-save"></i>
                        Update Password
                    </button>
                </form>

                <!-- Password Tips -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-lightbulb text-blue-500 mr-2"></i>
                        Security Tips
                    </h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• Use a unique password you don't use elsewhere</li>
                        <li>• Avoid using personal information</li>
                        <li>• Never share your password with anyone</li>
                        <li>• Change your password regularly</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Account Information (Read-only) (Right) -->
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-id-card text-yellow-500 mr-3"></i>
                    Account Information
                </h2>
                <p class="text-xs text-gray-500">Your account details are displayed below.</p>
            </div>

            <!-- ID Photo -->
            <div class="border-b pb-6">
                <label class="block text-sm font-medium text-gray-700 mb-4">ID Photo</label>
                <div class="flex flex-col items-center text-center">
                    @if($user->id_image_path)
                    <div class="w-40 h-40 rounded-lg overflow-hidden border-3 border-yellow-300 bg-gray-100 shadow-md mb-3 cursor-pointer hover:shadow-lg hover:border-yellow-400 transition-all duration-200" 
                         onclick="openImageModal('{{ asset('storage/' . $user->id_image_path) }}')">
                        <img src="{{ asset('storage/' . $user->id_image_path) }}" 
                             alt="ID Photo" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                    </div>
                    @else
                    <div class="w-40 h-40 rounded-lg overflow-hidden border-3 border-yellow-300 bg-yellow-50 flex items-center justify-center shadow-md mb-3 cursor-not-allowed">
                        <i class="fas fa-image text-yellow-200 text-6xl"></i>
                    </div>
                    @endif
                    <p class="text-xs text-gray-500">{{ $user->id_image_path ? 'Click to view full size' : 'Used for account verification' }}</p>
                </div>
            </div>

            <!-- Image Modal - Full Screen -->
            <div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" onclick="closeImageModal(event)">
                <div class="relative max-w-5xl w-full">
                    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 z-10 transition shadow-md">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                    <img id="modalImage" src="" alt="Profile Picture" class="max-w-full max-h-[85vh] mx-auto rounded-lg shadow-2xl">
                </div>
            </div>

            <!-- Password Change Confirmation Modal -->
            <div id="passwordConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="bg-gray-50 rounded-lg shadow-xl max-w-md w-full p-6 animate-in">
                    <!-- Modal Header -->
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-500 text-2xl"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-800">Confirm Password Change</h3>
                    </div>

                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-700 text-sm mb-3">
                            Are you sure you want to change your password? This action cannot be undone.
                        </p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                You will need to log in again with your new password after this change.
                            </p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3">
                        <button type="button" 
                                onclick="closePasswordConfirmModal()"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                            Cancel
                        </button>
                        <button type="button" 
                                onclick="submitPasswordForm()"
                                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i>
                            Confirm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="border-b pb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Personal Information</h3>
                <div class="space-y-3">
                    <!-- First Name -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">First Name</label>
                        <span class="text-sm font-semibold text-gray-800">{{ $user->first_name }}</span>
                    </div>

                    <!-- Last Name -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">Last Name</label>
                        <span class="text-sm font-semibold text-gray-800">{{ $user->last_name }}</span>
                    </div>

                    <!-- Email -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <span class="text-sm font-semibold text-gray-800">{{ $user->email }}</span>
                    </div>

                    <!-- Birth Date -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                        <span class="text-sm font-semibold text-gray-800">{{ $user->birth_date?->format('M d, Y') ?? 'Not provided' }}</span>
                    </div>

                    <!-- Sex -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">Sex</label>
                        <span class="text-sm font-semibold text-gray-800">{{ ucfirst($user->sex) ?? 'Not provided' }}</span>
                    </div>

                    <!-- PWD Status -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">PWD Status</label>
                        <span class="text-sm font-semibold">
                            @if($user->is_pwd)
                            <span class="text-green-600">Yes</span>
                            @else
                            <span class="text-gray-600">No</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Account Status</h3>
                <div class="space-y-3">
                    <!-- Account Status -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <label class="text-sm font-medium text-gray-600">Status</label>
                        <span class="text-sm font-semibold">
                            @if($user->account_status === 'pending')
                            <span class="text-amber-600">Pending Approval</span>
                            @elseif($user->account_status === 'approved')
                            <span class="text-green-600">Approved</span>
                            @elseif($user->account_status === 'partially_rejected')
                            <span class="text-amber-600">Needs Correction</span>
                            @elseif($user->account_status === 'rejected')
                            <span class="text-red-600">Rejected</span>
                            @else
                            <span class="text-gray-600">{{ ucfirst($user->account_status) }}</span>
                            @endif
                        </span>
                    </div>

                    <!-- Approval Date -->
                    <div class="flex justify-between items-center py-2">
                        <label class="text-sm font-medium text-gray-600">Approved On</label>
                        <span class="text-sm font-semibold text-gray-800">{{ $user->approved_at?->format('M d, Y g:i A') ?? 'Not yet approved' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth transitions */
    input:focus {
        transition: all 0.2s ease;
    }

    /* Read-only field styling */
    .bg-gray-50 {
        background-color: #f9fafb;
        cursor: not-allowed;
    }

    /* Animation for upload status */
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }

    .animate-in {
        animation: fadeIn 0.2s ease-out;
    }

</style>

<script>
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal(event) {
        // If event exists and target is not the modal itself, don't close
        if (event && event.target.id !== 'imageModal') {
            return;
        }
        
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });

    // Password confirmation modal functions
    function openPasswordConfirmModal() {
        // Validate form before opening modal
        const form = document.getElementById('passwordForm');
        const currentPassword = document.getElementById('current_password').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        // Basic validation
        if (!currentPassword) {
            alert('Please enter your current password.');
            return;
        }

        if (!password) {
            alert('Please enter your new password.');
            return;
        }

        if (!passwordConfirmation) {
            alert('Please confirm your new password.');
            return;
        }

        if (password !== passwordConfirmation) {
            alert('Passwords do not match. Please try again.');
            return;
        }

        // Show confirmation modal
        document.getElementById('passwordConfirmModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePasswordConfirmModal() {
        document.getElementById('passwordConfirmModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function submitPasswordForm() {
        closePasswordConfirmModal();
        document.getElementById('passwordForm').submit();
    }

    // Close modal when clicking outside
    document.getElementById('passwordConfirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePasswordConfirmModal();
        }
    });

    // Profile Picture Functions
    function previewImage(event) {
        const input = event.target;
        const fileNameDiv = document.getElementById('fileName');
        const uploadButton = document.getElementById('uploadButton');
        const uploadStatus = document.getElementById('uploadStatus');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit. Please choose a smaller file.');
                input.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (JPG, PNG, GIF).');
                input.value = '';
                return;
            }
            
            reader.onload = function(e) {
                // Update preview
                const preview = document.getElementById('profilePreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    // Create new preview if it doesn't exist
                    const previewContainer = document.querySelector('.relative .w-40');
                    const img = document.createElement('img');
                    img.id = 'profilePreview';
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover rounded-full';
                    previewContainer.innerHTML = '';
                    previewContainer.appendChild(img);
                    
                    // Remove placeholder icon
                    const placeholder = document.querySelector('.fa-user');
                    if (placeholder) {
                        placeholder.parentElement.remove();
                    }
                }
                
                // Show file name
                fileNameDiv.textContent = `Selected file: ${file.name}`;
                fileNameDiv.classList.remove('hidden');
                
                // Enable upload button
                uploadButton.disabled = false;
                
                // Show upload status indicator
                uploadStatus.classList.remove('hidden');
                uploadStatus.style.animation = 'fadeIn 0.3s ease-out';
            };
            
            reader.readAsDataURL(file);
        }
    }

   function removeProfilePicture() {
    const user = @json(auth()->user());
    
    if (!user.profile_picture) {
        // Show error message with better styling and animation
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md animate-in mb-4';
        errorDiv.role = 'alert';
        errorDiv.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Cannot Remove Profile Picture</h3>
                    <div class="mt-1 text-sm text-red-700">
                        You don't have a profile picture to remove. Please upload a profile picture first.
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.closest('.animate-in').remove()" 
                                class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <span class="sr-only">Dismiss</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Remove any existing alerts
        const existingAlert = document.querySelector('.animate-in');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Add the new alert at the top of the profile picture section
        const profileSection = document.querySelector('.bg-white.rounded-lg.shadow.p-6');
        profileSection.insertBefore(errorDiv, profileSection.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
        
        return;
    }
    if (confirm('Are you sure you want to remove your profile picture?')) {
        // Show loading state
        const removeButton = document.querySelector('button[onclick="removeProfilePicture()"]');
        const originalText = removeButton.innerHTML;
        removeButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing...';
        removeButton.disabled = true;
        // Send AJAX request to remove profile picture
        fetch('{{ route("resident.settings.remove-profile-picture") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show default avatar
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to remove profile picture');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 alert-message';
            errorDiv.role = 'alert';
            errorDiv.innerHTML = `
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">${error.message || 'An error occurred. Please try again.'}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            `;
            
            // Remove any existing alerts
            const existingAlert = document.querySelector('.alert-message');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Add the new alert
            const form = document.getElementById('profilePictureForm');
            form.parentNode.insertBefore(errorDiv, form.nextSibling);
        })
        .finally(() => {
            // Reset button state
            removeButton.innerHTML = originalText;
            removeButton.disabled = false;
        });
    }
}

    // Close modal on Escape key for password modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('passwordConfirmModal');
            if (!modal.classList.contains('hidden')) {
                closePasswordConfirmModal();
            }
        }
    });

    // Form submission handler
   document.getElementById('profilePictureForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('profile_picture');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-red-600 text-sm mt-2';
    
    // Remove any existing error messages
    const existingError = document.querySelector('.profile-picture-error');
    if (existingError) {
        existingError.remove();
    }

    if (!fileInput.files.length) {
        e.preventDefault();
        errorDiv.textContent = 'Please select a file to upload.';
        errorDiv.classList.add('profile-picture-error');
        fileInput.parentNode.insertBefore(errorDiv, fileInput.nextSibling);
        return;
    }

    // File size validation (5MB)
    const file = fileInput.files[0];
    if (file.size > 5 * 1024 * 1024) {
        e.preventDefault();
        errorDiv.textContent = 'File size exceeds 5MB limit. Please choose a smaller file.';
        errorDiv.classList.add('profile-picture-error');
        fileInput.parentNode.insertBefore(errorDiv, fileInput.nextSibling);
        return;
    }

    // File type validation
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        e.preventDefault();
        errorDiv.textContent = 'Invalid file type. Please upload a JPG, PNG, or GIF image.';
        errorDiv.classList.add('profile-picture-error');
        fileInput.parentNode.insertBefore(errorDiv, fileInput.nextSibling);
        return;
    }

    // Show loading state
    const button = document.getElementById('uploadButton');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    button.disabled = true;

    // Revert button state if form submission fails
    const form = this;
    const originalSubmit = form.submit.bind(form);
    
    form.submit = function() {
        // This will be called if validation passes
        originalSubmit();
    };

    // If we get here, the form will submit normally
});
</script>
@endsection