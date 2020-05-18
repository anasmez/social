<?php

namespace Tests\Feature;

use App\Models\Status;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListStatusesTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @test
     */
    public function can_get_all_statuses()
    {
        $this->withoutExceptionHandling();
        $statuses=factory(Status::class, 4)->create();
        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->getJson(route('statuses.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'total'=>4
        ]);

        $response->assertJsonStructure([
            'data', 'total', 'first_page_url', 'last_page_url'
        ]);
        $this->assertEquals(
            $response->json('data')[0]['id'],
            $statuses->last()->id
        );
    }
}
