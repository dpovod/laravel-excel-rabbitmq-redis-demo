<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Collection\PaginatedCollection;
use App\Repositories\RowRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class RowsController
 * @package App\Http\Controllers\Backend
 */
class RowsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $repo = new RowRepository();
        $page = (int)$request->input('page');
        $onPage = 10;
        $offset = $page * $onPage - $onPage;
        $total = $repo->getDistinctCount('date');

        if (!$total) {
            return response()->json([
                'success' => true,
                'data' => (new PaginatedCollection(new Collection(), 0, $onPage, $page))->toArray()
            ]);
        }

        $uniqueDates = $repo->getGroupedBy('date', $onPage, $offset, 'date');
        $minDate = $uniqueDates->first()->date;
        $maxDate = $uniqueDates->last()->date;
        $list = $repo->getByDate($minDate, $maxDate);

        $list = $list->groupBy(function ($item) {
            return $item->date_string;
        });

        return response()->json([
            'success' => true,
            'data' => (new PaginatedCollection($list, $total, $onPage, $page))->toArray()
        ]);
    }
}
