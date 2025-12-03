<?php

use App\Models\History;

if (! function_exists('createHistory')) {
    function createHistory($userId, $value, $storeId = null)
    {
        History::create([
            'user_id'  => $userId,
            'store_id' => $storeId,
            'action'   => $value,
        ]);
    }
}

if (!function_exists('getTimezone')) {
    function getTimezone()
    {
        return "Asia/Phnom_Penh";
    }
}
