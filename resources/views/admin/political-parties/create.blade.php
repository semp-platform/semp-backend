@extends('layouts.app')

@section('title', 'Register Political Party')

@section('content')

<div class="max-w-3xl">

    <h1 class="mb-6 text-2xl font-bold">
        Register Political Party
    </h1>

    <form
        method="POST"
        action="{{ route('admin.political-parties.store') }}">

        @include('admin.political-parties._form')

    </form>

</div>

@endsection
