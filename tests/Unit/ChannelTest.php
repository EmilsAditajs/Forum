<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Channel;
use App\Models\Thread;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_Channel_consists_of_threads(): void
    {
        $channel = Channel::factory()->create();
        $thread = Thread::factory()->create(['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads()->where('id', $thread->id)->exists());
    }
}
