<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PublicContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    /**
     * Display a listing of public content.
     */
    public function index(Request $request): View
    {
        $query = PublicContent::query()
            ->with(['creator', 'updater'])
            ->latest();

        if ($request->filled('type')) {
            $query->ofType($request->string('type')->toString());
        }

        if ($request->filled('status')) {
            match ($request->string('status')->toString()) {
                'published' => $query->where('is_published', true),
                'draft' => $query->where('is_published', false),
                default => null,
            };
        }

        $contents = $query->paginate(20)->withQueryString();

        return view('web.public-content.index', compact('contents'));
    }

    /**
     * Show the form for creating new public content.
     */
    public function create(): View
    {
        $types = $this->contentTypes();

        return view('web.public-content.create', compact('types'));
    }

    /**
     * Store newly created public content.
     */
   public function store(Request $request): RedirectResponse
{
    $validated = $this->validateContent($request);

    $validated['is_home_service'] = $request->boolean('is_home_service');

    $validated['slug'] = $this->uniqueSlug(
        $validated['slug'] ?? $validated['title']
    );

    if ($request->hasFile('image')) {
        $validated['image_path'] = $request->file('image')
            ->store('public-content/images', 'public');
    }

    if ($request->hasFile('attachment')) {
        $validated['attachment_path'] = $request->file('attachment')
            ->store('public-content/attachments', 'public');
    }

    unset($validated['image'], $validated['attachment']);

    $validated['created_by'] = Auth::id();
    $validated['updated_by'] = Auth::id();

    PublicContent::create($validated);

    return redirect()
        ->route('web.public-content.index')
        ->with('success', 'Public content created successfully.');
}

    /**
     * Display the specified public content.
     */
    public function show(PublicContent $publicContent): View
    {
        $publicContent->load(['creator', 'updater']);

        return view('web.public-content.show', compact('publicContent'));
    }

    /**
     * Show the form for editing public content.
     */
    public function edit(PublicContent $publicContent): View
    {
        $types = $this->contentTypes();

        return view('web.public-content.edit', compact('publicContent', 'types'));
    }

    /**
     * Update the specified public content.
     */
    public function update(
        Request $request,
        PublicContent $publicContent
    ): RedirectResponse {
        $validated = $this->validateContent($request, $publicContent);
        $validated['is_home_service'] = $request->boolean('is_home_service');

        $validated['slug'] = $this->uniqueSlug(
            $validated['slug'] ?? $validated['title'],
            $publicContent->id
        );

        if ($request->hasFile('image')) {
            if ($publicContent->image_path) {
                Storage::disk('public')->delete($publicContent->image_path);
            }

            $validated['image_path'] = $request->file('image')
                ->store('public-content/images', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($publicContent->attachment_path) {
                Storage::disk('public')->delete($publicContent->attachment_path);
            }

            $validated['attachment_path'] = $request->file('attachment')
                ->store('public-content/attachments', 'public');
        }

        unset($validated['image'], $validated['attachment']);

        $validated['updated_by'] = Auth::id();

        $publicContent->update($validated);

        return redirect()
            ->route('web.public-content.index')
            ->with('success', 'Public content updated successfully.');
    }

    /**
     * Remove the specified public content.
     */
    public function destroy(PublicContent $publicContent): RedirectResponse
    {
        if ($publicContent->image_path) {
            Storage::disk('public')->delete($publicContent->image_path);
        }

        if ($publicContent->attachment_path) {
            Storage::disk('public')->delete($publicContent->attachment_path);
        }

        $publicContent->delete();

        return redirect()
            ->route('web.public-content.index')
            ->with('success', 'Public content deleted successfully.');
    }

    /**
     * Publish public content.
     */
    public function publish(PublicContent $publicContent): RedirectResponse
    {
        $publicContent->update([
            'is_published' => true,
            'published_at' => $publicContent->published_at ?? now(),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Content published successfully.');
    }

    /**
     * Unpublish public content.
     */
    public function unpublish(PublicContent $publicContent): RedirectResponse
    {
        $publicContent->update([
            'is_published' => false,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Content unpublished successfully.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(PublicContent $publicContent): RedirectResponse
    {
        $publicContent->update([
            'is_featured' => ! $publicContent->is_featured,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Featured status updated.');
    }

    /**
     * Toggle ticker status.
     */
    public function toggleTicker(PublicContent $publicContent): RedirectResponse
    {
        $publicContent->update([
            'is_ticker' => ! $publicContent->is_ticker,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Ticker status updated.');
    }

    /**
     * Validation rules for public content.
     */
   private function validateContent(
    Request $request,
    ?PublicContent $publicContent = null
): array {
    $slugRules = [
        'nullable',
        'string',
        'max:255',
    ];

    if ($publicContent) {
        $slugRules[] = 'unique:public_contents,slug,' . $publicContent->id;
    } else {
        $slugRules[] = 'unique:public_contents,slug';
    }

    return $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'slug' => $slugRules,
        'type' => [
            'required',
            'string',
            'in:' . implode(',', $this->contentTypes()),
        ],
        'summary' => ['nullable', 'string'],
        'content' => ['required', 'string'],
        'image' => ['nullable', 'image', 'max:5120'],
        'attachment' => ['nullable', 'file', 'max:10240'],
        'published_at' => ['nullable', 'date'],
        'display_until' => ['nullable', 'date', 'after_or_equal:published_at'],
        'is_published' => ['nullable', 'boolean'],
        'is_featured' => ['nullable', 'boolean'],
        'is_ticker' => ['nullable', 'boolean'],
        'is_home_service' => ['nullable', 'boolean'],
        'sort_order' => ['nullable', 'integer', 'min:0'],
    ]);
}
    /**
     * Return the supported public content types.
     */
    private function contentTypes(): array
    {
        return [
            PublicContent::TYPE_NEWS,
            PublicContent::TYPE_NOTICE,
            PublicContent::TYPE_ANNOUNCEMENT,
            PublicContent::TYPE_PRESS_RELEASE,
        ];
    }

    /**
     * Generate a unique slug.
     */
    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = 'public-content';
        }

        $original = $slug;
        $counter = 1;

        while (
            PublicContent::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
