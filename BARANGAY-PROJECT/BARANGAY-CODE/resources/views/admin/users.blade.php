@extends('layouts.admin_panel')

@section('title', 'Users')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <h2 class="text-2xl font-bold mb-6">Users </h2>

    <table class="w-full border border-gray-300 rounded">
        <thead>
            <tr class="bg-yellow-500 text-white">
                <th class="py-2 px-4 border">ID</th>
                <th class="py-2 px-4 border">Name</th>
                <th class="py-2 px-4 border">Email</th>
                <th class="py-2 px-4 border">Role</th>
                <th class="py-2 px-4 border">Action</th>
            </tr>
        </thead>
        <tbody>
            {{-- Dummy users --}}
            <tr>
                <td class="py-2 px-4 border">1</td>
                <td class="py-2 px-4 border">John Doe</td>
                <td class="py-2 px-4 border">john@example.com</td>
                <td class="py-2 px-4 border">Admin</td>
                <td class="py-2 px-4 border">
                    <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-400 transition">Edit</button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-400 transition">Delete</button>
                </td>
            </tr>
            <tr>
                <td class="py-2 px-4 border">2</td>
                <td class="py-2 px-4 border">Jane Smith</td>
                <td class="py-2 px-4 border">jane@example.com</td>
                <td class="py-2 px-4 border">User</td>
                <td class="py-2 px-4 border">
                    <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-400 transition">Edit</button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-400 transition">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
