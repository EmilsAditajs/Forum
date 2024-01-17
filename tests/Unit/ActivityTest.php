<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\Activities;
use App\Models\Reply;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    public function test_records_activity_when_a_thread_is_created(): void
    {
        $this->signIn();

        $thread = Thread::factory()->create();

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id'=> auth('')->id(),
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $activity = Activities::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function test_records_activity_when_a_reply_is_created(): void
    {
        $this->signIn();

        $reply = Reply::factory()->create();

        $this->assertEquals(2, Activities::count());


    }

    public function test_fetch_activity_feed_for_any_user()
    {
        $this->signIn();

        Thread::factory()->count(2)->create(['user_id' => auth('')->id()]);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activities::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
