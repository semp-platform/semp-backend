<?php

namespace Database\Seeders;

use App\Enums\DocumentCategory;
use App\Enums\DocumentWorkflowStage;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $documentTypes = [

            // Candidate Documents

            [
                'name' => 'Passport Photograph',
                'code' => 'passport',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Recent passport photograph of the candidate.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 1,
            ],

            [
                'name' => 'National Identification Number (NIN) Slip',
                'code' => 'nin_slip',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Official NIN slip issued by NIMC.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 2,
            ],

            [
                'name' => 'Permanent Voter Card',
                'code' => 'pvc',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Permanent Voter Card (PVC).',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 3,
            ],

            [
                'name' => 'Academic Qualification',
                'code' => 'academic_qualification',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Academic qualification certificate(s).',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 4,
            ],

            [
                'name' => 'Tax Clearance Certificate',
                'code' => 'tax_clearance',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Valid tax clearance certificate.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 5,
            ],

            [
                'name' => 'Certificate of Origin',
                'code' => 'certificate_of_origin',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Certificate of Origin.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 6,
            ],

            [
                'name' => 'Birth Certificate / Declaration of Age',
                'code' => 'birth_certificate',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Birth certificate or declaration of age.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 7,
            ],

            [
                'name' => 'Party Membership Card',
                'code' => 'party_membership_card',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Political party membership card.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 8,
            ],

            [
                'name' => 'Court Documents',
                'code' => 'court_documents',
                'category' => DocumentCategory::Candidate,
                'workflow_stage' => DocumentWorkflowStage::Submission,
                'description' => 'Court documents where applicable.',
                'required' => false,
                'allow_multiple_versions' => true,
                'display_order' => 9,
            ],

            // Official Forms

            [
                'name' => 'CF001 Candidate Information Form',
                'code' => 'cf001',
                'category' => DocumentCategory::OfficialForm,
                'workflow_stage' => DocumentWorkflowStage::Preparation,
                'description' => 'Official OGSIEC Candidate Information Form.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 10,
            ],

            [
                'name' => 'CF002 Submission Form',
                'code' => 'cf002',
                'category' => DocumentCategory::OfficialForm,
                'workflow_stage' => DocumentWorkflowStage::Batch,
                'description' => 'Official OGSIEC Submission Form.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 11,
            ],

            [
                'name' => 'EC4D Nomination Form',
                'code' => 'ec4d',
                'category' => DocumentCategory::OfficialForm,
                'workflow_stage' => DocumentWorkflowStage::Preparation,
                'description' => 'Official OGSIEC Nomination Form.',
                'required' => true,
                'allow_multiple_versions' => true,
                'display_order' => 12,
            ],
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::updateOrCreate(
                ['code' => $documentType['code']],
                $documentType
            );
        }
    }
}
