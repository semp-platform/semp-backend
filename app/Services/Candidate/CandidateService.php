<?php

namespace App\Services\Candidate;

use App\Contracts\Identity\IdentityVerificationService;
use App\Models\Candidate\Candidate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CandidateService
{
    public function __construct(
        protected IdentityVerificationService $identityVerification
    ) {}

    public function verifyNin(string $nin): array
    {
        $nin = trim($nin);

        if (! preg_match('/^\d{11}$/', $nin)) {
            throw ValidationException::withMessages([
                'nin' => 'The NIN must contain exactly 11 digits.',
            ]);
        }

        $ninHash = hash('sha256', $nin);

        $existingCandidate = Candidate::query()
            ->where('nin_hash', $ninHash)
            ->first();

        if ($existingCandidate) {
            return [
                'id' => $existingCandidate->id,
                'first_name' => $existingCandidate->first_name,
                'middle_name' => $existingCandidate->middle_name,
                'last_name' => $existingCandidate->last_name,
                'gender' => $existingCandidate->gender,
                'date_of_birth' => $existingCandidate->date_of_birth?->format('Y-m-d'),
                'verified' => true,
            ];
        }

        try {
            $identity = $this->identityVerification->verify($nin);
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'nin' => 'The NIN could not be verified. Please confirm the number and try again.',
            ]);
        }

        return [
            'id' => null,
            'first_name' => $identity['first_name'],
            'middle_name' => $identity['middle_name'] ?? null,
            'last_name' => $identity['last_name'],
            'gender' => $identity['gender'],
            'date_of_birth' => $identity['date_of_birth'],
            'verified' => true,
        ];
    }

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

        try {
            $identity = $this->identityVerification->verify($nin);
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'nin' => 'The NIN could not be verified. Please confirm the number and try again.',
            ]);
        }

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
