<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ClosurePeriod;
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
        } else {
            $services = Service::onlyTrashed()
                ->orderBy('name')
                ->paginate(6)
                ->withQueryString();
            $closures = collect();
        }

        return view('admin.archives', compact('services', 'closures', 'tab'));
    }
}

