<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Destination;

class HomeController extends Controller
{
    public function __invoke()
    {
        $packages = Package::query()->where('is_published', true)->orderBy('created_at', 'desc')->limit(6)->get();
        $destinations = Destination::query()->where('is_published', true)->orderBy('name')->limit(6)->get();
        return view('public.home', compact('packages', 'destinations'));
    }
}

