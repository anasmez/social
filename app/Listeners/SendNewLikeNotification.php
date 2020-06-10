<?php

namespace App\Listeners;

use App\Events\ModelLiked;
use App\Notifications\NewLikeNotification;

class SendNewLikeNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModelLiked $event
     * @return void
     */
    public function handle(ModelLiked $event)
    {
        $event->model->user->notify(
            new NewLikeNotification($event->model, $event->likeSender)
        );
    }
}
