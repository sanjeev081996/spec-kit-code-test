@extends('layouts.app')

@section('title',$package->title)

@section('content')
  <h1 class="h3 mb-3" data-testid="package-title">{{ $package->title }}</h1>
  @if($package->summary)
    <p class="lead">{{ $package->summary }}</p>
  @endif
  @if($package->description)
    <div class="mt-3">{!! nl2br(e($package->description)) !!}</div>
  @endif
  <dl class="row mt-3">
    @if($package->duration)
      <dt class="col-sm-3">Duration</dt>
      <dd class="col-sm-9">{{ $package->duration }}</dd>
    @endif
    @if($package->price_from)
      <dt class="col-sm-3">Price from</dt>
      <dd class="col-sm-9">₹{{ number_format($package->price_from, 2) }}</dd>
    @endif
  </dl>
@endsection

