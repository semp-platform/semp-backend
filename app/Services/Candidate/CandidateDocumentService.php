<?php

namespace App\Services\Candidate;

use App\Models\Candidate\Candidate;
use App\Models\Candidate\CandidateDocument;
use App\Models\DocumentType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidateDocumentService
{
    /**
     * Upload or replace a candidate document.
     */
    public function upload(
        Candidate $candidate,
        DocumentType $documentType,
        UploadedFile $file
    ): CandidateDocument {

        return DB::transaction(function () use (
            $candidate,
            $documentType,
            $file
        ) {

            $document = CandidateDocument::firstWhere([
                'candidate_id' => $candidate->id,
                'document_type_id' => $documentType->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Delete previous file if replacing
            |--------------------------------------------------------------------------
            */

            if (
                $document &&
                Storage::disk($document->disk)->exists($document->path)
            ) {

                Storage::disk($document->disk)
                    ->delete($document->path);

            }

            /*
            |--------------------------------------------------------------------------
            | Store new file
            |--------------------------------------------------------------------------
            */

            $storedPath = $file->store(

                'candidates/'.$candidate->id,

                'private'

            );

            /*
            |--------------------------------------------------------------------------
            | Create or update record
            |--------------------------------------------------------------------------
            */

            return CandidateDocument::updateOrCreate(

                [

                    'candidate_id' => $candidate->id,

                    'document_type_id' => $documentType->id,

                ],

                [

                    'original_name' => $file->getClientOriginalName(),

                    'stored_name' => basename($storedPath),

                    'disk' => 'private',

                    'path' => $storedPath,

                    'mime_type' => $file->getMimeType(),

                    'file_size' => $file->getSize(),

                    'uploaded_by' => auth()->id(),

                    'uploaded_at' => now(),

                    'verified_by' => null,

                    'verified_at' => null,

                    'remarks' => null,

                ]

            );

        });

    }

    /**
     * Delete a candidate document.
     */
    public function delete(
        CandidateDocument $document
    ): void {

        if (
            Storage::disk($document->disk)
                ->exists($document->path)
        ) {

            Storage::disk($document->disk)
                ->delete($document->path);

        }

        $document->delete();

    }
}
