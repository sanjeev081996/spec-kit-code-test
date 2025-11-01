<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Destination;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::query()->where('is_published', true)->orderBy('name')->paginate(12);
        return view('public.destinations.index', compact('destinations'));
    }

    public function show(Destination $destination)
    {
        abort_unless($destination->is_published, 404);
        return view('public.destinations.show', compact('destination'));
    }
}

