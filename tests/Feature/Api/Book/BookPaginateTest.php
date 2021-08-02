<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookPaginateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_correct_books_with_limit_and_offset()
    {

        $books = factory(\App\Models\Book::class)->times(25)->create();

        $response = $this->getJson($this->app_url.'/books');

        $response->assertStatus(200)
            ->assertJson([
                'booksCount' => 25
            ]);

        $this->assertCount(20, $response->json()['books'], 'Expected books to set default limit to 20');
        $this->assertEquals(
            \App\Models\Book::orderby('id','desc')->take(20)->pluck('title')->toArray(),
            array_column($response->json()['books'], 'title'),
            'Expected latest 20 books by default'
        );

        $response = $this->getJson($this->app_url.'/books?limit=10&offset=5');

        $response->assertStatus(200)
            ->assertJson([
                'booksCount' => 25
            ]);

        $this->assertCount(10, $response->json()['books'], 'Expected books limit of 10 when set');

        $this->assertEquals(
            \App\Models\Book::orderby('id','desc')->skip(5)->take(10)->pluck('title')->toArray(),
            array_column($response->json()['books'], 'title'),
            'Expected latest 10 books with 5 offset'
        );
    }
}
