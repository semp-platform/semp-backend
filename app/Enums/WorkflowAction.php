<?php

namespace App\Enums;

enum WorkflowAction: string
{
    case Submitted = 'submitted';

    case Received = 'received';

    case Assigned = 'assigned';

    case Forwarded = 'forwarded';

    case QueryRaised = 'query_raised';

    case QueryResponded = 'query_responded';

    case Returned = 'returned';

    case Approved = 'approved';

    case Rejected = 'rejected';

    case Published = 'published';

    public function label(): string
    {
        return match ($this) {

            self::Submitted => 'Submitted',

            self::Received => 'Received',

            self::Assigned => 'Assigned',

            self::Forwarded => 'Forwarded',

            self::QueryRaised => 'Query Raised',

            self::QueryResponded => 'Query Responded',

            self::Returned => 'Returned',

            self::Approved => 'Approved',

            self::Rejected => 'Rejected',

            self::Published => 'Published',

        };
    }
}
