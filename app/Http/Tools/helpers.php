<?php

use App\Models\History;

if (! function_exists('createHistory')) {
    function createHistory($userId, $value, $storeId = null)
    {
        History::create([
            'user_id'  => $userId,
            'store_id' => $storeId,
            'value'    => $value,
        ]);
    }
}
