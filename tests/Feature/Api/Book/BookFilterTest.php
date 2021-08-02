<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookFilterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_an_empty_array_of_books_when_no_books_exist_with_the_title()
    {
        $response = $this->getJson($this->app_url.'/books?title=test');

        $response->assertStatus(200)
            ->assertJson([
                'books' => [],
                'booksCount' => 0
            ]);

    }

    /** @test */
    public function it_returns_array_of_books_when_books_exist_by_given_title()
    {
        $books = factory(\App\Models\Book::class)->times(3)->create(['title' => 'test']);
        $data = [
            'books' => [
                [
                    'title' => $books[2]->title,
                ],
                [
                    'title' => $books[1]->title,
                ],
                [
                    'title' => $books[0]->title,
                ],
            ],
            'booksCount' => 3
        ];
        $response = $this->getJson($this->app_url.'/books?title=test');
        $response
            ->assertStatus(200)
            ->assertJson($data);
    }

    /** @test */
    public function it_returns_array_of_books_when_books_exist_by_given_id()
    {
        $books = factory(\App\Models\Book::class)->times(3)->create(['title' => 'book1']);

        $response = $this->getJson($this->app_url.'/books?id=1');
        $response->assertStatus(200)
            ->assertJson([
                'books' => [
                    [
                        'title' => $books[2]->title,
                    ],
                ],
                'booksCount' => 1
            ]);
    }




}
