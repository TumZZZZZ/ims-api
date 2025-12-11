<?php

use App\Models\History;
use App\Models\Image;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

if (! function_exists('createHistory')) {
    function createHistory($userId, $value, $storeId = null, array $details = [])
    {
        History::create([
            'user_id'  => $userId,
            'store_id' => $storeId,
            'action'   => $value,
            'details'  => !empty($details) ? json_encode($details) : null,
        ]);
    }
}

if (!function_exists('getTimezone')) {
    function getTimezone()
    {
        return "Asia/Phnom_Penh";
    }
}

if (!function_exists('initials')) {
    /**
     * Get initials (first letters) of first 2 words of a name
     *
     * @param string $name
     * @return string
     */
    function initials($name)
    {
        $name = trim($name);
        if (empty($name)) return '';

        $parts = explode(' ', $name);

        $firstLetter = substr($parts[0], 0, 1);
        $secondLetter = isset($parts[1]) ? substr($parts[1], 0, 1) : '';

        return strtoupper($firstLetter . $secondLetter);
    }
}

if (!function_exists('uploadImage')) {
    /**
     * @param string $objectId
     * @param string $collection
     * @param file $image
     * @return string
     */
    function uploadImage($objectId, $collection, $image)
    {
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = public_path('storage/images/'.$collection);
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0777, true);
        }
        $image->move($imagePath, $filename);
        $imageUrl = asset('storage/images/'.$collection.'/'.$filename);

        // Upsert image record
        $image = Image::where('object_id', $objectId)
            ->where('collection', $collection)
            ->first();
        if ($image) {
            $image->url = $imageUrl;
            $image->save();
        } else {
            Image::create([
                'object_id'  => $objectId,
                'collection' => $collection,
                'url'        => $imageUrl,
            ]);
        }
    }
}

if (!function_exists('mongodbTransaction')) {
    /**
     * Run multiple MongoDB operations in a transaction.
     *
     * @param callable $callback
     * @throws \Exception
     */
    function mongodbTransaction(callable $callback)
    {
        // Get MongoDB client
        $client = DB::connection('mongodb')->getMongoClient();

        // Start session
        $session = $client->startSession();

        try {
            // Begin transaction
            $session->startTransaction();

            // Run user callback, passing the session
            $callback($session);

            // Commit transaction
            $session->commitTransaction();
        } catch (\Exception $e) {
            // Rollback transaction
            $session->abortTransaction();
            throw $e;
        }
    }
}

if (!function_exists('getMerchantNameByUserStoreIds')) {
    function getMerchantNameByUserStoreIds(array $storeIds)
    {
        $merchant = Store::whereIn('_id', $storeIds)
            ->whereNull('parent_id')
            // ->whereNull('deleted_at')
            ->first();
        return $merchant ? $merchant->name : null;
    }
}

if (!function_exists('getBranchNamesByUserStoreIds')) {
    function getBranchNamesByUserStoreIds(array $storeIds)
    {
        $branches = \App\Models\Store::whereIn('_id', $storeIds)
            ->whereNotNull('parent_id')
            ->whereNull('deleted_at')
            ->get();
        return implode(', ', $branches->pluck('name')->toArray());
    }
}

if (!function_exists('getFontFamilyByLocale')) {
    function getFontFamilyByLocale($locale)
    {
        switch ($locale) {
            case 'km':
                return 'Kantumruy Pro';
                break;
            default:
                return 'Poppins';
                break;
        }
    }
}
