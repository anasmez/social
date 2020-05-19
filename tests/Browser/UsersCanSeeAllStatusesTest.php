<?php

namespace Tests\Browser;

use App\Models\Status;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanSeeAllStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function users_can_see_all_statuses_on_the_homepage()
    {
        $statuses = factory(Status::class, 3)->create();
        $user=factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($statuses, $user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->waitForText($statuses->first()->body);

            foreach ($statuses as $status){
                $browser->assertSee($status->body);
            }
        });
    }
}