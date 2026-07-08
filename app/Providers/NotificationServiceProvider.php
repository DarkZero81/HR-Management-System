<?php

namespace App\Providers;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use App\Notifications\Channels\SmsChannel;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->resolving('channelManager', function (ChannelManager $channelManager) {
            $channelManager->extend('sms', fn() => new SmsChannel());
        });
    }
}