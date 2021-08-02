<?php

namespace App\Http\Requests\Api\Book;

use App\Http\Requests\Api\ApiRequest;

class UpdateBook extends ApiRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return $this->get('book') ?: [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'sometimes|max:1024',
            'author.id' => 'sometimes|exists:App\Models\Author,id',
            'author.first_name' => 'required|string|max:255',
            'author.last_name' => 'required|string|max:255',
            'attachments' => 'sometimes',
        ];
    }
}
