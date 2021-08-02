<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Classes\Filters\BookFilter;
use App\Http\Requests\Api\Book\CreateBook;
use App\Http\Requests\Api\Book\UpdateBook;
use App\Http\Requests\Api\Book\DeleteBook;
use App\Classes\Transformers\BookTransformer;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\AuthorRepositoryInterface;

class BooksController extends ApiController
{

    protected $bookRepository;
    protected $authorRepository;

    /**
     * BooksController constructor.
     *
     * @param BookRepositoryInterface $bookRepository
     * @param AuthorRepositoryInterface $authorRepository
     * @param BookTransformer $transformer
     */
    public function __construct(BookRepositoryInterface $bookRepository,AuthorRepositoryInterface $authorRepository, BookTransformer $transformer)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->transformer = $transformer;
    }

    /**
     * Get all the books.
     *
     * @param BookFilter $filter
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(BookFilter $filter)
    {
        $books = $this->bookRepository->paginate($filter);

        return $this->respondWithPagination($books);
    }

    /**
     * Export all query books to file and return filename.
     *
     * @param BookFilter $filter
     * @return string
     * @throws \Exception
     */
    public function export(BookFilter $filter)
    {
        $fileName = $this->bookRepository->export($filter);
        return  $fileName;
    }

    /**
     * Create a new book and return the book if successful.
     *
     * @param CreateBook $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBook $request)
    {
        $input = $request->get('book');
        if (empty($input['author']['id'])) {
            $author = $this->authorRepository->create($input['author']);
            $input['author_id'] = $author->id;
        } else {
            $input['author_id'] = $input['author']['id'];
        }
        $book = $this->bookRepository->create($input);

        return $this->respondWithTransformer($book);
    }

    /**
     * Get the book given by its id.
     *
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        return $this->respondWithTransformer($book);
    }

    /**
     * Update the book given by its slug and return the book if successful.
     *
     * @param UpdateBook $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateBook $request, Book $book)
    {
        $input = $request->get('book');

        if (empty($input['author']['id'])) {
            $author = $this->authorRepository->create($input['author']);
            $input['author_id'] = $author->id;
        }else {
            $author = $this->authorRepository->newQuery()->firstOrCreate($input['author']);
            $input['author_id'] = $author->id;
        }
        $book = $this->bookRepository->update($book->id, $input);

        return $this->respondWithTransformer($book);
    }

    /**
     * Delete the book .
     *
     * @param DeleteBook $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DeleteBook $request, Book $book)
    {
        if ($this->bookRepository->delete($book)) {
            return $this->respondSuccess();
        } else {
            return $this->respondForbidden();
        }
    }
}
