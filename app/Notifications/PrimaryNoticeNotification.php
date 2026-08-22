<?php

namespace App\Notifications;

use App\Models\PartyPrimaryNotice;
use App\Models\PrimaryEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PrimaryNoticeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $type,
        public PartyPrimaryNotice $notice,
        public ?PrimaryEvent $event = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->type === 'commissioner_review') {
            return [
                'type' => 'party_primary_notice_review',
                'title' => 'New Party Primary Notice',
                'message' => 'A party primary notice has been submitted and requires your review.',
                'notice_id' => $this->notice->id,
                'event_id' => null,
                'url' => route(
                    'staff.commissioner.primary-notices.show',
                    $this->notice
                ),
            ];
        }

        return [
            'type' => 'primary_monitoring_required',
            'title' => 'Primary Monitoring Required',
            'message' => 'An approved party primary notice requires EPM monitoring.',
            'notice_id' => $this->notice->id,
            'event_id' => $this->event?->id,
            'url' => route(
                'staff.epm.primary-monitoring.show',
                $this->event
            ),
        ];
    }
}
