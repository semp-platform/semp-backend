<?php

namespace App\Enums;

enum DocumentCategory: string
{
    case Candidate = 'candidate';
    case OfficialForm = 'official_form';
    case Batch = 'batch';

    public function label(): string
    {
        return match ($this) {
            self::Candidate => 'Candidate',
            self::OfficialForm => 'Official Form',
            self::Batch => 'Batch',
        };
    }
}
