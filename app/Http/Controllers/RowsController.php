<?php

namespace App\Http\Controllers;

class RowsController extends Controller
{
    public function listRows()
    {
        return view('rows.list');
    }
}
