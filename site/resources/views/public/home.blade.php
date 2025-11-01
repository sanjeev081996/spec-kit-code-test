@extends('layouts.app')

@section('title','Home')

@section('content')
  <div class="py-4">
    <h1 class="h3 mb-4">Welcome to {{ config('app.name') }}</h1>

    <h2 class="h5 mt-4">Featured Packages</h2>
    <div class="row g-3">
      @forelse($packages as $pkg)
      <div class="col-md-4" data-testid="package-card">
        <div class="card h-100">
          <div class="card-body">
            <h3 class="h6 card-title"><a href="{{ url('/packages/'.$pkg->slug) }}">{{ $pkg->title }}</a></h3>
            <p class="card-text text-muted">{{ $pkg->summary }}</p>
          </div>
        </div>
      </div>
      @empty
      <p class="text-muted">No packages yet.</p>
      @endforelse
    </div>

    <h2 class="h5 mt-5">Popular Destinations</h2>
    <div class="row g-3">
      @forelse($destinations as $dest)
      <div class="col-md-3" data-testid="destination-card">
        <div class="card h-100">
          <div class="card-body">
            <h3 class="h6 card-title"><a href="{{ url('/destinations/'.$dest->slug) }}">{{ $dest->name }}</a></h3>
            <p class="card-text text-muted">{{ Str::limit($dest->description, 80) }}</p>
          </div>
        </div>
      </div>
      @empty
      <p class="text-muted">No destinations yet.</p>
      @endforelse
    </div>
  </div>
@endsection

