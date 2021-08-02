<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookUpdateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_updated_book_on_successfully_updating_the_book()
    {
        $book =factory(\App\Models\Book::class)->create();

        $data = [
            'book' => [
                'title' => $book->title,
                'author' => [
                   'first_name' =>$book->author->first_name,
                   'last_name' =>$book->author->last_name,
                ],
            ]
        ];

        $response = $this->putJson($this->app_url."/books/{$book->id}", $data, $this->headers);
        $response->assertStatus(200)
            ->assertJson([
                'book' => [
                    'title' => $book->title,
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_updating_the_book_with_invalid_inputs()
    {
        $book = factory(\App\Models\Book::class)->create();

        $data = [
            'book' => [
                'title' => '',
            ]
        ];

        $response = $this->putJson($this->app_url."/books/{$book->id}", $data, $this->headers);

        $response->assertStatus(422)
            ->assertJson([
                "message"=>"The given data was invalid.",
                'errors' => [
                    'title' => ['The title field is required.'],
                ]
            ]);
    }

    /** @test */
//    public function it_returns_an_unauthorized_error_when_trying_to_update_book_without_logging_in()
//    {
//        $book = $this->loggedInUser->books()->save(factory(\App\Models\Book::class)->make());
//
//        $data = [
//            'book' => [
//                'title' => 'new name',
//            ]
//        ];
//
//        $response = $this->putJson($this->>app_url."/books/{$book->id}", $data);
//
//        $response->assertStatus(401);
//    }


}
