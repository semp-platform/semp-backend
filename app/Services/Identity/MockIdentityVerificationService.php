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

    '33333333333' => [
        'first_name' => 'Olumide',
        'middle_name' => 'Joseph',
        'last_name' => 'Adeyemi',
        'gender' => 'Male',
        'date_of_birth' => '1978-02-21',
    ],

    '44444444444' => [
        'first_name' => 'Yetunde',
        'middle_name' => 'Abosede',
        'last_name' => 'Ogunleye',
        'gender' => 'Female',
        'date_of_birth' => '1988-09-14',
    ],

    '55555555555' => [
        'first_name' => 'Ibrahim',
        'middle_name' => 'Adewale',
        'last_name' => 'Balogun',
        'gender' => 'Male',
        'date_of_birth' => '1982-12-05',
    ],

    '66666666666' => [
        'first_name' => 'Temilade',
        'middle_name' => null,
        'last_name' => 'Sowunmi',
        'gender' => 'Female',
        'date_of_birth' => '1993-04-27',
    ],

    '77777777777' => [
        'first_name' => 'Kayode',
        'middle_name' => 'Michael',
        'last_name' => 'Osinowo',
        'gender' => 'Male',
        'date_of_birth' => '1975-08-11',
    ],

    '88888888888' => [
        'first_name' => 'Aminat',
        'middle_name' => 'Olubukola',
        'last_name' => 'Lawal',
        'gender' => 'Female',
        'date_of_birth' => '1987-01-19',
    ],

    '99999999999' => [
        'first_name' => 'Babajide',
        'middle_name' => null,
        'last_name' => 'Akinwale',
        'gender' => 'Male',
        'date_of_birth' => '1980-10-30',
    ],

    '12345678901' => [
        'first_name' => 'Funmilayo',
        'middle_name' => 'Grace',
        'last_name' => 'Oladipo',
        'gender' => 'Female',
        'date_of_birth' => '1991-07-08',
    ],

    '10987654321' => [
        'first_name' => 'Samuel',
        'middle_name' => 'Oluwatobi',
        'last_name' => 'Fashola',
        'gender' => 'Male',
        'date_of_birth' => '1984-03-17',
    ],

    '13579135791' => [
        'first_name' => 'Kehinde',
        'middle_name' => 'Elizabeth',
        'last_name' => 'Adekunle',
        'gender' => 'Female',
        'date_of_birth' => '1989-05-23',
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
