<?php

namespace Tests\Browser;

use App\Models\Status;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Throwable;

class UsersCanSeeProfilesTest extends DuskTestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    /** @test
     * @throws Throwable
     */
    public function users_can_see_profiles()
    {
        $user = factory(User::class)->create();
        $statuses = factory(Status::class, 2)->create(['user_id' => $user->id]);
        $otherStatuses = factory(Status::class)->create();

        $this->browse(function (Browser $browser) use ($otherStatuses, $statuses, $user) {
            $browser->visit("/@{$user->name}")
                ->assertSee($user->name)
                ->waitForText($statuses->first()->body)
                ->assertSee($statuses->first()->body)
                ->assertSee($statuses->last()->body)
                ->assertDontSee($otherStatuses->body);
        });
    }
}
