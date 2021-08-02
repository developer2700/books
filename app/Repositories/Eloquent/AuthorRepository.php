<?php

namespace App\Repositories\Eloquent;

use App\Models\Author;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Classes\Paginate\Paginate;
use App\Classes\Filters\AuthorFilter;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    /**
     * BookRepository constructor.
     *
     * @param Author $author
     * @throws \Exception
     */
    public function __construct(Author $author)
    {
        parent::__construct($author);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->firstOrCreate($attributes);

    }
}

