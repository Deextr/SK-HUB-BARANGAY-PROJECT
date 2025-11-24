@extends('layouts.resident_panel')

@section('title', 'Account Correction Required')

@section('content')

<div class="max-w-2xl mx-auto">
    <!-- Warning Banner -->
    <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="font-semibold text-amber-900">Account Requires Corrections</h3>
                <p class="text-sm text-amber-800 mt-1">Your account has been flagged for corrections. Please review the reason below and update your information.</p>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Admin Feedback</h2>
        <div class="bg-gray-50 p-4 rounded border border-gray-200">
            <p class="text-gray-700">{{ Auth::user()->partially_rejected_reason ?? 'No specific reason provided.' }}</p>
        </div>
        <div class="mt-3 text-sm text-gray-600">
            <p><strong>Flagged on:</strong> {{ Auth::user()->partially_rejected_at?->format('F d, Y \a\t g:i A') ?? 'N/A' }}</p>
            <p><strong>Resubmission attempts:</strong> {{ Auth::user()->resubmission_count ?? 0 }} / 3</p>
        </div>
    </div>

    <!-- Correction Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Update Your Information</h2>
        
        <form method="POST" action="{{ route('resident.account.resubmit') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    value="{{ Auth::user()->first_name }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                />
                @error('first_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    value="{{ Auth::user()->last_name }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                />
                @error('last_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ID Image Upload -->
            <div>
                <label for="id_image" class="block text-sm font-medium text-gray-700 mb-1">ID Image (Optional - Re-upload if needed)</label>
                
                @if(Auth::user()->id_image_path)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Current ID Image:</p>
                        <img 
                            src="{{ Storage::url(Auth::user()->id_image_path) }}" 
                            alt="Current ID" 
                            class="max-w-xs h-auto rounded border border-gray-200"
                        />
                    </div>
                @endif

                <input 
                    type="file" 
                    id="id_image" 
                    name="id_image" 
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                />
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF (Max 2MB)</p>
                @error('id_image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium"
                >
                    Resubmit for Review
                </button>
                <a 
                    href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center"
                >
                    Logout
                </a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </form>

        <!-- Info Box -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> After resubmitting your corrected information, an administrator will review it again. You will be notified once the review is complete.
            </p>
        </div>
    </div>
</div>

@endsection
