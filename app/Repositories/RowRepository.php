<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Row;
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
}
