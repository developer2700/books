<?php

namespace App\Classes\Transformers;

class AuthorTransformer extends Transformer
{
    protected $resourceName = 'author';

    public function transform($data)
    {
        return [
            'id' => $data['id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'created_at' => $data['created_at']->format('Y-m-d H:i:s'),
            'updated_at' => $data['updated_at']->format('Y-m-d H:i:s'),
        ];
    }
}
