@extends('layouts.resident_panel')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Success Message -->
    @if(session('status'))
    <div class="rounded-md bg-green-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Account Settings</h3>
            <p class="mt-1 text-sm text-gray-500">View your personal information and update your password</p>
        </div>

        <form method="POST" action="{{ route('resident.settings.update') }}" class="divide-y divide-gray-200"
            id="settings-form" enctype="multipart/form-data">
            @csrf

            <!-- Personal Information Section (Read-Only) -->
            <div class="px-6 py-6 space-y-6">
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Personal Information</h4>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                            <div class="mt-1">
                                <input type="text" id="first_name"
                                    value="{{ $usersettings->first_name ?? '' }}"
                                    class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md p-3 border bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                            <div class="mt-1">
                                <input type="text" id="last_name"
                                    value="{{ $usersettings->last_name ?? '' }}"
                                    class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md p-3 border bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <div class="mt-1">
                                <input type="email" id="email"
                                    value="{{ $usersettings->email ?? '' }}"
                                    class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md p-3 border bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                            <div class="mt-1">
                                <input type="date" id="birth_date"
                                    value="{{ optional($usersettings->birth_date)->format('Y-m-d') ?? '' }}"
                                    class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md p-3 border bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                            <div class="mt-1">
                                <input type="text" id="sex"
                                    value="{{ ucfirst($usersettings->sex ?? '') }}"
                                    class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md p-3 border bg-gray-100"
                                    readonly>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Person with Disability (PWD)</label>
                            <div class="mt-1">
                                <input type="text"
                                    value="{{ $usersettings->is_pwd ? 'Yes' : 'No' }}"
                                    class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md p-3 border bg-gray-100"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="px-6 py-6 space-y-6">
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Change Password</h4>
                    <p class="text-sm text-gray-500 mb-6">Update your password to keep your account secure</p>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                New Password
                                <span class="text-xs font-normal text-gray-500">(leave blank to keep current)</span>
                            </label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password" placeholder="Enter new password"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-3 border"
                                    autocomplete="new-password">
                            </div>
                            @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                New Password</label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Confirm new password"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-3 border"
                                    autocomplete="new-password">
                            </div>
                            @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ID Image Upload & Save Button -->
            <div class="px-6 py-4 bg-gray-50">
                <div class="flex items-center justify-end">
                    
                    <div class="text-right">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('settings-form').addEventListener('submit', function(event) {
    if (!confirm('Are you sure you want to update your settings?')) {
        event.preventDefault();
    }
});
</script>
@endsection