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
        <!-- Change Password Section (Left) -->
        <div class="bg-white rounded-lg shadow p-6 h-fit">
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

        <!-- Account Information (Read-only) (Right) -->
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-user-circle text-yellow-500 mr-3"></i>
                    Account Information
                </h2>
                <p class="text-xs text-gray-500">Your account details are displayed below.</p>
            </div>

            <!-- Profile Picture - Larger & Clickable -->
            <div class="border-b pb-6">
                <label class="block text-sm font-medium text-gray-700 mb-4">Profile Picture</label>
                <div class="flex flex-col items-center text-center">
                    @if($user->id_image_path)
                    <div class="w-40 h-40 rounded-lg overflow-hidden border-3 border-yellow-300 bg-gray-100 shadow-md mb-3 cursor-pointer hover:shadow-lg hover:border-yellow-400 transition-all duration-200" 
                         onclick="openImageModal('{{ asset('storage/' . $user->id_image_path) }}')">
                        <img src="{{ asset('storage/' . $user->id_image_path) }}" 
                             alt="Profile Picture" 
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

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('passwordConfirmModal');
            if (!modal.classList.contains('hidden')) {
                closePasswordConfirmModal();
            }
        }
    });
</script>
@endsection
