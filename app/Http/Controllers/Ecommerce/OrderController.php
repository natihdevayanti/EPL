<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use App\ProductVariant;
use App\Payment;
use Carbon\Carbon;
use App\OrderReturn;
use Illuminate\Support\Str;
use DB;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withCount(['return'])->where('customer_id', auth()->guard('customer')->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('ecommerce.orders.index', compact('orders'));
    }

    public function view($invoice)
    {
        $order = Order::with(['district.city.province', 'details.variant', 'payment'])->where('invoice', $invoice)->first();
        if (\Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order) || $order->free_access == 1) {
            return view('ecommerce.orders.view', compact('order'));
        }
        return redirect(route('customer.orders'))->with(['error' => 'Anda Tidak Diizinkan Untuk Mengakses Order Orang Lain']);
    }

    public function paymentForm()
    {
        return view('ecommerce.payment');
    }

    public function setPaymentDestination(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|exists:orders,id',
            'transfer_to' => 'required|string'
        ]);

        DB::beginTransaction();
        
        try {
            $order = Order::find($request->order_id);
            
            if($order) {
                $payment = Payment::where('order_id', $order->id)->first();

                if($payment) {
                    $payment->update(['transfer_to' => $request->transfer_to]);
                } else {
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'name' => null,
                        'transfer_to' => $request->transfer_to,
                        'transfer_date' => null,
                        'amount' => 0,
                        'proof' => null,
                        'status' => false
                    ]);

                    DB::commit();
                }

                if($order->free_access == 1) {
                    return redirect(route('customer.view_order_unregistered', ['invoice' => $order->invoice]));
                } else {
                    return redirect(route('customer.view_order', ['invoice' => $order->invoice]));
                }
            }
            return redirect()->back()->with(['error' => 'Pesanan Tidak Ada']);
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function storePayment(Request $request)
    {
        $this->validate($request, [
            'invoice' => 'required|exists:orders,invoice',
            'name' => 'required|string',
            'transfer_to' => 'required|string',
            'transfer_date' => 'required',
            'amount' => 'required|integer',
            'proof' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::where('invoice', $request->invoice)->first();
            if ($order->total_cost != $request->amount) return redirect()->back()->with(['error' => 'Error, Pembayaran Harus Sama Dengan Tagihan']);

            if ($order->status == 0 && $request->hasFile('proof')) {
                $file = $request->file('proof');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/payment', $filename);

                $payment = Payment::where('order_id', $order->id)->first();

                $order_invalid = Carbon::create($order->invalid_at);
                $now = Carbon::now()->format('Y-m-d H:i:s');

                if($order_invalid->isBefore($now) && $order->status != 6) {
                    $order->update(['status' => 6]);
                    $payment->update(['status' => 2]);

                    DB::commit();
                    
                    return redirect()->back()->with(['error' => 'Error, Batas Waktu Pembayaran Habis']);
                }

                $payment->update([
                    'name' => $request->name,
                    'transfer_to' => $request->transfer_to,
                    'transfer_date' => Carbon::parse($request->transfer_date)->format('Y-m-d'),
                    'amount' => $request->amount,
                    'proof' => $filename,
                    'status' => false
                ]);
                $order->update(['status' => 1]);

                DB::commit();
                
                if($order->free_access == 1) {
                    return redirect(route('customer.view_order_unregistered', ['invoice' => $request->invoice]));
                } else {
                    return redirect(route('customer.view_order', ['invoice' => $request->invoice]));
                }                
            }
            return redirect()->back()->with(['error' => 'Error, Upload Bukti Transfer']);
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function pdf($invoice)
    {
        $order = Order::with(['district.city.province', 'details', 'details.variant', 'payment'])
            ->where('invoice', $invoice)->first();
        if (!\Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order)) {
            return redirect(route('customer.view_order', $order->invoice));
        }

        $pdf = PDF::loadView('ecommerce.orders.pdf', compact('order'));
        return $pdf->stream();
    }

    public function acceptOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!\Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order) && $order->free_access == 0) {
            return redirect()->back()->with(['error' => 'Bukan Pesanan Kamu']);
        }

        $order->update(['status' => 4]);
        return redirect()->back()->with(['success' => 'Pesanan Dikonfirmasi']);
    }

    public function returnForm($invoice)
    {
        $order = Order::where('invoice', $invoice)->first();
        $order->update([
            'status' => 5
        ]);
        return view('ecommerce.orders.return', compact('order'));
    }

    public function processReturn(Request $request, $id)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'refund_transfer' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        $return = OrderReturn::where('order_id', $id)->first();
        if ($return) return redirect()->back()->with(['error' => 'Permintaan Refund Dalam Proses']);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/return', $filename);

            OrderReturn::create([
                'order_id' => $id,
                'photo' => $filename,
                'reason' => $request->reason,
                'refund_transfer' => $request->refund_transfer,
                'status' => 0
            ]);
            $order = Order::find($id);
            $this->sendMessage($order->invoice, $request->reason);
            return redirect()->back()->with(['success' => 'Permintaan Refund Dikirim']);
        }
    }

    public function cancelOrder($invoice)
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

        if ($order->free_access == 0)
            return redirect(route('customer.view_order', $invoice));
        else
            return redirect(route('customer.view_order_unregistered', $invoice));
    }
}
