<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\OrderDetail;
use App\ProductVariant;
use App\Mail\OrderMail;
use Mail;

class OrderController extends Controller
{
    public function index()
    {
        
        if (Auth::user()->user_type_id == 2) {
            $orders = Order::with(['district.city.province'])
            ->withCount('return')
            ->where(function ($q) {
                $q->where('status', 0)->orWhere('status', 1);
            })
            ->orderBy('created_at', 'DESC');
        } else if (Auth::user()->user_type_id == 3) {
            $orders = Order::with(['district.city.province'])
            ->withCount('return')
            ->where(function ($q) {
                $q->where('status', 2)->orWhere('status', 3);
            })
            ->orderBy('created_at', 'DESC');
        } else {
            $orders = Order::with(['district.city.province'])
            ->withCount('return')
            ->where(function ($q) {
                $q->where('status', 0)->orWhere('status', 1)->orWhere('status', 2)->orWhere('status', 3);
            })
            ->orderBy('created_at', 'DESC');
        }

        if (request()->q != '') {
            $orders = $orders->where(function($q) {
                $q->where('customer_name', 'LIKE', '%' . request()->q . '%')
                ->orWhere('invoice', 'LIKE', '%' . request()->q . '%')
                ->orWhere('customer_address', 'LIKE', '%' . request()->q . '%');
            });
        }

        if (request()->status != '') {
            $orders = $orders->where('status', request()->status);
        }

        $orders = $orders->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function ordersDone()
    {
        $orders = Order::with(['district.city.province'])
        ->withCount('return')
        ->where(function ($q) {
            $q->where('status', 4)->orWhere('status', 6);
        })
        ->orderBy('created_at', 'DESC');
        

        if (request()->q != '') {
            $orders = $orders->where(function($q) {
                $q->where('customer_name', 'LIKE', '%' . request()->q . '%')
                ->orWhere('invoice', 'LIKE', '%' . request()->q . '%')
                ->orWhere('customer_address', 'LIKE', '%' . request()->q . '%');
            });
        }

        if (request()->status != '') {
            $orders = $orders->where('status', request()->status);
        }

        $orders = $orders->paginate(10);
        return view('orders.done', compact('orders'));
    }

    public function ordersPending()
    {
        $orders = Order::with(['district.city.province'])
        ->withCount('return')
        ->where(function ($q) {
            $q->where('status', 5);
        })
        ->orderBy('created_at', 'DESC');
        

        if (request()->q != '') {
            $orders = $orders->where(function($q) {
                $q->where('customer_name', 'LIKE', '%' . request()->q . '%')
                ->orWhere('invoice', 'LIKE', '%' . request()->q . '%')
                ->orWhere('customer_address', 'LIKE', '%' . request()->q . '%');
            });
        }

        if (request()->status != '') {
            $orders = $orders->where('status', request()->status);
        }

        $orders = $orders->paginate(10);
        return view('orders.orderPending', compact('orders'));
    }

    public function view($invoice)
    {
        $order = Order::with(['district.city.province', 'payment', 'details.variant'])->where('invoice', $invoice)->first();
        return view('orders.view', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->details()->delete();
        $order->payment()->delete();
        $order->delete();
        return redirect(route('orders.index'));
    }

    public function acceptPayment($invoice)
    {
        $order = Order::with(['payment'])->where('invoice', $invoice)->first();
        $order->payment()->update(['status' => 1]);
        $order->update(['status' => 2]);
        return redirect(route('orders.view', $order->invoice));
    }

    public function shippingOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->update(['tracking_number' => $request->tracking_number, 'status' => 3]);
        Mail::to($order->customer_email)->send(new OrderMail($order));
        return redirect()->back();
    }

    public function finishOrder($invoice)
    {
        $order = Order::where('invoice', $invoice)->first();
        $order->update(['status' => 4]);

        return redirect(route('orders.view', $order->invoice));
    }

    public function return($invoice)
    {
        $order = Order::with(['return'])->where('invoice', $invoice)->first();
        return view('orders.return', compact('order'));
    }

    public function approveReturn(Request $request)
    {
        $this->validate($request, ['status' => 'required']);
        $order = Order::find($request->order_id);
        $order->return()->update(['status' => $request->status]);
        $order->update(['status' => 4]);
        return redirect()->back();
    }

    public function pendingPayment($invoice)
    {
        $order = Order::with(['payment'])->where('invoice', $invoice)->first();
        $order->payment()->update(['status' => 0]);
        $order->update(['status' => 5]);
        return redirect(route('orders.view', $order->invoice));
    }

    public function cancelPayment($invoice)
    {
        $order = Order::with(['payment'])->where('invoice', $invoice)->first();
        $order->payment()->update(['status' => 2]);
        $order->update(['status' => 6]);

        $order_details = OrderDetail::with(['variant'])->where('order_id', $order->id)->get();
        foreach($order_details as $od) {
            $product_variant = ProductVariant::find($od->product_variant_id);
            $product_variant->stock += $od->qty;
            $product_variant->save();
            $od->update(['order_status' => 1]);
        }

        return redirect(route('orders.view', $order->invoice));
    }
}

