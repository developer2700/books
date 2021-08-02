<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookViewTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_book_by_id_if_valid_and_not_found_error_if_invalid()
    {
        $book = factory(\App\Models\Book::class)->create();

        $response = $this->getJson($this->app_url."/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJson([
                'book' => [
                    'title' => $book->title,
                    'author' => [
                        'first_name' =>$book->author->first_name,
                        'last_name' =>$book->author->last_name,
                    ],
                    'description' => $book->description,
                    'created_at' => $book->created_at,
                ]
            ]);

        $response = $this->getJson($this->app_url.'/books/wrong_id');

        $response->assertStatus(404);
    }

}
