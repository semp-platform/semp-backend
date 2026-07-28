<?php

namespace App\Services\Candidate;

use App\Contracts\Identity\IdentityVerificationService;
use App\Models\Candidate\Candidate;
use Illuminate\Support\Facades\Crypt;

class CandidateService
{
    public function __construct(
        protected IdentityVerificationService $identityVerification
    ) {}

    public function findOrCreateFromNin(string $nin): Candidate
    {
        $nin = trim($nin);

        $ninHash = hash('sha256', $nin);

        $existingCandidate = Candidate::query()
            ->where('nin_hash', $ninHash)
            ->first();

        if ($existingCandidate) {
            return $existingCandidate;
        }

        $identity = $this->identityVerification->verify($nin);

        return Candidate::create([
            'nin_encrypted' => Crypt::encryptString($nin),
            'nin_hash' => $ninHash,
            'first_name' => $identity['first_name'],
            'middle_name' => $identity['middle_name'] ?? null,
            'last_name' => $identity['last_name'],
            'gender' => $identity['gender'],
            'date_of_birth' => $identity['date_of_birth'],
            'nin_verified_at' => now(),
            'is_active' => true,
        ]);
    }
}
