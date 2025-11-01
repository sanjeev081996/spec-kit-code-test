<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::query()->where('is_published', true)->orderBy('created_at', 'desc')->paginate(12);
        return view('public.packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        abort_unless($package->is_published, 404);
        return view('public.packages.show', compact('package'));
    }
}

