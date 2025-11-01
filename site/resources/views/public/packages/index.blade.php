@extends('layouts.app')

@section('title','Packages')

@section('content')
  <h1 class="h3 mb-3">Packages</h1>
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
      <p class="text-muted">No packages published yet.</p>
    @endforelse
  </div>
  <div class="mt-3">{{ $packages->links() }}</div>
@endsection

