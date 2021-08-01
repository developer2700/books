<?php

namespace App\Classes\Filters;

use App\Models\Attachment;
use App\Models\Book;

class BookFilter extends Filter
{
    /**
     * Filter by title
     * Get all the books by the given book title.
     *
     * @param $title
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function title($title)
    {
        return $this->builder->where('title', 'like', $title . '%');
    }

    /**
     * Filter by id
     * Get the book by the given id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function id($id)
    {
        return $this->builder->where('books.id',$id);
    }

    /**
     * Filter by Author's name
     * Get books by the given author name.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function author($name)
    {
        return $this->builder
            ->whereHas('author', function ($q) use ($name) {
                $q->where('first_name', 'like', $name.'%');
                $q->orWhere('last_name', 'like', $name.'%');
            })->get();
    }
    /**
     * Filter by Status
     * Get the book by the given status.
     *
     * @param $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function status($status)
    {
        if (is_numeric($status)) {
            return $this->builder->whereStatus($status);
        } elseif ($status = array_search($status, Book::$statuses)) {
            return $this->builder->whereStatus($status);
        }
    }
}
