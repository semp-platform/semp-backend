<?php

namespace App\Services\Administration;

use App\Enums\DocumentCategory;
use App\Enums\DocumentWorkflowStage;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DocumentTypeService
{
    /**
     * Get all document types.
     */
    public function all(): Collection
    {
        return DocumentType::ordered()->get();
    }

    /**
     * Get active document types.
     */
    public function active(): Collection
    {
        return DocumentType::query()
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get document type by code.
     */
    public function findByCode(string $code): ?DocumentType
    {
        return DocumentType::query()
            ->where('code', $code)
            ->first();
    }

    /**
     * Get document types by category.
     */
    public function byCategory(DocumentCategory $category): Collection
    {
        return DocumentType::query()
            ->where('category', $category)
            ->ordered()
            ->get();
    }

    /**
     * Get document types by workflow stage.
     */
    public function byWorkflowStage(DocumentWorkflowStage $stage): Collection
    {
        return DocumentType::query()
            ->where('workflow_stage', $stage)
            ->ordered()
            ->get();
    }

    /**
     * Create a document type.
     */
    public function create(array $data): DocumentType
    {
        return DB::transaction(function () use ($data) {
            return DocumentType::create($data);
        });
    }

    /**
     * Update a document type.
     */
    public function update(DocumentType $documentType, array $data): DocumentType
    {
        return DB::transaction(function () use ($documentType, $data) {
            $documentType->update($data);

            return $documentType->fresh();
        });
    }

    /**
     * Activate a document type.
     */
    public function activate(DocumentType $documentType): DocumentType
    {
        $documentType->update([
            'is_active' => true,
        ]);

        return $documentType->fresh();
    }

    /**
     * Deactivate a document type.
     */
    public function deactivate(DocumentType $documentType): DocumentType
    {
        $documentType->update([
            'is_active' => false,
        ]);

        return $documentType->fresh();
    }
}
