<?php
declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class RowsController
 * @package App\Http\Controllers\Frontend
 */
class RowsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function listRows()
    {
        return view('rows.list');
    }
}
