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
    ];

    public function branch()
    {
        return $this->hasOne(Store::class, '_id', 'branch_id')->whereNull('deleted_at');
    }

    public function user()
    {
        return $this->hasOne(User::class, '_id', 'user_id')->whereNull('deleted_at');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, '_id', 'supplier_id')->whereNull('deleted_at');
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
