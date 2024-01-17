<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\User;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_can_not_add_replies(): void
    {
        $this->withExceptionHandling()
            ->post('/threads/some-thread/1/replies', [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads(): void
    {

        $this->signIn();

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function reply_requires_a_body(): void
    {
        $this->signIn();

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->make(['body' => NULL]);

        $this->withExceptionHandling()
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
