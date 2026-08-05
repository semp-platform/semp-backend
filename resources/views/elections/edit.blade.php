@extends('layouts.app')

@section('content')

@include('elections.partials.form', [
    'action' => route('elections.update', $election),
    'method' => 'PUT',
    'election' => $election,
])

@endsection
