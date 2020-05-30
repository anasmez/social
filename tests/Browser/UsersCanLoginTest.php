<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Throwable;

class UsersCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @test
     * @throws Throwable
     */
    public function registered_users_can_login()
    {
        factory(User::class)->create([
            'email' => 'anas@email.com'
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'anas@email.com')
                ->type('password', 'secret')
                ->press('#login-btn')
                ->assertPathIs('/')
                ->assertAuthenticated();
        });
    }

    /**
     * @throws Throwable
     * @test
     */
    public function users_cannot_login_with_invalid_information()
    {
        $this->withoutExceptionHandling();

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->press('#login-btn')
                ->assertPathIs('/login')
                ->assertPresent('main#app.py-4 div.container div.row div.col-md-6.mx-auto div');
        });
    }
}
