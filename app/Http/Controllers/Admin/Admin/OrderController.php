<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusChanged;
use App\Models\NotificationLog;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->withCount('items')
            ->with('payments')
            ->when(! $request->filled('payment'), fn ($q) => $q->kitchenVisible())
            ->when($request->payment === 'paid', fn ($q) => $q->whereHas('payments', fn ($p) => $p->where('method', 'card')->where('status', 'paid')))
            ->when($request->payment === 'pickup', fn ($q) => $q->whereDoesntHave('payments'))
            ->when($request->payment === 'awaiting', fn ($q) => $q->whereHas('payments', fn ($p) => $p->where('method', 'card')->where('status', 'pending')))
            ->when($request->payment === 'failed', fn ($q) => $q->whereHas('payments', fn ($p) => $p->where('method', 'card')->where('status', 'failed')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('pickup_time', $request->date('date')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%' . $request->string('search') . '%';
                $q->where(fn ($w) => $w->where('order_number', 'like', $term)
                    ->orWhere('customer_name', 'like', $term)
                    ->orWhere('customer_email', 'like', $term));
            })
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'confirmed' THEN 1 WHEN 'preparing' THEN 2 WHEN 'ready' THEN 3 ELSE 4 END")
            ->orderBy('pickup_time')
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items', 'addons', 'payments');

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        $changed = $order->status !== $data['status'];
        $order->update($data);

        if ($changed && in_array($data['status'], ['ready', 'cancelled'], true) && $order->customer_email) {
            $this->notifyCustomer($order);
        }

        return back()->with('status', 'Order status updated.');
    }

    protected function notifyCustomer(Order $order): void
    {
        $status = 'sent';

        try {
            Mail::to($order->customer_email)->send(new OrderStatusChanged($order));
        } catch (\Throwable $e) {
            $status = 'failed';
            Log::error("Order status email failed for {$order->order_number}: " . $e->getMessage());
        }

        NotificationLog::create([
            'notifiable_type' => Order::class,
            'notifiable_id' => $order->id,
            'channel' => 'email',
            'recipient' => $order->customer_email,
            'message' => "Order {$order->order_number} marked {$order->status}; customer notified.",
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
