@csrf

@if(isset($politicalParty))
    @method('PUT')
@endif

<div class="space-y-6">

    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">
            Party Name
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $politicalParty->name ?? '') }}"
            class="w-full rounded-lg border-slate-300">

        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">
            Acronym
        </label>

        <input
            type="text"
            name="acronym"
            value="{{ old('acronym', $politicalParty->acronym ?? '') }}"
            class="w-full rounded-lg border-slate-300">

        @error('acronym')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <input
            id="is_active"
            type="checkbox"
            name="is_active"
            value="1"
            @checked(old('is_active', $politicalParty->is_active ?? true))>

        <label for="is_active" class="text-sm font-medium">
            Active
        </label>
    </div>

    <div class="flex gap-3">

        <button
            type="submit"
            class="rounded-lg bg-emerald-600 px-5 py-2 text-white hover:bg-emerald-700">
            Save
        </button>

        <a
            href="{{ route('admin.political-parties.index') }}"
            class="rounded-lg border px-5 py-2">
            Cancel
        </a>

    </div>

</div>
