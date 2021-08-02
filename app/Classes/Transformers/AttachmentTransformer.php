<?php

namespace App\Classes\Transformers;

class AttachmentTransformer extends Transformer
{
    protected $resourceName = 'attachment';

    public function transform($data)
    {
        return [
            'id' => $data['id'],
            'book_id' => $data['book_id'],
            'filename' => $data['filename'],
            'created_at' => $data['created_at']->format('Y-m-d H:i:s'),
            'updated_at' => $data['updated_at']->format('Y-m-d H:i:s'),
            'book' => $data['book'],
        ];
    }
}
