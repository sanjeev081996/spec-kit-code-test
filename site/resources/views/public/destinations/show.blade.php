@extends('layouts.app')

@section('title',$destination->name)

@section('content')
  <h1 class="h3 mb-3" data-testid="destination-title">{{ $destination->name }}</h1>
  @if($destination->description)
    <div class="mt-3">{!! nl2br(e($destination->description)) !!}</div>
  @endif
@endsection

