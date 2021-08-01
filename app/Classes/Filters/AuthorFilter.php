<?php

namespace App\Classes\Filters;

use App\Models\Author;

class AuthorFilter extends Filter
{
    /**
     * Filter by first_name
     * Get all the authors by the given author first_name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function first_name($name)
    {
        return $this->builder->where('first_name', 'like', $name . '%');
    }

    /**
     * Filter by last_name
     * Get all the authors by the given author last_name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function last_name($name)
    {
        return $this->builder->where('last_name', 'like', $name . '%');
    }

    /**
     * Filter by fullname
     * Get all the authors by the given author first_name.
     *
     * @param $full_name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function name($full_name)
    {
        return $this->builder
            ->where('first_name', 'like', $full_name . '%')
            ->orWhere('last_name', 'like', $full_name . '%');
    }
    /**
     * Filter by id
     * Get the author by the given id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function id($id)
    {
        return $this->builder->whereId($id);
    }

}
