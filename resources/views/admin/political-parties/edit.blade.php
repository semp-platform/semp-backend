@extends('layouts.app')

@section('title', 'Edit Political Party')

@section('content')

<div class="max-w-3xl">

    <h1 class="mb-6 text-2xl font-bold">
        Edit Political Party
    </h1>

    <form
        method="POST"
        action="{{ route('admin.political-parties.update', $politicalParty) }}">

        @include('admin.political-parties._form')

    </form>

</div>

@endsection
