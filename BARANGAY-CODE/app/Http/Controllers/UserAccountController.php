<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    /**
     * Display a listing of pending user accounts
     */
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
                    ->where('is_archived', false)
                    ->whereIn('account_status', ['pending', 'approved', 'rejected']);

        // Apply search filter
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->q . '%')
                  ->orWhere('first_name', 'like', '%' . $request->q . '%')
                  ->orWhere('last_name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        // Apply sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $query->orderBy($sort, $direction);

        $users = $query->paginate(6)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user_accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'id_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('id_image')) {
            $idImagePath = $request->file('id_image')->store('id_images', 'public');
        }

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_status' => 'pending',
            'id_image_path' => $idImagePath ?? null,
            'is_admin' => false,
        ]);

        return redirect()->route('admin.user_accounts.index')
            ->with('success', 'User account created successfully and is pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find user by ID
            $user = User::findOrFail($id);
            
            // Generate the full URL for the ID image if it exists
            $idImageUrl = null;
            if ($user->id_image_path) {
                // Check if path already starts with http (full URL)
                if (str_starts_with($user->id_image_path, 'http')) {
                    $idImageUrl = $user->id_image_path;
                } else {
                    // Generate URL from storage path
                    $idImageUrl = Storage::url($user->id_image_path);
                }
            }

            return response()->json([
                'id' => $user->id,
                'full_name' => $user->full_name ?? ($user->first_name . ' ' . $user->last_name),
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'birth_date' => $user->birth_date ? $user->birth_date->format('F d, Y') : null,
                'age' => $user->formatted_age ?? null,
                'sex' => $user->sex,
                'is_pwd' => $user->is_pwd,
                'account_status' => $user->account_status,
                'is_archived' => $user->is_archived,
                'archive_reason' => $user->archive_reason,
                'archived_at' => $user->archived_at ? $user->archived_at->format('F d, Y \a\t g:i A') : null,
                'id_image_path' => $idImageUrl,
                'created_at' => $user->created_at ? $user->created_at->format('F d, Y \a\t g:i A') : 'N/A',
                'approved_at' => $user->approved_at ? $user->approved_at->format('F d, Y \a\t g:i A') : null,
                'rejected_at' => $user->rejected_at ? $user->rejected_at->format('F d, Y \a\t g:i A') : null,
                'rejection_reason' => $user->rejection_reason,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json([
                'error' => 'User not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.user_accounts.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.user_accounts.index')
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Approve a user account
     */
    public function approve(User $user)
    {
        $user->update([
            'account_status' => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
            'rejected_at' => null,
        ]);

        return redirect()->route('admin.user_accounts.index')
            ->with('success', 'User account approved successfully.');
    }

    /**
     * Reject a user account
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $user->update([
            'account_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'approved_at' => null,
        ]);

        return redirect()->route('admin.user_accounts.index')
            ->with('success', 'User account rejected successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Delete ID image if exists
        if ($user->id_image_path && Storage::disk('public')->exists($user->id_image_path)) {
            Storage::disk('public')->delete($user->id_image_path);
        }

        $user->delete();

        return redirect()->route('admin.user_accounts.index')
            ->with('success', 'User account deleted successfully.');
    }

    /**
     * Show pending accounts only
     */
    public function pending()
    {
        $users = User::where('is_admin', false)
                    ->where('is_archived', false)
                    ->where('account_status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->paginate(6)
                    ->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Show approved accounts only
     */
    public function approved()
    {
        $users = User::where('is_admin', false)
                    ->where('is_archived', false)
                    ->where('account_status', 'approved')
                    ->orderBy('approved_at', 'desc')
                    ->paginate(6)
                    ->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Show rejected accounts only
     */
    public function rejected()
    {
        $users = User::where('is_admin', false)
                    ->where('is_archived', false)
                    ->where('account_status', 'rejected')
                    ->orderBy('rejected_at', 'desc')
                    ->paginate(6)
                    ->withQueryString();

        return view('admin.users', compact('users'));
    }
    
    /**
     * Archive a user account
     */
    public function archive(Request $request, User $user)
    {
        $request->validate([
            'archive_reason' => 'required|string|max:1000',
        ]);
        
        // Only approved accounts can be archived
        if (!$user->isApproved()) {
            return redirect()->back()->with('error', 'Only approved accounts can be archived.');
        }
        
        $user->archive($request->archive_reason);
        
        return redirect()->route('admin.user_accounts.index')
            ->with('success', 'User account archived successfully.');
    }
    
    /**
     * Unarchive a user account
     */
    public function unarchive(User $user)
    {
        $user->unarchive();
        
        return redirect()->route('admin.archives', ['tab' => 'users'])
            ->with('success', 'User account unarchived successfully.');
    }
}
