<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Administration\DocumentTypeService;
use App\Http\Requests\Admin\StoreDocumentTypeRequest;
use App\Models\DocumentType;
use App\Enums\DocumentCategory;
use App\Enums\DocumentWorkflowStage;
 use App\Http\Requests\Admin\UpdateDocumentTypeRequest;



class DocumentTypeController extends Controller
{

    public function __construct(
        private readonly DocumentTypeService $documentTypeService
    ) {}

   public function index()
{
    $this->authorize('viewAny', DocumentType::class);

    $documentTypes = $this->documentTypeService->all();

    return view('admin.document-types.index', compact('documentTypes'));
}
    /**
     * Show the form for creating a new resource.
     */

public function create()
{
    $this->authorize('create', DocumentType::class);

    return view('admin.document-types.create', [
        'categories' => DocumentCategory::cases(),
        'workflowStages' => DocumentWorkflowStage::cases(),
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentTypeRequest $request)
{
    $this->authorize('create', DocumentType::class);

    $this->documentTypeService->create(
        $request->validated()
    );

    return redirect()
        ->route('admin.document-types.index')
        ->with('success', 'Document type created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
{
    //
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
{
    $this->authorize('update', $documentType);

    return view('admin.document-types.edit', [
        'documentType' => $documentType,
        'categories' => DocumentCategory::cases(),
        'workflowStages' => DocumentWorkflowStage::cases(),
    ]);
}

    /**
     * Update the specified resource in storage.
     */


public function update(
    UpdateDocumentTypeRequest $request,
    DocumentType $documentType
) {
    $this->authorize('update', $documentType);

    $this->documentTypeService->update(
        $documentType,
        $request->validated()
    );

    return redirect()
        ->route('admin.document-types.index')
        ->with('success', 'Document type updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */

    public function activate(DocumentType $documentType)
{
    $this->authorize('activate', $documentType);

    $this->documentTypeService->activate($documentType);

    return back()->with('success', 'Document type activated successfully.');
}
public function deactivate(DocumentType $documentType)
{
    $this->authorize('deactivate', $documentType);

    $this->documentTypeService->deactivate($documentType);

    return back()->with('success', 'Document type deactivated successfully.');
}
}
