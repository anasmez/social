<?php

namespace Tests\Feature;

use App\Events\CommentCreated;
use App\Http\Resources\CommentResource;
use App\Http\Resources\StatusResource;
use App\Models\Comment;
use App\Models\Status;
use App\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateCommentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function guest_users_cannot_create_comments()
    {
        $status = factory(Status::class)->create();
        $comment = ['body' => 'Mi primer comentario'];

        $response = $this->postJson(route('statuses.comments.store', $status), $comment);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function authenticated_users_can_comment_statuses()
    {
        $status = factory(Status::class)->create();
        $user = factory(User::class)->create();
        $comment = ['body' => 'Mi primer comentario'];

        $response = $this->actingAs($user)
            ->postJson(route('statuses.comments.store', $status), $comment);

        $response->assertJson([
            'data' => ['body' => $comment['body']]
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'status_id' => $status->id,
            'body' => $comment['body']
        ]);
    }

    /**
     * @test
     */
    public function a_comment_requires_body()
    {
        $status = factory(Status::class)->create();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.comments.store', $status), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }

    /** @test */
    public function an_event_is_fired_when_a_comment_is_created()
    {
        Event::fake([CommentCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $status = factory(Status::class)->create();
        $user = factory(User::class)->create();
        $comment = ['body' => 'Mi primer comentario'];

        $this->actingAs($user)->postJson(route('statuses.comments.store', $status), $comment);

        Event::assertDispatched(CommentCreated::class, function ($commentStatusEvent) {
            $this->assertInstanceOf(ShouldBroadcast::class, $commentStatusEvent);
            $this->assertInstanceOf(CommentResource::class, $commentStatusEvent->comment);
            $this->assertInstanceOf(Comment::class, $commentStatusEvent->comment->resource);
            $this->assertEquals(Comment::first()->id, $commentStatusEvent->comment->id);
            $this->assertEquals(
                'socket-id',
                $commentStatusEvent->socket,
                'The event ' . get_class($commentStatusEvent) . ' must call the method "dontBroadcastToCurrentUser" in the constructor.'
            );
            return true;
        });
    }

}
