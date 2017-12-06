<?php

namespace App\Services;

use App\Team;

class DatabaseNotificationDelivery
{
    /**
     * The notification message
     *
     * @var array
     */
    protected $message;

    /**
     * Create a DatabaseNotificationDelivery instance.
     *
     * @param array $message
     */
    public function __construct($notification)
    {
        $this->message = $message;
    }

    /**
     * Named constructor.
     *
     * @param  array $message
     * @return DatabaseNotificationDelivery
     */
    public static function make($message)
    {
        return new static($notification);
    }

    /**
     * Send the notification, delivering it to the notifiable(s).
     *
     * @param  \App\User | \App\Team  $notifiable
     * @return \Illuminate\Database\Eloquent\Collection | \App\User
     */
    public function sendTo($notifiable)
    {
        if ($notifiable instanceof Team) {
            return $this->toTeam($notifiable);
        }

        return $this->toUser($notifiable);
    }

    /**
     * Deliver the notification to all Team members.
     *
     * @param  \App\Team $team
     * @return \Illuminate\Database\Eloquent\Collection<\Illuminate\Notifications\DatabaseNotification>
     */
    public function toTeam($team)
    {
        return $team->users->map("toUser");
    }

    /**
     * Deliver the notification to a User.
     *
     * @param  \App\User $user
     * @return \Illuminate\Notifications\DatabaseNotification
     */
    public function toUser($user)
    {
        return $user->notifications->create($this->message);
    }
}
