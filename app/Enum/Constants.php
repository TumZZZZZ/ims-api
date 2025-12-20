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
    const ROLE_STAFF        = 'STAFF';

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

    const PAYMENT_TYPE = "PAYMENT_TYPE";
    const PAYMENT_TYPE_CASH = "CASH";
    const PAYMENT_TYPE_ABA = "ABA";
    const PAYMENT_TYPE_ACLEDA = "ACLEDA";
    const PAYMENT_TYPE_WING = "WING";
    const PAYMENT_TYPE_FTB = "FTB";
    const PAYMENT_TYPE_SATHAPANA = "SATHAPANA";

    const ORDER_STATUS_NEW = "NEW";
    const ORDER_STATUS_CANCELLED = "CANCELLED";
    const ORDER_STATUS_PAID = "PAID";

    const TELEGRAM_CONFIG_TAB = "telegram_config";
    const TELEGRAM_CONFIG_TYPE_RECEIVE_INVOICE = "receive_invoice";
    const TELEGRAM_CONFIG_TYPE_LOWER_STOCK_ALERT = "lower_stock_alert";
    const TELEGRAM_RECEIVE_INVOICE = "TELEGRAM_RECEIVE_INVOICE";
    const TELEGRAM_LOWER_STOCK_ALERT = "TELEGRAM_LOWER_STOCK_ALERT";
}
