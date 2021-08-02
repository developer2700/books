<?php

namespace App\Repositories\Interfaces;

use App\Classes\Filters\FilterInterface;
use App\Classes\Filters\BookFilter;
use App\Models\Book;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BookRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * export query records.
     *
     * @param FilterInterface $filter
     * @return mixed
     */
    public function export(FilterInterface $filter);

}
