@extends('layouts.admin_panel')

@section('title', 'User Accounts Management')

@section('content')
<div class="space-y-6">
    <!-- Filters & Sorting Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Header with Toggle -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Filters & Sorting</h3>
                <button type="button" id="toggleFilters" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    <span id="toggleText">Hide Filters</span>
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div id="filtersContent" class="px-6 py-4">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by ID, name, email..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="id" {{ request('sort')=='id'?'selected':'' }}>ID</option>
                            <option value="first_name" {{ request('sort')=='first_name'?'selected':'' }}>Name</option>
                            <option value="email" {{ request('sort')=='email'?'selected':'' }}>Email</option>
                            <option value="account_status" {{ request('sort')=='account_status'?'selected':'' }}>Status</option>
                            <option value="created_at" {{ request('sort')=='created_at'?'selected':'' }}>Registration Date</option>
                        </select>
                    </div>

                    <!-- Sort Direction -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="asc" {{ request('direction')=='asc'?'selected':'' }}>Ascending</option>
                            <option value="desc" {{ request('direction')=='desc'?'selected':'' }}>Descending</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ request('status')==''?'selected':'' }}>All Status</option>
                            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                            <option value="partially_rejected" {{ request('status')=='partially_rejected'?'selected':'' }}>Partially Rejected</option>
                            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.user_accounts.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
                        Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Combined Filter Tabs and Accounts Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Filter Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <a href="{{ route('admin.user_accounts.index') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.user_accounts.index') ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-list mr-2"></i>All Accounts
                </a>
                <a href="{{ route('admin.user_accounts.pending') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.user_accounts.pending') ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-clock mr-2"></i>Pending Review
                </a>
                <a href="{{ route('admin.user_accounts.approved') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.user_accounts.approved') ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-check-circle mr-2"></i>Approved
                </a>
                <a href="{{ route('admin.user_accounts.partially_rejected') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.user_accounts.partially_rejected') ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-exclamation-circle mr-2"></i>Partial Rejected
                </a>
                <a href="{{ route('admin.user_accounts.rejected') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('admin.user_accounts.rejected') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-times-circle mr-2"></i>Rejected
                </a>
            </nav>
        </div>
        
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-b border-green-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- ID Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->id }}</div>
                        </td>

                        <!-- Name Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                                    @if($user->id_image_path)
                                        <button onclick="showImageModal('{{ Storage::url($user->id_image_path) }}')"
                                           class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                            <i class="fas fa-image mr-1"></i>View ID
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Email Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_archived)
                                <span class="text-amber-600 font-medium">Archived</span>
                            @elseif($user->account_status === 'pending')
                                <span class="text-amber-600 font-medium">Pending</span>
                            @elseif($user->account_status === 'approved')
                                <span class="text-green-600 font-medium">Approved</span>
                            @elseif($user->account_status === 'partially_rejected')
                                <span class="text-amber-600 font-medium">Partially Rejected</span>
                            @else
                                <span class="text-red-600 font-medium">Rejected</span>
                            @endif
                        </td>

                        <!-- Registration Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">
                                {{ $user->created_at->format('g:i A') }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="showViewModal({{ $user->id }})" 
                                        title="View Account"
                                        class="px-2 py-2 text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                
                                @if($user->account_status === 'pending')
                                    <button onclick="showReviewModal({{ $user->id }}, '{{ $user->full_name }}')" 
                                            title="Review Account"
                                            class="px-2 py-2 text-yellow-600 hover:text-yellow-800 font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @elseif($user->account_status === 'partially_rejected')
                                    <button onclick="showReviewModal({{ $user->id }}, '{{ $user->full_name }}')" 
                                            title="Review Resubmission"
                                            class="px-2 py-2 text-yellow-600 hover:text-yellow-800 font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @endif
                                
                                @if($user->account_status === 'approved' && !$user->is_archived)
                                    <button onclick="showArchiveModal({{ $user->id }}, '{{ $user->full_name }}')" 
                                            title="Archive Account"
                                            class="px-2 py-2 text-amber-600 hover:text-amber-800 font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                </td>
            </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No user accounts found</h3>
                                <p class="text-gray-500">There are no user accounts to display at this time.</p>
                            </div>
                </td>
            </tr>
                    @endforelse
        </tbody>
    </table>
</div>
        <!-- Pagination -->
        @if(($users ?? collect())->count() > 0)
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Admin Review Modal - Approve / Partial Reject / Total Reject -->
<div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-gray-900">Account Review</h3>
                    <button onclick="hideReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <p id="review_user_name" class="text-sm text-gray-600 mt-2">User: <strong>-</strong></p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Action Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Action</label>
                    <div class="space-y-3">
                        <!-- Approve -->
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-green-50 transition">
                            <input type="radio" name="action" value="approve" class="mt-1 mr-3" onchange="selectAction('approve')">
                            <div>
                                <p class="font-medium text-green-700">✓ Approve Account</p>
                                <p class="text-sm text-gray-600">Account will be verified and resident can access the system.</p>
                            </div>
                        </label>

                        <!-- Partial Reject -->
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-amber-50 transition">
                            <input type="radio" name="action" value="partial" class="mt-1 mr-3" onchange="selectAction('partial')">
                            <div>
                                <p class="font-medium text-amber-700">⚠ Partial Rejection (Soft)</p>
                                <p class="text-sm text-gray-600">Resident can login but must correct flagged information before full access.</p>
                            </div>
                        </label>

                        <!-- Total Reject -->
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-red-50 transition">
                            <input type="radio" name="action" value="total" class="mt-1 mr-3" onchange="selectAction('total')">
                            <div>
                                <p class="font-medium text-red-700">✕ Total Rejection (Hard)</p>
                                <p class="text-sm text-gray-600">Account is rejected. Resident cannot login and will see rejection message.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Reason Textarea (shown for partial and total reject) -->
                <div id="reasonSection" class="hidden">
                    <label for="review_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea id="review_reason" rows="4" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                              placeholder="Provide a detailed reason for this decision. This will be shown to the resident."
                              minlength="10"
                              maxlength="1000"></textarea>
                    <p class="mt-1 text-xs text-gray-500">Minimum 10 characters, maximum 1000 characters.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200">
                <button type="button" onclick="hideReviewModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150">
                    Cancel
                </button>
                <button type="button" onclick="submitReview('approve')" id="btn-approve"
                        class="hidden px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                    <i class="fas fa-check mr-1"></i>Approve
                </button>
                <button type="button" onclick="submitReview('partial')" id="btn-partial"
                        class="hidden px-4 py-2 text-sm font-medium text-white bg-amber-600 border border-transparent rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-150">
                    <i class="fas fa-exclamation-circle mr-1"></i>Request Corrections
                </button>
                <button type="button" onclick="submitReview('total')" id="btn-total"
                        class="hidden px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                    <i class="fas fa-times mr-1"></i>Reject
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Archive Modal -->
<div id="archiveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <form id="archiveForm" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-archive text-amber-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900">Archive Account</h3>
                            <p class="text-sm text-gray-500">Please provide a reason for archiving this account</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="archive_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Archive Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea id="archive_reason" name="archive_reason" rows="4" 
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                  placeholder="Please provide a detailed reason for archiving this account..."
                                  required></textarea>
                        <p class="mt-1 text-xs text-gray-500">This reason will be shown to the user when they try to login.</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" onclick="hideArchiveModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-amber-600 border border-transparent rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-150">
                        <i class="fas fa-archive mr-1"></i>
                        Archive Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-5xl w-full">
            <button onclick="hideImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="ID Image" class="max-w-full max-h-[85vh] mx-auto rounded-lg shadow-2xl">
        </div>
    </div>
</div>

<!-- View User Details Modal -->
<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">User Account Details</h3>
                    <button onclick="hideViewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- User Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold mb-4">Personal Information</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID</label>
                                <p id="view_id" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <p id="view_full_name" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p id="view_email" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Birth Date</label>
                                <p id="view_birth_date" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Age</label>
                                <p id="view_age" class="mt-1 text-sm text-gray-900 font-medium">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sex</label>
                                <p id="view_sex" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">PWD Status</label>
                                <p id="view_is_pwd" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Account Status</label>
                                <div id="view_status" class="mt-1">-</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Registration Date</label>
                                <p id="view_created_at" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div id="view_approved_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Approved Date</label>
                                <p id="view_approved_at" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div id="view_rejected_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Rejected Date</label>
                                <p id="view_rejected_at" class="mt-1 text-sm text-gray-900">-</p>
                            </div>

                            <div id="view_rejection_reason_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                <p id="view_rejection_reason" class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded">-</p>
                            </div>
                            
                            <div id="view_archived_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Archive Status</label>
                                <p id="view_archived_status" class="mt-1 text-sm font-medium text-amber-600">Archived</p>
                            </div>
                            
                            <div id="view_archived_date_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Archived Date</label>
                                <p id="view_archived_at" class="mt-1 text-sm text-gray-900">-</p>
                            </div>
                            
                            <div id="view_archive_reason_section" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Archive Reason</label>
                                <p id="view_archive_reason" class="mt-1 text-sm text-gray-900 bg-amber-50 p-3 rounded">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- ID Image -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold mb-4">ID Verification</h4>
                        
                        <div id="view_id_image_container" class="text-center">
                            <img id="view_id_image" src="" alt="ID Image" class="max-w-full h-auto rounded-lg shadow-md cursor-pointer hidden" onclick="showImageModal(this.src)">
                            <p id="view_image_hint" class="text-xs text-gray-500 mt-2 hidden">Click image to view full size</p>
                            <div id="view_no_image" class="text-center text-gray-500 py-8">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p>No ID image uploaded</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ===== GLOBAL VARIABLES =====
let currentReviewUserId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Filter Toggle
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');
    const toggleText = document.getElementById('toggleText');
    
    toggleBtn.addEventListener('click', function() {
        filtersContent.classList.toggle('hidden');
        toggleText.textContent = filtersContent.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
    });
});

