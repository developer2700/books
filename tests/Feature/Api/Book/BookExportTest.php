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

        $response = $this->getJson($this->app_url.'/books/export?type=csv');

        $response->assertStatus(200);
    }
}
