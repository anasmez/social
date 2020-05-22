<?php

namespace Tests\Unit\Models;

use App\Models\Like;
use App\Models\Status;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    function a_status_belongs_to_a_user()
    {
        $status=factory(Status::class)->create();

        $this->assertInstanceOf(User::class, $status->user);
    }
    /**
     * @test
     */
    function a_status_has_many_likes()
    {
        $status=factory(Status::class)->create();
        factory(Like::class)->create(['status_id'=>$status->id]);

        $this->assertInstanceOf(Like::class, $status->likes->first());
    }

    /**
     * @test
     */
    function a_status_can_be_liked_and_unliked()
    {
        $status=factory(Status::class)->create();

        $user=factory(User::class)->create();
        $this->actingAs($user);

        $status->like();

        $this->assertEquals(1, $status->fresh()->likes->count());

        $status->unlike();

        $this->assertEquals(0, $status->fresh()->likes->count());
    }
    /**
     * @test
     */
    function a_status_can_be_liked_once()
    {
        $status=factory(Status::class)->create();

        $user=factory(User::class)->create();
        $this->actingAs($user);

        $status->like();

        $this->assertEquals(1, $status->likes->count());

        $status->like();

        $this->assertEquals(1, $status->fresh()->likes->count());
    }
    /**
     * @test
     */
    function a_status_knows_if_it_has_been_liked()
    {
        $status=factory(Status::class)->create();

        $this->assertFalse($status->isLiked());

        $this->actingAs(factory(User::class)->create());

        $status->like();

        $this->assertTrue($status->isLiked());
    }


}
