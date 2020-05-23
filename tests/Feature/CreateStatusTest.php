<?php

namespace Tests\Feature;

use App\User;
use Symfony\Component\Mime\Header\IdentificationHeader;
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
        $response = $this->postJson(route('statuses.store'), ['body'=>'Mi primer estado']);

        $response->assertStatus(401);
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
            'data'=>['body'=>'Mi primer estado'],
        ]);

        // 3. Then => Entonces veo un nuevo estado en la base de datos
        $this->assertDatabaseHas('statuses', [
            'user_id'=>$user->id,
            'body'=>'Mi primer estado']);
    }

    /**
     * @test
     */
    public function a_status_requires_body()
    {
        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->postJson(route('statuses.store'), ['body'=>'']);
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors'=>['body']
        ]);
    }
    /**
     * @test
     */
    public function a_status_body_requires_a_minimum_length()
    {
        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->postJson(route('statuses.store'), ['body'=>'asdf']);
        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors'=>['body']
        ]);
    }
}
