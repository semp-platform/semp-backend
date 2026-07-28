<?php

namespace App\Services\Identity;

use App\Contracts\Identity\IdentityVerificationService;
use RuntimeException;

class MockIdentityVerificationService implements IdentityVerificationService
{
    private array $identities = [
        '11111111111' => [
            'first_name' => 'Ade',
            'middle_name' => 'Oluwaseun',
            'last_name' => 'Adebayo',
            'gender' => 'Male',
            'date_of_birth' => '1985-06-15',
        ],

        '22222222222' => [
            'first_name' => 'Ngozi',
            'middle_name' => null,
            'last_name' => 'Okafor',
            'gender' => 'Female',
            'date_of_birth' => '1990-11-03',
        ],
    ];

    public function verify(string $nin): array
    {
        if (! isset($this->identities[$nin])) {
            throw new RuntimeException('NIN could not be verified.');
        }

        return $this->identities[$nin];
    }
}
