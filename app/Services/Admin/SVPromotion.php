<?php

namespace App\Services\Admin;

use App\Enum\Constants;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SVPromotion
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Promotion::with(['branch'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->where('branch_id', $user->active_on)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function getById($id)
    {
        return Promotion::find($id);
    }

    public function getPromotionTypes()
    {
        return [
            (object)[
                'key' => Constants::PROMOTION_TYPE_AMOUNT,
                'value' => __('amount')
            ],
            (object)[
                'key' => Constants::PROMOTION_TYPE_PERCENTAGE,
                'value' => __('percentage')
            ]
        ];
    }

    public function store(array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($params, $user) {
            // Convert to UTC
            $params['start_date'] = Carbon::parse($params['start_date'], getTimezone())->setTimezone('UTC');
            $params['end_date'] = Carbon::parse($params['end_date'], getTimezone())->setTimezone('UTC');

            // Create promotion
            $promotion = Promotion::create([
                'branch_id' => $user->active_on,
                'name' => $params['name'],
                'type' => $params['type'],
                'value' => $params['amount'],
                'start_date' => $params['start_date'],
                'end_date' => $params['end_date'],
                'category_ids' => $params['category_ids'] ?? [],
                'product_ids' => $params['product_ids'] ?? [],
            ]);

            if (!empty($params['product_ids'])) {
                Product::whereIn('id', $params['product_ids'])->update([
                    'promotion_id' => $promotion->id,
                ]);
            }

            if (!empty($params['category_ids'])) {
                Category::whereIn('id', $params['category_ids'])->update([
                    'promotion_id' => $promotion->id,
                ]);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('created_an_object', ['object' => __('promotion')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function update($id, array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $params, $user) {
            // Convert to UTC
            $params['start_date'] = Carbon::parse($params['start_date'], getTimezone())->setTimezone('UTC');
            $params['end_date'] = Carbon::parse($params['end_date'], getTimezone())->setTimezone('UTC');

            // Create promotion
            $promotion = Promotion::find($id);

            // Update promotion
            $promotion->branch_id = $user->active_on;
            $promotion->name = $params['name'];
            $promotion->type = $params['type'];
            $promotion->value = $params['amount'];
            $promotion->start_date = $params['start_date'];
            $promotion->end_date = $params['end_date'];
            $promotion->category_ids = $params['category_ids'] ?? [];
            $promotion->product_ids = $params['product_ids'] ?? [];
            $promotion->save();

            if (!empty($params['product_ids'])) {
                Product::whereIn('id', $params['product_ids'])->update([
                    'promotion_id' => $promotion->id,
                ]);
            }

            if (!empty($params['category_ids'])) {
                Category::whereIn('id', $params['category_ids'])->update([
                    'promotion_id' => $promotion->id,
                ]);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('promotion')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function delete($id)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $user) {
            $promotion = Promotion::find($id);

            // Soft delete user
            $promotion->deleted_at = now();
            $promotion->save();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('promotion')]), @$user->merchant->id, $user->active_on);
        });
    }
}
