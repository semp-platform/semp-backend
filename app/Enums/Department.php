<?php

namespace App\Enums;

enum Department: string
{
    case Party = 'party';

    case ICT = 'ict';

    case EPM = 'epm';

    case Legal = 'legal';

    case Commission = 'commission';

    public function label(): string
    {
        return match ($this) {

            self::Party => 'Political Party',

            self::ICT => 'Information & Communications Technology',

            self::EPM => 'Election & Party Monitoring',

            self::Legal => 'Legal Services',

            self::Commission => 'Commission',

        };
    }
}
