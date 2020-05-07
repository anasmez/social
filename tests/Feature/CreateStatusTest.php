<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStatusTest extends TestCase
{
    /**
     * @test
     */
    public function an_authenticated_user_can_create_statuses()
    {
        // 1. Given => Teniendo un usuario autenticado
        $user=factory(User::class)->create();
        $this->actingAs($user);

        // 2. When => Cuando hace un post request a status
        $this->post(route('status.store'), ['body'=>'Mi primer estado']);

        // 3. Then => Entonces veo un nuevo estado en la base de datos
        $this->assertDatabaseHas('statuses', ['body'=>'Mi primer estado']);
    }
}
