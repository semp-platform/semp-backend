@csrf

@if(isset($documentType))
    @method('PUT')
@endif

<div class="space-y-8">

    {{-- Basic Information --}}
    <section class="rounded-lg border border-slate-200 bg-slate-50 p-6">

        <h3 class="mb-6 text-lg font-semibold text-slate-900">
            Basic Information
        </h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">
                    Name <span class="text-red-600">*</span>
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    value="{{ old('name', $documentType->name ?? '') }}"
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Code --}}
            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700">
                    Code <span class="text-red-600">*</span>
                </label>

                <input
                    id="code"
                    name="code"
                    type="text"
                    required
                    value="{{ old('code', $documentType->code ?? '') }}"
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category" class="block text-sm font-semibold text-slate-700">
                    Category <span class="text-red-600">*</span>
                </label>

                <select
                    id="category"
                    name="category"
                    required
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >
                    @foreach($categories as $category)
                        <option
                            value="{{ $category->value }}"
                            @selected(old('category', $documentType->category->value ?? '') === $category->value)
                        >
                            {{ $category->label() }}
                        </option>
                    @endforeach
                </select>

                @error('category')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Workflow Stage --}}
            <div>
                <label for="workflow_stage" class="block text-sm font-semibold text-slate-700">
                    Workflow Stage <span class="text-red-600">*</span>
                </label>

                <select
                    id="workflow_stage"
                    name="workflow_stage"
                    required
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >
                    @foreach($workflowStages as $stage)
                        <option
                            value="{{ $stage->value }}"
                            @selected(old('workflow_stage', $documentType->workflow_stage->value ?? '') === $stage->value)
                        >
                            {{ $stage->label() }}
                        </option>
                    @endforeach
                </select>

                @error('workflow_stage')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

    </section>

    {{-- Description --}}
    <section class="rounded-lg border border-slate-200 bg-slate-50 p-6">

        <h3 class="mb-6 text-lg font-semibold text-slate-900">
            Description
        </h3>

        <textarea
            name="description"
            rows="5"
            class="block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
        >{{ old('description', $documentType->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </section>

    {{-- Configuration --}}
    <section class="rounded-lg border border-slate-200 bg-slate-50 p-6">

        <h3 class="mb-6 text-lg font-semibold text-slate-900">
            Configuration
        </h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <div>
                <label for="display_order" class="block text-sm font-semibold text-slate-700">
                    Display Order
                </label>

                <input
                    id="display_order"
                    name="display_order"
                    type="number"
                    min="0"
                    value="{{ old('display_order', $documentType->display_order ?? 0) }}"
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600/20"
                >

                @error('display_order')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-lg border border-slate-300 bg-white p-4">

                <h4 class="mb-4 font-semibold text-slate-800">
                    Options
                </h4>

                <div class="space-y-3">

                    <label class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            name="required"
                            value="1"
                            @checked(old('required', $documentType->required ?? true))
                        >
                        <span>Required document</span>
                    </label>

                    <label class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            name="allow_multiple_versions"
                            value="1"
                            @checked(old('allow_multiple_versions', $documentType->allow_multiple_versions ?? false))
                        >
                        <span>Allow multiple versions</span>
                    </label>

                    <label class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked(old('is_active', $documentType->is_active ?? true))
                        >
                        <span>Active</span>
                    </label>

                </div>

            </div>

        </div>

    </section>

    {{-- Actions --}}
    <div class="flex items-center gap-3 border-t border-slate-200 pt-6">

        <button
            type="submit"
            class="rounded-lg bg-emerald-700 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-800"
        >
            {{ isset($documentType) ? 'Update Document Type' : 'Save Document Type' }}
        </button>

        <a
            href="{{ route('admin.document-types.index') }}"
            class="rounded-lg border border-slate-300 px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
        >
            Cancel
        </a>

    </div>

</div>
