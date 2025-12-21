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
        self::LEDTER_TYPE_SALE => 'Sale',
        self::LEDTER_TYPE_PURCHASE_ORDER => 'Purchase Order',
        self::LEDTER_TYPE_INCREASEMENT => 'Adjustment Increase',
        self::LEDTER_TYPE_DECREASEMENT => 'Adjustment Decrease'
    ];

    const PURCHASE_ORDER_STATUS = [
        self::PURCHASE_ORDER_STATUS_RECEIVED => 'Received',
        self::PURCHASE_ORDER_STATUS_IN_REVIEW => 'In Review',
        self::PURCHASE_ORDER_STATUS_REQUESTED => 'Requested',
        self::PURCHASE_ORDER_STATUS_REJECTED => 'Rejected'
    ];

    const  PURCHASE_ORDER_STATUS_RECEIVED = "RECEIVED";
    const  PURCHASE_ORDER_STATUS_IN_REVIEW = "IN_REVIEW";
    const  PURCHASE_ORDER_STATUS_REQUESTED = "REQUESTED";
    const  PURCHASE_ORDER_STATUS_REJECTED = "REJECTED";

    const LEDTER_TYPE_SALE = "SALE";
    const LEDTER_TYPE_PURCHASE_ORDER = "PURCHASE_ORDER";
    const LEDTER_TYPE_INCREASEMENT = "INCREASEMENT";
    const LEDTER_TYPE_DECREASEMENT = "DECREASEMENT";

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

    const PO_STATUS_IN_REVIEW = "IN_REVIEW";
    const PO_STATUS_REQUESTED = "REQUESTED";
    const PO_STATUS_REJECTED = "REJECTED";
    const PO_STATUS_RECIEVED = "RECIEVED";
}
