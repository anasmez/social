<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_users_can_not_create_statuses()
    {
        $response = $this->post(route('statuses.store'), ['body'=>'Mi primer estado']);

        $response->assertRedirect('login');
    }
    /**
     * @test
     */
    public function an_authenticated_user_can_create_statuses()
    {
        $this->withoutExceptionHandling();
        // 1. Given => Teniendo un usuario autenticado
        $user=factory(User::class)->create();
        $this->actingAs($user);

        // 2. When => Cuando hace un post request a status
        $response = $this->post(route('statuses.store'), ['body'=>'Mi primer estado']);

        $response->assertJson([
            'body'=>'Mi primer estado',
        ]);

        // 3. Then => Entonces veo un nuevo estado en la base de datos
        $this->assertDatabaseHas('statuses', [
            'user_id'=>$user->id,
            'body'=>'Mi primer estado']);
    }
}
