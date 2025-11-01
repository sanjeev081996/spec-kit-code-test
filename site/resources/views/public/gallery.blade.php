@extends('layouts.app')

@section('title','Gallery')

@section('content')
  <h1 class="h3 mb-3">Gallery</h1>
  <div class="row g-3">
    @forelse($media as $m)
    <div class="col-6 col-md-3">
      <div class="card h-100">
        <img class="card-img-top" alt="{{ $m->alt_text }}" src="{{ asset('storage/'.ltrim($m->file_path,'/')) }}"/>
      </div>
    </div>
    @empty
      <p class="text-muted">No media uploaded yet.</p>
    @endforelse
  </div>
  <div class="mt-3">{{ $media->links() }}</div>
@endsection

