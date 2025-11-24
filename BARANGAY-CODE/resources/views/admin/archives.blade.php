@extends('layouts.admin_panel')

@section('title', 'Archives')

@section('content')

@if(session('status'))
  <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
@endif

<!-- Filters Card (All Tabs) -->
<div class="bg-white rounded-lg shadow mb-6">
    <!-- Header with Toggle -->
    <div class="px-5 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Filters & Sorting</h3>
            <button type="button" id="toggleFilters" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                <span id="toggleText">Hide Filters</span>
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div id="filtersContent" class="px-5 py-4">
        @php
            $currentSort = $sort ?? request('sort');
            $currentDirection = $direction ?? request('direction', 'desc');
        @endphp
        <form method="GET" class="space-y-4">
            <input type="hidden" name="tab" value="{{ request('tab', 'services') }}">
            
            <!-- Row 1: Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                @if(request('tab') === 'closures')
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by reason, status, or dates..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                @elseif(request('tab') === 'users')
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email, or reason..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                @else
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by service name, description, reason..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                @endif
            </div>

            <!-- Row 2: Sort By and Order -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        @if(request('tab') === 'closures')
                            <option value="deleted_at" {{ $currentSort=='deleted_at'?'selected':'' }}>Archived Date</option>
                            <option value="start_date" {{ $currentSort=='start_date'?'selected':'' }}>Start Date</option>
                            <option value="end_date" {{ $currentSort=='end_date'?'selected':'' }}>End Date</option>
                            <option value="reason" {{ $currentSort=='reason'?'selected':'' }}>Reason</option>
                            <option value="status" {{ $currentSort=='status'?'selected':'' }}>Status</option>
                        @elseif(request('tab') === 'users')
                            <option value="archived_at" {{ $currentSort=='archived_at'?'selected':'' }}>Archived Date</option>
                            <option value="full_name" {{ $currentSort=='full_name'?'selected':'' }}>Name</option>
                            <option value="email" {{ $currentSort=='email'?'selected':'' }}>Email</option>
                            <option value="archive_reason" {{ $currentSort=='archive_reason'?'selected':'' }}>Archive Reason</option>
                        @else
                            <option value="created_at" {{ $currentSort=='created_at'?'selected':'' }}>Archived Date</option>
                            <option value="service_id" {{ $currentSort=='service_id'?'selected':'' }}>Service Name</option>
                            <option value="units_archived" {{ $currentSort=='units_archived'?'selected':'' }}>Units Archived</option>
                            <option value="capacity_before" {{ $currentSort=='capacity_before'?'selected':'' }}>Capacity Before</option>
                            <option value="capacity_after" {{ $currentSort=='capacity_after'?'selected':'' }}>Capacity After</option>
                            <option value="reason" {{ $currentSort=='reason'?'selected':'' }}>Reason</option>
                        @endif
                    </select>
                </div>

                <!-- Sort Direction -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                    <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="asc" {{ $currentDirection=='asc'?'selected':'' }}>Ascending</option>
                        <option value="desc" {{ $currentDirection=='desc'?'selected':'' }}>Descending</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.archives', ['tab' => request('tab', 'services')]) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition border border-gray-300">
                    Clear All
                </a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded shadow p-4">
  <div class="flex items-center gap-2 border-b mb-4">
    <a href="{{ route('admin.archives', ['tab' => 'services']) }}" class="px-4 py-2 {{ request('tab','services')==='services' ? 'border-b-2 border-yellow-500 text-yellow-700' : 'text-gray-600' }}">Services</a>
    <a href="{{ route('admin.archives', ['tab' => 'closures']) }}" class="px-4 py-2 {{ request('tab')==='closures' ? 'border-b-2 border-yellow-500 text-yellow-700' : 'text-gray-600' }}">Closure Periods</a>
    <a href="{{ route('admin.archives', ['tab' => 'users']) }}" class="px-4 py-2 {{ request('tab')==='users' ? 'border-b-2 border-yellow-500 text-yellow-700' : 'text-gray-600' }}">Users</a>
  </div>

  @if(request('tab','services')==='services')
    <!-- Combined Service Archives -->
    @php
      $hasRows = isset($serviceRows) && $serviceRows instanceof \Illuminate\Pagination\LengthAwarePaginator && $serviceRows->count() > 0;
    @endphp
    
    @if($hasRows)
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="py-2 px-3">Service Name</th>
            <th class="py-2 px-3">Description</th>
            <th class="py-2 px-3">Units Archived</th>
            <th class="py-2 px-3">Capacity Before</th>
            <th class="py-2 px-3">Capacity After</th>
            <th class="py-2 px-3">Reason</th>
            <th class="py-2 px-3">Archived Date</th>
            <th class="py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($serviceRows as $row)
          <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-3 font-medium">{{ $row->service_name }}</td>
            <td class="py-2 px-3 text-sm text-gray-600">{{ $row->description ?? '—' }}</td>
            <td class="py-2 px-3">
              @if($row->type === 'partial')
                <span class="text-sm font-medium text-amber-600">
                  {{ $row->units_archived }}
                </span>
              @else
                <span class="text-sm font-medium text-red-600">
                  All
                </span>
              @endif
            </td>
            <td class="py-2 px-3">{{ $row->capacity_before }}</td>
            <td class="py-2 px-3">{{ $row->capacity_after }}</td>
            <td class="py-2 px-3 text-sm text-gray-600">{{ $row->reason ?? '—' }}</td>
            <td class="py-2 px-3 text-sm text-gray-500">{{ optional($row->archived_at)->format('M d, Y g:i A') ?? '—' }}</td>
            <td class="py-2 px-3">
              @if($row->type === 'partial')
                <form method="POST" action="{{ route('admin.service_archives.restore', $row->id) }}" onsubmit="return confirm('Restore {{ $row->units_archived }} archived units?')" class="inline">
                  @csrf
                  <button type="submit" title="Restore Units" class="px-2 py-2 text-green-600 hover:text-green-800 font-medium">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                      </svg>
                  </button>
                </form>
              @else
                <form method="POST" action="{{ route('admin.services.restore', $row->id) }}" onsubmit="return confirm('Unarchive this service?')" class="inline">
                  @csrf
                  <button type="submit" title="Unarchive Service" class="px-2 py-2 text-green-600 hover:text-green-800 font-medium">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                      </svg>
                  </button>
                </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="text-center py-12 px-4 bg-gray-50 rounded">
      <p class="text-gray-600">No archived services.</p>
    </div>
    @endif
    @if($hasRows)
    <div class="mt-6">
      {{ $serviceRows->links() }}
    </div>
    @endif
  @elseif(request('tab')==='users')
    @if(($users ?? collect())->count() > 0)
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="py-2 px-3">Name</th>
            <th class="py-2 px-3">Email</th>
            <th class="py-2 px-3">Archive Reason</th>
            <th class="py-2 px-3">Archived Date</th>
            <th class="py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-3 font-medium">{{ $user->full_name }}</td>
            <td class="py-2 px-3 text-sm text-gray-600">{{ $user->email }}</td>
            <td class="py-2 px-3 text-sm text-gray-600">{{ $user->archive_reason ?? '—' }}</td>
            <td class="py-2 px-3 text-sm text-gray-500">{{ $user->archived_at ? $user->archived_at->format('M d, Y g:i A') : '—' }}</td>
            <td class="py-2 px-3">
              <form method="POST" action="{{ route('admin.user_accounts.unarchive', $user->id) }}" onsubmit="return confirm('Unarchive this user account?')" class="inline">
                @csrf
                <button type="submit" title="Unarchive User Account" class="px-2 py-2 text-green-600 hover:text-green-800 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="text-center py-12 px-4">
      <p class="text-gray-600">No archived user accounts.</p>
    </div>
    @endif
    
    @if(($users ?? collect())->count() > 0)
    <div class="mt-6">
      {{ $users->links() }}
    </div>
    @endif
  @else
    @if(($closures ?? collect())->count() > 0)
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="py-2 px-3">Dates</th>
            <th class="py-2 px-3">Reason</th>
            <th class="py-2 px-3">Status</th>
            <th class="py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($closures as $p)
          <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-3">
              <div class="font-medium">{{ $p->start_date->format('M d, Y') }} – {{ $p->end_date->format('M d, Y') }}</div>
              <div class="text-xs text-gray-500">Full day</div>
            </td>
            <td class="py-2 px-3 text-sm text-gray-600">{{ $p->reason ?? '—' }}</td>
            <td class="py-2 px-3 text-sm">
              @if($p->status === 'active')
                <span class="text-green-600 font-medium">{{ ucfirst($p->status) }}</span>
              @else
                <span class="text-amber-600 font-medium">{{ ucfirst($p->status) }}</span>
              @endif
            </td>
            <td class="py-2 px-3">
              <form method="POST" action="{{ route('admin.closure_periods.restore', $p->id) }}" onsubmit="return confirm('Unarchive this period?')" class="inline">
                @csrf
                <button type="submit" title="Unarchive Closure Period" class="px-2 py-2 text-green-600 hover:text-green-800 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="text-center py-12 px-4">
      <p class="text-gray-600">No archived closure periods.</p>
    </div>
    @endif
    
    @if(($closures ?? collect())->count() > 0)
    <div class="mt-6">
      {{ $closures->links() }}
    </div>
    @endif
  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');
    const toggleText = document.getElementById('toggleText');

    if (toggleButton && filtersContent) {
        toggleButton.addEventListener('click', function() {
            filtersContent.classList.toggle('hidden');
            toggleText.textContent = filtersContent.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
        });
    }
});
</script>

@endsection


