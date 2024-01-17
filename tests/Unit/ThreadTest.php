<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Thread;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected Thread $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = Thread::factory()->create();
    }

    /** @test */
    public function thread_can_make_a_string_path(): void
    {
        $thread = Thread::factory()->create();

        $this->assertEquals('/threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
    }

    /** @test */
    public function thread_has_a_creator(): void
    {
        $this->assertInstanceOf('App\Models\User', $this->thread->creator);
    }

    /** @test */
    public function thread_has_replies(): void
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function thread_can_add_a_reply_(): void
    {
        $this->thread->addReply([
            'body' => 'good body',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function thread_belongs_to_a_channel(): void
    {
        $thread = Thread::factory()->create();

        $this->assertInstanceOf('App\Models\Channel', $thread->channel);
    }
}
