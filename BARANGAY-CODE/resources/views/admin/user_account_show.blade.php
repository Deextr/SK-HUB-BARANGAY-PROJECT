@extends('layouts.admin_panel')

@section('title', 'User Account Details')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">User Account Details</h2>
        <a href="{{ route('admin.user_accounts.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Information -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->full_name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Status</label>
                    <div class="mt-1">
                        @if($user->account_status === 'pending')
                            <span class="text-amber-600 font-medium">Pending Approval</span>
                        @elseif($user->account_status === 'approved')
                            <span class="text-green-600 font-medium">Approved</span>
                        @else
                            <span class="text-red-600 font-medium">Rejected</span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Registration Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
                
                @if($user->approved_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Approved Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->approved_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
                @endif
                
                @if($user->rejected_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rejected Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->rejected_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
                @endif
                
                @if($user->rejection_reason)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                    <p class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded">{{ $user->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- ID Image -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">ID Verification</h3>
            
            @if($user->id_image_path)
                <div class="text-center">
                    <img src="{{ Storage::url($user->id_image_path) }}" 
                         alt="ID Image" 
                         class="max-w-full h-auto rounded-lg shadow-md mb-4">
                    <a href="{{ Storage::url($user->id_image_path) }}" 
                       target="_blank" 
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        View Full Size
                    </a>
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>No ID image uploaded</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-center space-x-4">
        @if($user->account_status === 'pending')
            <form action="{{ route('admin.user_accounts.approve', $user) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition"
                        onclick="return confirm('Are you sure you want to approve this account?')">
                    Approve Account
                </button>
            </form>
            
            <button onclick="showRejectModal()" 
                    class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 transition">
                Reject Account
            </button>
        @endif
        
        <a href="{{ route('admin.user_accounts.edit', $user) }}" 
           class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 transition font-medium">
            Edit Account
        </a>
        
        <form action="{{ route('admin.user_accounts.destroy', $user) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition"
                    onclick="return confirm('Are you sure you want to delete this account?')">
                Delete Account
            </button>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form action="{{ route('admin.user_accounts.reject', $user) }}" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Account</h3>
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" 
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                  placeholder="Please provide a reason for rejecting this account..."
                                  required></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Reject Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endsection