<?php

namespace App\Services\API;

use App\Enum\Constants;
use App\Models\Category;
use App\Models\Meta;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAssign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

class SVSaleScreen
{
    public function getPaymentMethods()
    {
        return Meta::where('key', Constants::PAYMENT_TYPE)
            ->get()
            ->transform(function ($paymentMethod) {
                return (object)[
                    'id' => $paymentMethod->id,
                    'image_url' => $paymentMethod->image->url ?? null,
                    'name' => $paymentMethod->value,
                ];
            })
            ->values();
    }

    public function getAllCategories(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Category::with(['image'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->where('branch_ids', $user->active_on)
            ->whereNull('deleted_at')
            ->get()
            ->transform(function($category) {
                return (object)[
                    'id' => $category->id,
                    'name' => $category->name,
                    'image_url' => $category->image->url ?? null,
                ];
            })
            ->values();
    }

    public function getProductByCategory($categoryId, array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;

        $category = Category::find($categoryId);
        if (!$category) {
            throw new \Exception('Category not found!', 404);
        }

        return Product::with(['image','assign'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->whereHas('assign', function ($query) use ($user) {
                $query->where('branch_id', $user->active_on)
                    ->whereNull('deleted_at');
            })
            ->where('category_ids', $category->id)
            ->whereNull('deleted_at')
            ->get()
            ->transform(function($product) {
                return (object)[
                    'id' => $product->id,
                    'name' => $product->name,
                    'image_url' => $product->image->url ?? null,
                    'price' => $product->assign->price,
                    'price_format' => amountFormat(convertCentsToAmounts($product->assign->price), getCurrencyCode()),
                ];
            })
            ->values();
    }

    public function getOrderDetails()
    {
        $user = Auth::user();
        $order = Order::with(['orderDetails'])
            ->where('branch_id', $user->active_on)
            ->where('sale_by', $user->id)
            ->where('status', Constants::ORDER_STATUS_NEW)
            ->first();
        if (!$order) return [];

        $orderDetails = [];
        $subtotal = 0;
        $discount = 0;
        foreach ($order->orderDetails as $orderDetail) {
            $price = $orderDetail->price * $orderDetail->quantity;
            $subtotal += $price;
            $discount += $orderDetail->discount_amount;
            $orderDetails[] = [
                'id' => $orderDetail->product_id,
                'name' => $orderDetail->product->name,
                'image_url' => $orderDetail->image->url ?? null,
                'price' => amountFormat(convertCentsToAmounts($price), getCurrencyCode()),
                'quantity' => $orderDetail->quantity,
            ];
        }
        $total = $subtotal + $discount;

        return [
            'order_id' => $order->id,
            'order_details' => $orderDetails,
            'subtotal' => amountFormat(convertCentsToAmounts($subtotal), getCurrencyCode()),
            'discount' => amountFormat(convertCentsToAmounts($discount), getCurrencyCode()),
            'total' => amountFormat(convertCentsToAmounts($total), getCurrencyCode()),
        ];
    }

    public function addOrder(array $params)
    {
        $categoryId = $params['category_id'];
        $productId = $params['product_id'];
        $quantity = $params['quantity'];
        $discountId = $params['discount_id'] ?? null;
        $user = Auth::user();

        $category = Category::find($categoryId);
        if (!$category) {
            throw new \Exception('Category not found!', 404);
        }

        $product = Product::find($productId);
        if (!$product) {
            throw new \Exception('Product not found!', 404);
        }

        $discountAmount = 0;
        if ($discountId) {
            # code...
        }

        $order = Order::with(['orderDetails'])
            ->where('branch_id', $user->active_on)
            ->where('sale_by', $user->id)
            ->where('status', Constants::ORDER_STATUS_NEW)
            ->first();
        if (!$order) {
            # Create order
            $order = Order::create([
                'branch_id' => $user->active_on,
                'sale_by' => $user->id,
                'order_number' => generateOrderNumber(),
                'date' => now(),
                'status' => Constants::ORDER_STATUS_NEW,
            ]);
            # Create order detail
            OrderDetail::create([
                'order_id' => $order->id,
                'category_id' => $categoryId,
                'product_id' => $productId,
                'discount_id' => $discountId,
                'price' => $product->assign->price,
                'cost' => $product->assign->cost,
                'quantity' => $quantity,
                'discount_amount' => $discountAmount,
            ]);
        } else {
            foreach ($order->orderDetails as $orderDetail) {
                if ($orderDetail->product_id === $productId) {
                    $orderDetail->quantity = $orderDetail->quantity + $quantity;
                    $orderDetail->save();
                } else {
                    # Create order detail
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'category_id' => $categoryId,
                        'product_id' => $productId,
                        'discount_id' => $discountId,
                        'price' => $product->assign->price,
                        'cost' => $product->assign->cost,
                        'quantity' => $quantity,
                        'discount_amount' => $discountAmount,
                    ]);
                }
            }
        }

        # Update stock
        $productAssign = $product->assign;
        $productAssign->quantity = $productAssign->quantity - $quantity;
        $productAssign->save();

        return $this->getOrderDetails();
    }

    public function adjustOrderQuantity($orderId, array $params)
    {
        $orderDetail = OrderDetail::with('order')
            ->whereHas('order', function($query) use ($orderId) {
                $query->where('id', $orderId);
            })
            ->where('product_id', $params['product_id'])
            ->first();
        $orderDetail->quantity = $params['quantity'];
        $orderDetail->save();

        # Modify stock
        $productAssign = $orderDetail->product->assign;
        if ($params['action'] === 'add') {
            $productAssign->quantity = $productAssign->quantity - $params['quantity'];
        } else {
            $productAssign->quantity = $productAssign->quantity + $params['quantity'];
        }
        $productAssign->save();

        return $this->getOrderDetails();
    }

    public function removeProductFromOrder($orderId, array $params)
    {
        $orderDetail = OrderDetail::with('order')
            ->whereHas('order', function($query) use ($orderId) {
                $query->where('id', $orderId);
            })
            ->where('product_id', $params['product_id'])
            ->first();
        $orderDetail->delete();

        # Modify stock
        $productAssign = $orderDetail->product->assign;
        $productAssign->quantity = $productAssign->quantity + $params['quantity'] ?? 1;
        $productAssign->save();

        return $this->getOrderDetails();
    }

    public function removeOrder($orderId)
    {
        $order = Order::with(['orderDetails'])
            ->where('id', $orderId)
            ->first();

        foreach ($order->orderDetails as $orderDetail) {
            # Modify stock
            $productAssign = $orderDetail->product->assign;
            $productAssign->quantity = $productAssign->quantity + $orderDetail->quantity;
            $productAssign->save();

            $orderDetail->delete();
        }

        $order->status = Constants::ORDER_STATUS_CANCELLED;
        $order->save();

        $order->delete();

        return [];
    }

    public function placeOrder(array $params)
    {
        $user = Auth::user();
        $order = Order::find($params['order_id']);
        $payment = Meta::find($params['payment_id']);

        if ($order->orderDetails->isEmpty()) {
            throw new \Exception('Please add at least one item before placing the order');
        }

        if (!$order) {
            throw new \Exception('Order not found!', 404);
        }

        if (!$payment) {
            throw new \Exception('Payment not found!', 404);
        }

        $order->payment_id = $params['payment_id'];
        $order->status = Constants::ORDER_STATUS_PAID;
        $order->save();

        # Notify to telegram channel if setup
        $receiveInvoiceConfig = getReceiveInvoiceConfig();
        if ($receiveInvoiceConfig) {
            $message = __('date')." : ". Carbon::parse($order->date)->setTimezone(getTimezone())->format('y/m/d g:i A')."\n";
            $message .= __('branch')." : ". $user->getActiveBranch()->name ?? "Unknown Branch\n";
            $message .= __('order_number')." : <b>".str_pad($order->order_number, 4, '0', STR_PAD_LEFT)."</b>\n";
            $message .= __('order_details')."\n";
            $discount = 0;
            $total = 0;
            foreach ($order->orderDetails as $orderDetail) {
                $price = $orderDetail->quantity * $orderDetail->price;
                $discount += $orderDetail->discount_amount;
                $total += $price;
                $message .= "  ".$orderDetail->quantity."x  ".$orderDetail->product->name."  ".amountFormat(convertCentsToAmounts($price), getCurrencyCode())."\n";
            }
            $message .= __('discount')." : ".amountFormat(convertCentsToAmounts($discount), getCurrencyCode())."\n";
            $message .= "<b>".__('total')." : ".amountFormat(convertCentsToAmounts($total), getCurrencyCode())."</b>\n";
            $message .= __('payment_method')." : ".($order->payment->value);
            sendMessageToTelegram([
                'chat_id' => getTelegramChannelIDFormatted($receiveInvoiceConfig->value),
                'text' => $message,
                'parse_mode' => "HTML"
            ]);
        }

        return [];
    }
}
