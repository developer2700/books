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

    /**
     * @param $file_name
     * @return array
     */
    public function read_csv($file_name)
    {
        $lines = [];
        $rows = explode("\n", file_get_contents($file_name));
        foreach ($rows as $row) {
            if (trim($row)) {
                $lines[] = array_filter(explode(',', trim($row)));
            }
        }
        return $lines;
    }

    /**
     * @param $file_name
     * @return array
     */
    public function read_xml($file_name)
    {
        $rows = simplexml_load_string(file_get_contents($file_name));
        $json = (json_decode(json_encode($rows), true));
        return $json['element'];
    }
}
