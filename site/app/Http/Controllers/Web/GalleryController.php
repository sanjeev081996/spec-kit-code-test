<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Media;

class GalleryController extends Controller
{
    public function __invoke()
    {
        $media = Media::query()->orderByDesc('created_at')->paginate(24);
        return view('public.gallery', compact('media'));
    }
}

