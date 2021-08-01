<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    protected $user;
    protected $app_url;

    protected $headers;

    public function setUp(): void
    {
        parent::setUp();

        if (env('withoutExceptionHandling')) {
            $this->withoutExceptionHandling();
        }
        $user = factory(\App\Models\User::class)->create();
        $this->user = $user;
        $this->app_url = env('APP_URL');

        $this->headers = [
            'Authorization' => "Bearer {$this->user->name}"
        ];
    }
}
