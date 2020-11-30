<?php
declare(strict_types=1);

namespace App\Models\Collection;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class PaginatedCollection
 * @package App\Models\Collection
 */
class PaginatedCollection
{
    /** @var Collection */
    private $items;

    /** @var array */
    private $pagination;

    /**
     * PaginatedCollection constructor.
     * @param Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     */
    public function __construct(Collection $items, int $total, int $perPage, int $currentPage)
    {
        $paginator = new LengthAwarePaginator($items, $total, $perPage, $currentPage);
        $this->items = new Collection($paginator->items());

        $this->pagination = [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'first_item' => $paginator->firstItem(),
            'last_item' => $paginator->lastItem()
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'pagination' => $this->pagination,
        ];
    }
}
