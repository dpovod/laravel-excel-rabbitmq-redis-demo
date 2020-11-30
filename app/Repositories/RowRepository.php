<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Row;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RowRepository
 * @package App\Repositories
 */
class RowRepository extends BaseRepository
{
    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return Row::getModel();
    }

    /**
     * @param string $column
     * @return int
     */
    public function getDistinctCount(string $column): int
    {
        return $this->model->distinct()->count($column);
    }

    /**
     * @param string $groupBy
     * @param int $limit
     * @param int $offset
     * @param string $column
     * @return Collection|Model[]
     */
    public function getGroupedBy(string $groupBy, int $limit, int $offset, string $column = '*'): Collection
    {
        return $this->model->groupBy($groupBy)->orderBy($groupBy)->limit($limit)->offset($offset)->get($column);
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return Collection|Model[]
     */
    public function getByDate(\DateTime $from, \DateTime $to): Collection
    {
        return $this->model->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->get();
    }
}
