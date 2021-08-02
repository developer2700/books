<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use App\Classes\Filters\AuthorFilter;
use App\Classes\Transformers\AuthorTransformer;
use App\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorsController extends ApiController
{
    protected $repository;

    /**
     * AuthorsController constructor.
     *
     * @param AuthorRepositoryInterface $authorRepository
     * @param AuthorTransformer $transformer
     */
    public function __construct(AuthorRepositoryInterface $authorRepository, AuthorTransformer $transformer)
    {
        $this->repository = $authorRepository;
        $this->transformer = $transformer;
    }

    /**
     * Get all the companies.
     *
     * @param AuthorFilter $filter
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(AuthorFilter $filter)
    {
        $authors = $this->repository->paginate($filter);

        return $this->respondWithPagination($authors);
    }
}
