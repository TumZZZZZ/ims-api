<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseApi;
use App\Services\Inventory\SVLedgers;
use Illuminate\Http\Request;

class LedgersController extends BaseApi
{
    public function getService()
    {
        return (new SVLedgers());
    }

    public function index(Request $request)
    {
        return view('admin.ledgers.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }
}
