<?php

namespace App\Enum;

class Constants
{
    const ROLES = [
        'SUPER_ADMIN' => 'Super Admin',
        'ADMIN'       => 'Admin',
        'MANAGER'     => 'Manager',
        'STAFF'       => 'Staff',
    ];

    const ROLE_SUPER_ADMIN  = 'SUPER_ADMIN';
    const ROLE_ADMIN        = 'ADMIN';
    const ROLE_MANAGER      = 'MANAGER';

    const PROMOTION_TYPE_AMOUNT = "AMOUNT";
    const PROMOTION_TYPE_PERCENTAGE = "PERCENTAGE";

    const LEDGER_TYPES = [
        'SALE' => 'Sale',
        'PURCHASE_ORDER' => 'Purchase Order',
        'INCREASEMENT' => 'Adjustment Increase',
        'DECREASEMENT' => 'Adjustment Decrease'
    ];

    const  PURCHASE_ORDER_STATUS_CLOSED = "CLOSED";
    const  PURCHASE_ORDER_STATUS_DRAFT = "DRAFT";
    const  PURCHASE_ORDER_STATUS_SENT = "SENT";
    const  PURCHASE_ORDER_STATUS_REJECTED = "REJECTED";
}
