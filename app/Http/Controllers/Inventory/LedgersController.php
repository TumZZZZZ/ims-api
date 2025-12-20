<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\SVLedgers;
use Illuminate\Http\Request;

class LedgersController extends Controller
{
    public function getService()
    {
        return (new SVLedgers());
    }

    public function index(Request $request)
    {
        return view('inventory.ledgers.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }
}
