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
          @forelse(\App\Models\Service::onlyTrashed()->orderBy('name')->paginate(6) as $svc)
          <tr class="border-b">
            <td class="py-2 px-3">{{ $svc->name }}</td>
            <td class="py-2 px-3">{{ $svc->description }}</td>
            <td class="py-2 px-3">{{ $svc->capacity_units }}</td>
            <td class="py-2 px-3">
              <form method="POST" action="{{ route('admin.services.restore', $svc->id) }}" onsubmit="return confirm('Unarchive this service?')">
                @csrf
                <button class="bg-green-600 text-white px-3 py-1 rounded">Unarchive</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td class="py-4 px-3 text-gray-500" colspan="4">No archived services.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @else
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
          @php
            $closures = \App\Models\ClosurePeriod::onlyTrashed()->orderByDesc('deleted_at')->paginate(6);
          @endphp
          @forelse($closures as $p)
          <tr class="border-b">
            <td class="py-2 px-3">{{ $p->start_date->format('M d, Y') }} – {{ $p->end_date->format('M d, Y') }}</td>
            <td class="py-2 px-3">{{ $p->is_full_day ? 'Full day' : ($p->start_time.' - '.$p->end_time) }}</td>
            <td class="py-2 px-3">{{ $p->reason ?? '—' }}</td>
            <td class="py-2 px-3">{{ ucfirst($p->status) }}</td>
            <td class="py-2 px-3">
              <form method="POST" action="{{ route('admin.closure_periods.restore', $p->id) }}" onsubmit="return confirm('Unarchive this period?')">
                @csrf
                <button class="bg-green-600 text-white px-3 py-1 rounded">Unarchive</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td class="py-4 px-3 text-gray-500" colspan="5">No archived closure periods.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-3">{{ $closures->links() }}</div>
    </div>
  @endif
</div>

@endsection


