<?php

namespace App\Classes\Filters;

use App\Classes\Transformers\AttachmentTransformer;
use App\Models\Attachment;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttachmentFilter extends Filter
{

    protected $bookRepository;

    /**
     * Filter constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(Request $request, BookRepositoryInterface $bookRepository)
    {
        parent::__construct($request);
        $this->bookRepository = $bookRepository;
    }

    /**
     * Filter by name
     * Get all the attachments by the given file name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function file_name($name)
    {
        return $this->builder->where('filename', 'like', $name . '%');
    }

    /**
     * Filter by id
     * Get the station by the given id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function id($id)
    {
        return $this->builder->whereId($id);
    }

    /**
     * Filter by book_id
     * Get the attachments by the given book_id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function book_id($id)
    {
        return $this->builder->where('book_id', $id);
    }

}
