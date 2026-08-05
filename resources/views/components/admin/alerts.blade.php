{{-- Success Message --}}
@if(session('success'))
    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
        {{ session('error') }}
    </div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4">
        <h3 class="mb-2 font-semibold text-red-700">
            Validation Errors
        </h3>

        <ul class="list-disc pl-5 text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
