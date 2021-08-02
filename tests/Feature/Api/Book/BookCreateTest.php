<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_book_on_successfully_creating_a_new_book()
    {
        $author = factory(\App\Models\Author::class)->create();
        $data = [
            'book' => [
                'title' => 'A Brief History of Humankind',
                'status' => 'Published',
                'author' => [
                    'first_name' => $author->first_name,
                    'last_name' => $author->last_name
                ],
                'author.last_name' => $author->last_name,
                'description' => 'some text',
            ]
        ];

        $response = $this->postJson($this->app_url.'/books', $data, $this->headers);
        $response->assertStatus(200)
            ->assertJson([
                'book' => [
                    'title' => $data['book']['title'],
                    'status' => $data['book']['status'],
                    'author' => [
                        'first_name' => $author->first_name,
                        'last_name' => $author->last_name
                    ],
                    'description' => $data['book']['description'],
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_creating_a_new_book_with_invalid_inputs()
    {
        $data = [
            'book' => [
                'unknown_column' => '',
            ]
        ];

        $response = $this->postJson($this->app_url.'/books', $data, $this->headers);
        $response->assertStatus(422)
            ->assertJson([
                "message"=> "The given data was invalid.",
                'errors' => [
                    'title' => ['The title field is required.'],
                    'author.first_name' => ['The author.first name field is required.'],
                    'author.last_name' => ['The author.last name field is required.'],
                ]
            ]);
    }

    /** @test */
    public function it_returns_the_attachment_and_book_on_successfully_creating_a_new_book_with_attachment()
    {
        $book = factory(\App\Models\Book::class)->create();
        $data = [
            'book' => [
                'title' => $book->title,
                'description' => $book->description,
                'author' => [
                    'first_name' => $book->author->first_name,
                    'last_name' => $book->author->last_name,
                ],
                'attachments' => [['filename'=>'file1.png']],
            ]
        ];
        $response = $this->postJson($this->app_url.'/books', $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    // we won't use auth login in this project
    /** @test */
//    public function it_returns_an_unauthorized_error_when_trying_to_add_book_without_logging_in()
//    {
//        $data = [
//            'book' => [
//                'title' => 'title1',
//            ]
//        ];
//
//        $response = $this->postJson('/api/books', $data, []);
//
//        $response->assertStatus(401);
//    }
}
