<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Reply;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reply_has_a_owner(): void
    {
        $reply = Reply::factory()->create();

        $this->assertInstanceOf('App\Models\User', $reply->owner);
    }
}
