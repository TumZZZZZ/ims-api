<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $purchaseOrder;

    /**
     * Create a new message instance.
     */
    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Purchase Order : '. $this->purchaseOrder->order_number)
            ->view('inventory.purchase-order-mail', [
                'purchase_order' => $this->purchaseOrder,
            ]);
    }
}
