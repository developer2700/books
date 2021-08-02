<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookDeleteTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_a_200_success_response_on_successfully_removing_the_book()
    {
        $book = factory(\App\Models\Book::class)->create();

        $response = $this->deleteJson($this->app_url."/books/{$book->id}", [], $this->headers);

        $response->assertStatus(200);

        $response = $this->getJson($this->app_url."/books/{$book->id}");

        $response->assertStatus(404);
    }

}
