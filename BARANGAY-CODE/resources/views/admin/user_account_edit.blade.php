@extends('layouts.admin_panel')

@section('title', 'Edit User Account')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit User Account</h2>
        <a href="{{ route('admin.user_accounts.show', $user) }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            Back to Details
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.user_accounts.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" 
                           value="{{ old('first_name', $user->first_name) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" id="last_name" 
                           value="{{ old('last_name', $user->last_name) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
            </div>
            
            <!-- Contact Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Account Status</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Current Status:</span>
                        @if($user->account_status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium ml-2">
                                Pending Approval
                            </span>
                        @elseif($user->account_status === 'approved')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium ml-2">
                                Approved
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium ml-2">
                                Rejected
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($user->rejection_reason)
                <div class="mt-3">
                    <span class="text-sm font-medium text-gray-700">Rejection Reason:</span>
                    <p class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded">{{ $user->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('admin.user_accounts.show', $user) }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition">
                Update Account
            </button>
        </div>
    </form>
</div>
@endsection
