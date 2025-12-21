<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'purchase_orders';

    protected $fillable = [
        'branch_id',
        'user_id',
        'supplier_id',
        'order_number',
        'requested_date',
        'rejected_date',
        'recieved_date',
        'status',
        'total_cost',
        'reason',
        'purchase_order_details',
        'payment_term',
        'shipping_carrier',
        'shipping_fee'
    ];

    public function branch()
    {
        return $this->belongsTo(Store::class, 'branch_id', '_id')->whereNull('deleted_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id')->whereNull('deleted_at');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', '_id')->whereNull('deleted_at');
    }

    /**
     * Get embedded items only
     */
    public function items()
    {
        return collect($this->purchase_order_details ?? []);
    }

    /**
     * Get products related to embedded items
     */
    public function products()
    {
        $productIds = $this->items()
            ->pluck('product_id')
            ->unique()
            ->values()
            ->toArray();

        return Product::whereIn('_id', $productIds)->get();
    }

    /**
     * Get items with full product info
     */
    public function purchaseOrderDetails()
    {
        $items = $this->items();

        if ($items->isEmpty()) {
            return collect();
        }

        $products = Product::whereIn(
            '_id',
            $items->pluck('product_id')
        )->get()->keyBy('_id');

        return $items->map(function ($item) use ($products) {
            $product = $products[$item['product_id']] ?? null;

            return [
                'product_id' => $product->id,
                'product'    => $product,
                'quantity'   => (int) $item['quantity'],
                'unit_cost'  => (float) $item['unit_cost'],
                'total_cost' => (float) $item['total_cost'],
            ];
        });
    }

    public static function generatePONumber(): string
    {
        do {
            // Generate 12-digit number
            $number = random_int(100000000000, 999999999999);
            $poNumber = "PO-{$number}";
        } while (self::where('po_number', $poNumber)->exists());

        return $poNumber;
    }
}
