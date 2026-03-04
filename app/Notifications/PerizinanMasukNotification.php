<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PerizinanMasukNotification extends Notification
{
    use Queueable;

    public $perizinan;

    public function __construct($perizinan)
    {
        $this->perizinan = $perizinan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Perizinan baru',
            'message' => $this->perizinan->user->name . ' mengajukan izin',
            'perizinan_id' => $this->perizinan->id
        ];
    }
}