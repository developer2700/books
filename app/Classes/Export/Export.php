<?php

namespace App\Classes\Export;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\IModelConverterService;

class Export
{
    private $modelConverterService;

    /**
     * Total count of the items.
     *
     * @var int
     */
    protected $total;

    /**
     * Format of export file
     *
     * @var string
     */
    protected $format;

    /**
     * Fields of the items to export.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Collection of items.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $data;

    /**
     * Paginate constructor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param IModelConverterService $modelConverterService
     */
    public function __construct(Builder $builder,IModelConverterService $modelConverterService)
    {
        $this->modelConverterService = $modelConverterService;
        $sort = request()->get('sort', 'DESC');
        $order_by = request()->get('order_by','id');
        $this->format = request()->get('format', 'csv');
        $this->fields = request()->get('fields', []);
        $this->total = $builder->count();

        $this->data = $builder
            ->orderBy($order_by, $sort)
            ->get();
    }

    /**
     * Export query-data to file.
     *
     * @return string
     */
    public function export()
    {
        return $this->modelConverterService->ExportModelsToFile($this->format, $this->getData(), $this->fields);
    }

    /**
     * Get the total count of the items.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get the paginated collection of items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData()
    {
        return $this->data;
    }
}
