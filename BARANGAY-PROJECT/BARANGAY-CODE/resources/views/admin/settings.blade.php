@extends('layouts.admin_panel')

@section('title', 'Settings')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <h2 class="text-2xl font-bold mb-6">Settings </h2>

    <form method="POST" action="#">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Site Name:</label>
            <input type="text" name="site_name" value="Barangay 22-C" class="border rounded px-4 py-2 w-full">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Admin Email:</label>
            <input type="email" name="admin_email" value="admin@example.com" class="border rounded px-4 py-2 w-full">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Notification:</label>
            <select name="notification" class="border rounded px-4 py-2 w-full">
                <option value="enabled" selected>Enabled</option>
                <option value="disabled">Disabled</option>
            </select>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-400 transition">
                Save Settings
            </button>
            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition">
                Back
            </a>
        </div>
    </form>
</div>
@endsection
