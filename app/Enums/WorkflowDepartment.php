<?php

namespace App\Enums;

enum WorkflowDepartment: string
{
    case PARTY = 'party';
    case ICT = 'ict';
    case EPM = 'epm';
    case LEGAL = 'legal';
    case COMMISSION = 'commission';

    public function label(): string
    {
        return match ($this) {
            self::PARTY => 'Political Party',
            self::ICT => 'ICT',
            self::EPM => 'Election & Party Monitoring',
            self::LEGAL => 'Legal Services',
            self::COMMISSION => 'Commission',
        };
    }
}
