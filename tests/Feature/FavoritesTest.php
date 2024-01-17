<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Reply;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_favorite_anything(): void
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_favorite_any_reply(): void
    {
        $this->signIn();

        $reply = Reply::factory()->create();

        $this->post('replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function authenticated_user_can_only_favorite_a_reply_once(): void
    {
        $this->signIn();

        $reply = Reply::factory()->create();

        try {
            $this->post('replies/'.$reply->id.'/favorites');
            $this->post('replies/'.$reply->id.'/favorites');
        } catch (\Exception $e) {
            $this->fail($e);
        }

        $this->assertCount(1, $reply->favorites);
    }

}
