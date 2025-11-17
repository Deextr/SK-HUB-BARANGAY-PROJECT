<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ClosurePeriod;
use App\Models\User;
use Illuminate\Http\Request;

class ArchivesController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'services');
        
        if ($tab === 'closures') {
            $closures = ClosurePeriod::onlyTrashed()
                ->orderByDesc('deleted_at')
                ->paginate(6)
                ->withQueryString();
            $services = collect();
            $users = collect();
        } elseif ($tab === 'users') {
            $users = User::where('is_archived', true)
                ->where('is_admin', false)
                ->orderByDesc('archived_at')
                ->paginate(6)
                ->withQueryString();
            $services = collect();
            $closures = collect();
        } else {
            $services = Service::onlyTrashed()
                ->orderBy('name')
                ->paginate(6)
                ->withQueryString();
            $closures = collect();
            $users = collect();
        }

        return view('admin.archives', compact('services', 'closures', 'users', 'tab'));
    }
}

