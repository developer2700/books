<?php

namespace App\Classes\Transformers;

class BookTransformer extends Transformer
{
    protected $resourceName = 'book';

    public function transform($data)
    {
        return [
            'id' => $data['id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status_text'],
            'created_at' => $data['created_at']->format('Y-m-d H:i:s'),
            'updated_at' => $data['updated_at']->format('Y-m-d H:i:s'),
            'author' => $data->author,
            'attachments' => $data->attachments,
        ];
    }
}
