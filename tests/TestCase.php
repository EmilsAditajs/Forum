<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }

    protected function signIn(User $user = NULL): static
    {
        $user = $user ?: User::factory()->create();

        $this->actingAs($user);

        return $this;
    }

    protected function disableExceptionHandling(): void
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(Throwable $e) {}
            public function render($request, Throwable $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling(): static
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
