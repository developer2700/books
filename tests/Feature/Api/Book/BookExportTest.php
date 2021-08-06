<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookExportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_created_filename_with_given_format()
    {
        //create some books
        $books = factory(\App\Models\Book::class)->times(25)->create();

        $response = $this->getJson($this->app_url . '/books/export?format=csv');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_the_created_filename_with_given_format_and_title_field()
    {
        $books = factory(\App\Models\Book::class)->times(2)->create();
        $books = $books->sortByDesc('id');
        $books_data[] = ['title'];
        foreach ($books as $book) {
            $books_data [] = [$book->title];
        }
        $response = $this->getJson($this->app_url . '/books/export?format=csv&fields[]=title');
        $response->assertStatus(200);

        $file = $this->app_url . '/' . $response->getContent();
        $file_data = $this->read_csv($file);
        $this->assertEquals($file_data, $books_data);
    }

    /** @test */
    public function it_returns_the_exported_csv_file_with_title_and_author_first_name_field()
    {
        $books = factory(\App\Models\Book::class)->times(2)->create();
        $books = $books->sortByDesc('id');
        //prepare data
        $books_data[] = ['title', 'author.first_name'];
        foreach ($books as $book) {
            $books_data [] = [$book->title, $book->author->first_name];
        }
        $response = $this->getJson($this->app_url . '/books/export?format=csv&fields[]=title&fields[]=author.first_name');
        $response->assertStatus(200);

        $file = $this->app_url . '/' . $response->getContent();
        $file_data = $this->read_csv($file);
        $this->assertEquals($file_data, $books_data);
    }

    /** @test */
    public function it_returns_the_exported_xml_file_with_given_title_and_author_first_name_field()
    {
        $books = factory(\App\Models\Book::class)->times(2)->create();
        $books = $books->sortByDesc('id');
        //prepare data
        foreach ($books as $book) {
            $books_data [] = [
                'title' => $book->title,
                'author.first_name' => $book->author->first_name
            ];
        }
        $response = $this->getJson($this->app_url . '/books/export?format=xml&fields[]=title&fields[]=author.first_name');
        $response->assertStatus(200);

        $file = $this->app_url . '/' . $response->getContent();
        $file_data = $this->read_xml($file);
        $this->assertEquals($file_data, $books_data);
    }
}
