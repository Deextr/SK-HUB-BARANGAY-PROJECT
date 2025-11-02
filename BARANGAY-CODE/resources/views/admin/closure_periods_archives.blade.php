@extends('layouts.admin_panel')

@section('title', 'Closure Periods Archives')

@section('content')

@if(session('status'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
@endif

<div class="bg-white rounded shadow p-4 mb-4 flex items-center justify-between">
  <h2 class="text-lg font-semibold">Archived Closure Periods</h2>
  <a href="{{ route('admin.closure_periods.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
</div>

<div class="bg-white rounded shadow p-4">
  <form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search reason/status/date" class="border rounded px-3 py-2" />
    <button class="bg-gray-700 text-white px-4 py-2 rounded">Search</button>
  </form>
  <div class="overflow-x-auto">
    <table class="min-w-full">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="py-2 px-3">Dates</th>
          <th class="py-2 px-3">Time</th>
          <th class="py-2 px-3">Reason</th>
          <th class="py-2 px-3">Status</th>
          <th class="py-2 px-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $p)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $p->start_date->format('M d, Y') }} – {{ $p->end_date->format('M d, Y') }}</td>
          <td class="py-2 px-3">{{ $p->is_full_day ? 'Full day' : ($p->start_time.' - '.$p->end_time) }}</td>
          <td class="py-2 px-3">{{ $p->reason ?? '—' }}</td>
          <td class="py-2 px-3">{{ ucfirst($p->status) }}</td>
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
        @empty
        <tr>
          <td class="py-8 px-3 text-center text-gray-500" colspan="5">
            @if(request('q'))
              No archived items match your search. <a href="{{ route('admin.closure_periods.archives') }}" class="text-blue-600 underline">Clear search</a>
            @else
              No archived items.
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if(($items ?? collect())->count() > 0)
  <div class="mt-6">
    {{ $items->links() }}
  </div>
  @endif
</div>

@endsection


