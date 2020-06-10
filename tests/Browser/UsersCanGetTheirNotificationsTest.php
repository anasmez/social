<?php

namespace Tests\Browser;

use App\Models\Status;use App\User;use Illuminate\Foundation\Testing\DatabaseMigrations;use Illuminate\Notifications\DatabaseNotification;use Laravel\Dusk\Browser;use Tests\DuskTestCase;use Throwable;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @return void
     * @throws Throwable
     */
    public function user_can_see_their_notifications_in_the_nav_bar()
    {
        $user = factory(User::class)->create();
        $status = factory(Status::class)->create();

        $notification = factory(DatabaseNotification::class)->create([
            'notifiable_id' => $user->id,
            'data' => [
                'message' => 'Has recibido un like',
                'link' => route('statuses.show', $status),
            ],
        ]);

        $this->browse(function (Browser $browser) use ($status, $notification, $user) {
            $browser->loginAs($user)
                ->visit('/')
                ->resize(1024, 768)
                ->click('@notifications')
                ->waitForText('Has recibido un like')
                ->assertSee('Has recibido un like')
                ->click("@{$notification->id}")
                ->assertUrlIs($status->path())

                ->click('@notifications')
                ->waitFor("@mark-as-read-{$notification->id}")
                ->press("@mark-as-read-{$notification->id}")
                ->waitFor("@mark-as-unread-{$notification->id}")
                ->assertMissing("@mark-as-read-{$notification->id}")

                ->press("@mark-as-unread-{$notification->id}")
                ->waitFor("@mark-as-read-{$notification->id}")
                ->assertMissing("@mark-as-unread-{$notification->id}");
        });
    }
}
