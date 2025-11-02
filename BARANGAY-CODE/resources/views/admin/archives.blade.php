@extends('layouts.admin_panel')

@section('title', 'Archives')

@section('content')

@if(session('status'))
  <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
@endif

<div class="bg-white rounded shadow p-4">
  <div class="flex items-center gap-2 border-b mb-4">
    <a href="{{ route('admin.archives', ['tab' => 'services']) }}" class="px-4 py-2 {{ request('tab','services')==='services' ? 'border-b-2 border-yellow-500 text-yellow-700' : 'text-gray-600' }}">Services</a>
    <a href="{{ route('admin.archives', ['tab' => 'closures']) }}" class="px-4 py-2 {{ request('tab')==='closures' ? 'border-b-2 border-yellow-500 text-yellow-700' : 'text-gray-600' }}">Closure Periods</a>
  </div>

  @if(request('tab','services')==='services')
    @if(($services ?? collect())->count() > 0)
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="py-2 px-3">Name</th>
            <th class="py-2 px-3">Description</th>
            <th class="py-2 px-3">Capacity</th>
            <th class="py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($services as $svc)
          <tr class="border-b">
            <td class="py-2 px-3">{{ $svc->name }}</td>
            <td class="py-2 px-3">{{ $svc->description }}</td>
            <td class="py-2 px-3">{{ $svc->capacity_units }}</td>
            <td class="py-2 px-3">
              <form method="POST" action="{{ route('admin.services.restore', $svc->id) }}" onsubmit="return confirm('Unarchive this service?')" class="inline">
                @csrf
                <button type="submit" title="Unarchive Service" class="px-2 py-2 text-green-600 hover:text-green-800 font-medium">
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
      <p class="text-gray-600">No archived services.</p>
    </div>
    @endif
    
    @if(($services ?? collect())->count() > 0)
    <div class="mt-6">
      {{ $services->links() }}
    </div>
    @endif
  @else
    @if(($closures ?? collect())->count() > 0)
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
          @foreach($closures as $p)
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

@endsection


