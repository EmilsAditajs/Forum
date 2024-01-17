<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Reply;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Activities;

class ManageThreadsTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function guest_can_not_create_a_thread(): void
    {
        $this->withExceptionHandling();

        $this->get('threads/create')
            ->assertRedirect(route('login'));

        $this->post('/threads')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_create_new_threads(): void
    {
        $this->signIn();

        $thread = Thread::factory()->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function thread_requires_a_title(): void
    {
        $this->publishThread(['title' => NULL])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function thread_requires_a_body(): void
    {
        $this->publishThread(['body' => NULL])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function thread_requires_a_valid_channel(): void
    {
        Channel::factory()->count(2)->create();

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => NULL])
            ->assertSessionHasErrors('channel_id');
    }

    public function test_unauthorized_users_can_not_delete_threads(): void
    {
        $thread = Thread::factory()->create();

        $this->withExceptionHandling()
            ->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }


    public function test_authorized_users_can_delete_threads(): void
    {
        $this->signIn();

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);
        $reply = Reply::factory()->create(['thread_id' => $thread->id]);

        $this->delete($thread->path());

        $this->assertDatabaseMissing('threads', $thread->toArray())
            ->assertDatabaseMissing('replies', $reply->toArray())
            ->assertCount(0, Activities::all());
    }

    public function publishThread($overrides = []): TestResponse
    {
        $this->signIn();

        $thread = Thread::factory()->make($overrides);

        return $this->withExceptionHandling()
            ->post('/threads', $thread->toArray());
    }
}
