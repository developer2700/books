<?php

namespace App\Classes\Paginate;

use Illuminate\Database\Eloquent\Builder;

class Paginate
{
    /**
     * Total count of the items.
     *
     * @var int
     */
    protected $total;

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
     * @param int $limit
     * @param int $offset
     * @param string $sort
     * @param string $order_by
     */
    public function __construct(Builder $builder, $limit = 20, $offset = 0, $order_by = 'id', $sort = 'DESC')
    {
        $limit = request()->get('limit', $limit);
        $offset = request()->get('offset', $offset);
        $sort = request()->get('sort', $sort);
        $order_by = request()->get('order_by', $order_by);

        $this->total = $builder->count();

        $this->data = $builder
            ->orderBy( $order_by , $sort )
            ->skip($offset)
            ->take($limit)
            ->get();
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
