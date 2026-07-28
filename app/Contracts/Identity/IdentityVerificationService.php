<?php

namespace App\Contracts\Identity;

interface IdentityVerificationService
{
    public function verify(string $nin): array;
}
