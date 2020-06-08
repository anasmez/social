<?php

namespace Tests\Unit\Listeners;

use App\Events\ModelLiked;
use App\Models\Status;
use App\Notifications\NewLikeNotification;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendNewLikeNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_sent_when_a_user_receives_a_new_like()
    {
        Notification::fake();

        $statusOwner = factory(User::class)->create();

        $status = factory(Status::class)->create([
            'user_id' => $statusOwner->id,
        ]);

        ModelLiked::dispatch($status);

        Notification::assertSentTo($statusOwner, NewLikeNotification::class);
    }
}
