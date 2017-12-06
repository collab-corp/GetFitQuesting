<?php

namespace App\Notifications\Channels;

use App\Services\DatabaseNotificationDelivery;
use Illuminate\Notifications\Channels\DatabaseChannel as LaravelDatabaseChannel;

class DatabaseChannel extends LaravelDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        $data = $this->getData($notifiable, $notification);

        return DatabaseNotificationDelivery::make([
            'id' => $notification->id,
            'icon' => array_pull($data, 'icon', 'fa fa-bell'),
            'type' => get_class($notification),
            'action_text' => array_pull($data, 'action_text'),
            'action_url' => array_pull($data, 'action_url'),
            'data' => $data,
            'read_at' => null
        ])->sendTo($notifiable);
    }
}
