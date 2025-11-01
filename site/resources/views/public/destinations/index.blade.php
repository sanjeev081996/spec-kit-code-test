@extends('layouts.app')

@section('title','Destinations')

@section('content')
  <h1 class="h3 mb-3">Destinations</h1>
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
      <p class="text-muted">No destinations published yet.</p>
    @endforelse
  </div>
  <div class="mt-3">{{ $destinations->links() }}</div>
@endsection

