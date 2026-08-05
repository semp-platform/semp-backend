<?php

namespace App\Enums;

enum DocumentWorkflowStage: string
{
    case Preparation = 'preparation';
    case Submission = 'submission';
    case Batch = 'batch';

    public function label(): string
    {
        return match ($this) {
            self::Preparation => 'Preparation',
            self::Submission => 'Submission',
            self::Batch => 'Batch',
        };
    }
}
