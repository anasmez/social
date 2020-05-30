<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Throwable;

class UsersCanRegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws Throwable
     * @test
     */
    public function users_cannot_register_with_invalid_information()
    {
        $this->withoutExceptionHandling();

        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->press('@register-btn')
                ->assertPathIs('/register')
                ->assertPresent('main#app.py-4 div.container div.row div.col-md-6.mx-auto div');
        });
    }

    /**
     * @throws Throwable
     * @test
     */
    public function users_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'AnasMeziani')
                ->type('first_name', 'Anas')
                ->type('last_name', 'Meziani')
                ->type('email', 'anas@email.com')
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press('@register-btn')
                ->assertPathIs('/')
                ->assertAuthenticated();
        });
    }


}
