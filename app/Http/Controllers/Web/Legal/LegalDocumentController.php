<?php

namespace App\Http\Controllers\Web\Legal;

use App\Http\Controllers\Controller;
use App\Models\LegalDocument;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LegalDocumentController extends Controller
{
    /**
     * Display the shared legal document mailbox.
     */
    public function index(): View
    {
        $documents = LegalDocument::query()
            ->with('uploadedBy')
            ->latest('uploaded_at')
            ->latest('id')
            ->paginate(20);

        return view('staff.legal.documents.index', [
            'documents' => $documents,
        ]);
    }

    /**
 * Display legal documents for Commissioner review.
 */
public function commissionerIndex(): View
{
    $documents = LegalDocument::query()
        ->with('uploadedBy')
        ->latest('uploaded_at')
        ->latest('id')
        ->paginate(20);

    return view('staff.commissioner.documents.index', [
        'documents' => $documents,
    ]);
}
    /**
     * Upload a legal document.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'document_type' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'document' => [
                'required',
                'file',
                'max:20480',
                'mimes:pdf,jpg,jpeg,png',
            ],
        ]);

        $file = $request->file('document');

        $storedName = $file->hashName();

        $path = $file->storeAs(
            'legal-documents',
            $storedName,
            'private'
        );

        LegalDocument::create([
            'title' => $validated['title'],
            'document_type' => $validated['document_type'],
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'disk' => 'private',
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
            'uploaded_at' => now(),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with(
            'success',
            'Legal document uploaded successfully.'
        );
    }

    /**
     * View a legal document in the browser.
     */
    public function view(
        LegalDocument $legalDocument
    ) {
        abort_unless(
            Storage::disk($legalDocument->disk)
                ->exists($legalDocument->path),
            404
        );

        return Storage::disk($legalDocument->disk)->response(
            $legalDocument->path,
            $legalDocument->original_name,
            [
                'Content-Type' => $legalDocument->mime_type,
                'Content-Disposition' =>
                    'inline; filename="' .
                    addslashes($legalDocument->original_name) .
                    '"',
            ]
        );
    }

    /**
     * Download a legal document.
     */
    public function download(
        LegalDocument $legalDocument
    ) {
        abort_unless(
            Storage::disk($legalDocument->disk)
                ->exists($legalDocument->path),
            404
        );

        return Storage::disk($legalDocument->disk)->download(
            $legalDocument->path,
            $legalDocument->original_name
        );
    }
}
