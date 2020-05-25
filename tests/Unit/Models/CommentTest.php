<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function a_comment_belongs_to_a_user()
    {
        $comment=factory(Comment::class)->create();

        $this->assertInstanceOf(User::class, $comment->user);
    }
}
