<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\ApiRequest;

class DeleteBook extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
//        $book = $this->route('book');
//        return !count($book->attachments);
    }
}
