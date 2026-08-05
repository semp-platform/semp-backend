<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reference\CandidateChangeReason;

class CandidateChangeReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [

            /*
            |--------------------------------------------------------------------------
            | Candidate Withdrawal
            |--------------------------------------------------------------------------
            */

            [
                'change_type' => 'withdrawal',
                'code' => 'VOLUNTARY',
                'name' => 'Voluntary Withdrawal',
                'requires_document' => false,
            ],

            [
                'change_type' => 'withdrawal',
                'code' => 'DEATH',
                'name' => 'Death of Candidate',
                'requires_document' => true,
            ],

            [
                'change_type' => 'withdrawal',
                'code' => 'COURT',
                'name' => 'Court Order / Judgment',
                'requires_document' => true,
            ],

            [
                'change_type' => 'withdrawal',
                'code' => 'MEDICAL',
                'name' => 'Medical Incapacity',
                'requires_document' => true,
            ],

            [
                'change_type' => 'withdrawal',
                'code' => 'PARTY',
                'name' => 'Party Decision',
                'requires_document' => false,
            ],

            [
                'change_type' => 'withdrawal',
                'code' => 'DISCIPLINE',
                'name' => 'Party Disciplinary Action',
                'requires_document' => true,
            ],

            [
                'change_type' => 'withdrawal',
                'code' => 'OTHER',
                'name' => 'Other',
                'requires_document' => false,
            ],

        ];

        foreach ($reasons as $reason) {

            CandidateChangeReason::updateOrCreate(
                [
                    'change_type' => $reason['change_type'],
                    'code' => $reason['code'],
                ],
                [
                    'name' => $reason['name'],
                    'requires_document' => $reason['requires_document'],
                    'is_active' => true,
                ]
            );

        }
    }
}
