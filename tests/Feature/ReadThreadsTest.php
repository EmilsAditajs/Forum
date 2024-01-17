<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\Channel;
use App\Models\User;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected Thread $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = Thread::factory()->create();
    }

    /** @test */
    public function user_can_browse_all_threads(): void
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function user_can_browse_single_thread(): void
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function user_can_read_replies_of_the_thread(): void
    {
        $reply = Reply::factory()
            ->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function user_can_filter_threads_according_to_a_channel(): void
    {
        $channel = Channel::factory()->create();

        $threadInChannel = Thread::factory()->create(['channel_id' => $channel->id]);
        $threadNotInChannel = Thread::factory()->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);

    }

    /** @test */
    public function user_can_filter_threads_by_any_user_name(): void
    {
        $this->signIn(User::factory()->create(['name' => 'Janis']));

        $threadByJanis = Thread::factory()->create(['user_id'=> auth()->id()]);
        $threadNotByJanis = Thread::factory()->create();

        $this->get('/threads?by=Janis')
            ->assertSee($threadByJanis->title)
            ->assertDontSee($threadNotByJanis->title);
    }

        /** @test */
        public function user_can_filter_threads_by_popularity(): void
        {
            $threadWithTwoReplies = Thread::factory()->create();
            Reply::factory()->count(2)->create(['thread_id'=> $threadWithTwoReplies->id]);

            $threadWithThreeReplies = Thread::factory()->create();
            Reply::factory()->count(3)->create(['thread_id'=> $threadWithThreeReplies->id]);

            $threadWithNoReplies = $this->thread;

            $response = $this->getJson('/threads?popular=1')->json();

            $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
        }
}
