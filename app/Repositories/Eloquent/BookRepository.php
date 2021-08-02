<?php

namespace App\Repositories\Eloquent;

use App\Classes\Export\Export;
use App\Models\Book;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Classes\Filters\FilterInterface;
use App\Classes\Paginate\Paginate;
use App\Classes\Filters\BookFilter;
use App\Repositories\Interfaces\IModelConverterService;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    protected $modelConverterService;
    /**
     * BookRepository constructor.
     *
     * @param Book $book
     * @param IModelConverterService $modelConverterService
     * @throws \Exception
     */
    public function __construct(Book $book,IModelConverterService $modelConverterService)
    {
        $this->modelConverterService = $modelConverterService;
        parent::__construct($book);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        $book = $this->model->create($attributes);
        if (!empty($attributes['attachments'])) {
            foreach ($attributes['attachments'] as $attachment) {
                $book->attachments()->create($attachment);
            }
        }
        return $book;
    }
    /**
     * @param int $id
     * @param array $attributes
     *
     * @return Model
     */
    public function update(int $id,array $attributes): Model
    {
        $book = $this->model->find($id);
        $book->update($attributes);
        if (!empty($attributes['attachments'])) {
            $book->attachments()->delete();
            foreach ($attributes['attachments'] as $attachment) {
                $book->attachments()->create($attachment);
            }
        }
        return $book;
    }

    /**
     * export all query records.
     *
     * @param FilterInterface $filter
     * @return mixed
     */
    public function export(FilterInterface $filter)
    {
        $exporter = new Export($this->model->loadRelations()->filter($filter) , $this->modelConverterService);
        return $exporter->export();
    }
}

