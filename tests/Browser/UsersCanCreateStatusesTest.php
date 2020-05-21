<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Throwable;

class UsersCanCreateStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     * @test
     * @return void
     * @throws Throwable
     */
    public function users_can_create_statuses()
    {
        $user=factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                    ->visit('/')
                    ->type('body', 'Mi primer estado') // <input name="body">
                    ->press('#create-status')
                    ->waitForText('Mi primer estado')
                    ->assertSee('Mi primer estado')
                    ->assertSee($user->name)
            ;
        });
    }
}
