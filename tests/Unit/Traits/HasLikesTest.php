<?php

namespace Tests\Unit\Traits;

use App\Events\ModelLiked;
use App\Events\ModelUnliked;
use App\Models\Like;
use App\Traits\HasLikes;
use App\User;
use Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Schema;
use Tests\TestCase;

class HasLikesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Schema::create('model_with_likes', function ($table) {
            $table->increments('id');
        });
    }

    /**
     * @test
     */
    function a_model_morph_many_likes()
    {
        $model = ModelWithLike::create();

        factory(Like::class)->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model),
        ]);

        $this->assertInstanceOf(Like::class, $model->likes->first());
    }

    /**
     * @test
     */
    function a_model_can_be_liked_and_unliked()
    {
        $model = ModelWithLike::create();

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $model->like();

        $this->assertEquals(1, $model->likes()->count());

        $model->unlike();

        $this->assertEquals(0, $model->likes()->count());
    }

    /**
     * @test
     */
    function a_model_can_be_liked_once()
    {
        $model = ModelWithLike::create();

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $model->like();

        $this->assertEquals(1, $model->likes()->count());

        $model->like();

        $this->assertEquals(1, $model->likes()->count());
    }

    /**
     * @test
     */
    function a_model_knows_if_it_has_been_liked()
    {
        $model = ModelWithLike::create();

        $this->assertFalse($model->isLiked());

        $this->actingAs(factory(User::class)->create());

        $model->like();

        $this->assertTrue($model->isLiked());
    }

    /**
     * @test
     */
    function a_model_knows_how_many_likes_it_has()
    {
        $model = ModelWithLike::create();

        $this->assertEquals(0, $model->likesCount());

        factory(Like::class, 2)->create([
            'likeable_id' => $model->id,          // 1
            'likeable_type' => get_class($model) // App\\Models\\Status
        ]);
        $this->assertEquals(2, $model->likesCount());
    }

    /** @test */
    public function an_event_is_fired_when_a_model_is_liked()
    {
        Event::fake([ModelLiked::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $this->actingAs(factory(User::class)->create());

        $model = new ModelWithLike(['id' => 1]);
        $model->like();

        Event::assertDispatched(ModelLiked::class, function ($event) {
            $this->assertInstanceOf(ModelWithLike::class, $event->model);
            $this->assertEventChannelType('public', $event);
            $this->assertEventChannelName($event->model->eventChannelName(), $event); // "status.1.likes"
            $this->assertDontBroadCastToCurrentUser($event);
            return true;
        });
    }

    /** @test */
    public function an_event_is_fired_when_a_model_is_unliked()
    {
        Event::fake([ModelUnliked::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $this->actingAs(factory(User::class)->create());

        $model = ModelWithLike::create();

        $model->likes()->firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $model->unlike();

        Event::assertDispatched(ModelUnliked::class, function ($event) {
            $this->assertInstanceOf(ModelWithLike::class, $event->model);
            $this->assertEventChannelType('public', $event);
            $this->assertEventChannelName($event->model->eventChannelName(), $event); // "status.1.likes"
            $this->assertDontBroadCastToCurrentUser($event);
            return true;
        });
    }

    /** @test */
    public function can_get_the_event_channel_name()
    {
        $model = new ModelWithLike(['id' => 1]);

        $this->assertEquals(
            "modelwithlikes.1.likes",
            $model->eventChannelName()
        );

    }

}


class ModelWithLike extends Model
{
    use HasLikes;

    public $timestamps = false;

    protected $fillable = ['id'];
}