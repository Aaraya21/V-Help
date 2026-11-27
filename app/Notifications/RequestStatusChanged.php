<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Notifications\Notification;

class RequestStatusChanged extends Notification
{
    public function __construct(public ServiceRequest $sr) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        return [
            'id'        => $this->sr->id,
            'ticket_no' => $this->sr->ticket_no,
            'message'   => "คำร้อง {$this->sr->ticket_no} เปลี่ยนสถานะเป็น: {$this->sr->status_label}",
        ];
    }
}