// Image Modal Functions
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// View Modal Functions
function showViewModal(userId) {
    console.log('Fetching user data for ID:', userId);
    
    // Use the correct URL format
    const url = `/admin/user-accounts/${userId}`;
    console.log('Fetching from URL:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        credentials: 'same-origin'
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text);
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            
            // Populate modal with user data
            document.getElementById('view_id').textContent = data.id || '-';
            document.getElementById('view_full_name').textContent = data.full_name || '-';
            document.getElementById('view_email').textContent = data.email || '-';
            document.getElementById('view_birth_date').textContent = data.birth_date || 'N/A';
            document.getElementById('view_age').textContent = data.age ? data.age : 'N/A';
            document.getElementById('view_sex').textContent = data.sex || 'N/A';
            document.getElementById('view_is_pwd').textContent = data.is_pwd ? 'Yes' : 'No';
            document.getElementById('view_created_at').textContent = data.created_at || 'N/A';
            
            // Status text
            let statusHtml = '';
            if (data.account_status === 'pending') {
                statusHtml = '<span class="text-amber-600 font-medium">Pending Approval</span>';
            } else if (data.account_status === 'approved') {
                statusHtml = '<span class="text-green-600 font-medium">Approved</span>';
            } else {
                statusHtml = '<span class="text-red-600 font-medium">Rejected</span>';
            }
            document.getElementById('view_status').innerHTML = statusHtml;
            
            // Approved date
            if (data.approved_at) {
                document.getElementById('view_approved_section').classList.remove('hidden');
                document.getElementById('view_approved_at').textContent = data.approved_at;
            } else {
                document.getElementById('view_approved_section').classList.add('hidden');
            }
            
            // Rejected date
            if (data.rejected_at) {
                document.getElementById('view_rejected_section').classList.remove('hidden');
                document.getElementById('view_rejected_at').textContent = data.rejected_at;
            } else {
                document.getElementById('view_rejected_section').classList.add('hidden');
            }
            
            // Rejection reason
            if (data.rejection_reason) {
                document.getElementById('view_rejection_reason_section').classList.remove('hidden');
                document.getElementById('view_rejection_reason').textContent = data.rejection_reason;
            } else {
                document.getElementById('view_rejection_reason_section').classList.add('hidden');
            }
            
            // Archive status
            if (data.is_archived) {
                document.getElementById('view_archived_section').classList.remove('hidden');
                
                // Archive date
                if (data.archived_at) {
                    document.getElementById('view_archived_date_section').classList.remove('hidden');
                    document.getElementById('view_archived_at').textContent = data.archived_at;
                } else {
                    document.getElementById('view_archived_date_section').classList.add('hidden');
                }
                
                // Archive reason
                if (data.archive_reason) {
                    document.getElementById('view_archive_reason_section').classList.remove('hidden');
                    document.getElementById('view_archive_reason').textContent = data.archive_reason;
                } else {
                    document.getElementById('view_archive_reason_section').classList.add('hidden');
                }
            } else {
                document.getElementById('view_archived_section').classList.add('hidden');
                document.getElementById('view_archived_date_section').classList.add('hidden');
                document.getElementById('view_archive_reason_section').classList.add('hidden');
            }
            
            // ID Image
            if (data.id_image_path) {
                document.getElementById('view_id_image').src = data.id_image_path;
                document.getElementById('view_id_image').classList.remove('hidden');
                document.getElementById('view_image_hint').classList.remove('hidden');
                document.getElementById('view_no_image').classList.add('hidden');
            } else {
                document.getElementById('view_id_image').classList.add('hidden');
                document.getElementById('view_image_hint').classList.add('hidden');
                document.getElementById('view_no_image').classList.remove('hidden');
            }
            
            // Show modal
            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            alert('Failed to load user details. Please try again. Error: ' + error.message);
        });
}

function hideViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showRejectModal(userId, userName) {
    document.getElementById('rejectForm').action = `/admin/user-accounts/${userId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

function showArchiveModal(userId, userName) {
    document.getElementById('archiveForm').action = `/admin/user-accounts/${userId}/archive`;
    document.getElementById('archiveModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function hideArchiveModal() {
    document.getElementById('archiveModal').classList.add('hidden');
    document.getElementById('archive_reason').value = '';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Close modals when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectModal();
    }
});

document.getElementById('archiveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideArchiveModal();
    }
});

document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideViewModal();
    }
});

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideImageModal();
    }
});

// ===== NEW REVIEW MODAL FUNCTIONS =====

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideRejectModal();
        hideArchiveModal();
        hideViewModal();
        hideImageModal();
        hideReviewModal();
    }
});

function showReviewModal(userId, userName) {
    try {
        currentReviewUserId = userId;
        console.log('Opening review modal for user:', userId, userName);
        document.getElementById('review_user_name').innerHTML = `User: <strong>${userName}</strong>`;
        const modal = document.getElementById('reviewModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            console.error('reviewModal element not found');
            alert('Error: Modal not found. Please refresh the page.');
            return;
        }
        
        // Reset form
        document.querySelectorAll('input[name="action"]').forEach(el => el.checked = false);
        document.getElementById('review_reason').value = '';
        document.getElementById('reasonSection').classList.add('hidden');
        document.getElementById('btn-approve').classList.add('hidden');
        document.getElementById('btn-partial').classList.add('hidden');
        document.getElementById('btn-total').classList.add('hidden');
    } catch (error) {
        console.error('Error in showReviewModal:', error);
        alert('Error opening modal: ' + error.message);
    }
}

function hideReviewModal() {
    try {
        const modal = document.getElementById('reviewModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        document.body.style.overflow = 'auto';
        currentReviewUserId = null;
    } catch (error) {
        console.error('Error in hideReviewModal:', error);
    }
}

function selectAction(action) {
    // Update button visibility
    document.getElementById('btn-approve').classList.add('hidden');
    document.getElementById('btn-partial').classList.add('hidden');
    document.getElementById('btn-total').classList.add('hidden');
    
    if (action === 'approve') {
        document.getElementById('btn-approve').classList.remove('hidden');
        document.getElementById('reasonSection').classList.add('hidden');
    } else if (action === 'partial') {
        document.getElementById('btn-partial').classList.remove('hidden');
        document.getElementById('reasonSection').classList.remove('hidden');
    } else if (action === 'total') {
        document.getElementById('btn-total').classList.remove('hidden');
        document.getElementById('reasonSection').classList.remove('hidden');
    }
}

function submitReview(action) {
    if (!currentReviewUserId) {
        alert('Error: User ID not found');
        return;
    }
    
    // Validate reason for partial and total reject
    if ((action === 'partial' || action === 'total') && !document.getElementById('review_reason').value.trim()) {
        alert('Please provide a reason for this decision.');
        return;
    }
    
    // Validate reason length
    const reason = document.getElementById('review_reason').value.trim();
    if ((action === 'partial' || action === 'total') && (reason.length < 10 || reason.length > 1000)) {
        alert('Reason must be between 10 and 1000 characters.');
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    if (action === 'approve') {
        form.action = `/admin/user-accounts/${currentReviewUserId}/approve`;
    } else if (action === 'partial') {
        form.action = `/admin/user-accounts/${currentReviewUserId}/partial-reject`;
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'rejection_reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
    } else if (action === 'total') {
        form.action = `/admin/user-accounts/${currentReviewUserId}/total-reject`;
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'rejection_reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
    }
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Close review modal when clicking outside
const reviewModalElement = document.getElementById('reviewModal');
if (reviewModalElement) {
    reviewModalElement.addEventListener('click', function(e) {
        if (e.target === this) {
            hideReviewModal();
        }
    });
}
</script>
@endsection