<?php

namespace NotificationChannels\Discord;

use Illuminate\Notifications\Notification;

class DiscordChannel
{
    /**
     * @var Discord
     */
    protected $discord;

    /**
     * DiscordChannel constructor.
     *
     * @param Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return array
     *
     * @throws \NotificationChannels\Discord\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$channel = $notifiable->routeNotificationFor('discord')) {
            return;
        }

        $message = $notification->toDiscord($notifiable);

        return $this->discord->send($channel, [
            'content' => $message->body,
            'embed'   => $message->embed,
        ]);
    }
}