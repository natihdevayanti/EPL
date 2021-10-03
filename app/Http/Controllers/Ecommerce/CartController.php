<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\ProductVariant;
use App\Province;
use App\City;
use App\District;
use App\Customer;
use App\Order;
use App\OrderDetail;
use App\Cart;
use App\CartDetail;
use Illuminate\Support\Str;
use DB;
use App\Mail\CustomerRegisterMail;
use App\Mail\NoRegisterMail;
use Mail;
use Cookie;
use GuzzleHttp\Client;
use Carbon\Carbon;

class CartController extends Controller
{
    private function getCarts()
    {
        $carts = json_decode(request()->cookie('ecomm-carts'), true);
        $carts = $carts != '' ? $carts:[];
        return $carts;
    }

    public function addToCart(Request $request)
    {
        if($request->product_variant_id == "") {
            return redirect()->back()->with(['error' => 'Silakan pilih varian produk']);
        }

        $this->validate($request, [
            'product_variant_id' => 'required|exists:product_variants,id',
            'qty' => 'required|integer'
        ]);

        if (auth()->guard('customer')->check()) {
            $customer_id = auth()->guard('customer')->user()->id;
            if(!Cart::where('customer_id', $customer_id)->exists()) {
                Cart::create([
                    'customer_id' => $customer_id
                ]);
                DB::commit();
            }

            $carts_reg = Cart::where('customer_id', $customer_id)->first();
            $product_variant = ProductVariant::with('product')->find($request->product_variant_id);

            if(!CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $product_variant->id)->exists()){
                CartDetail::create([
                    'cart_id' => $carts_reg->id,
                    'product_variant_id' => $product_variant->id,
                    'price' => $product_variant->price * $request->qty,
                    'qty' => $request->qty
                ]);
            } else {
                $carts_variant = CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $product_variant->id)->first();
                $carts_variant->qty += $request->qty;
                $carts_variant->price += $product_variant->price * $request->qty;
                $carts_variant->save();
            }

            $carts_reg->total_cost += $request->qty * $product_variant->price;
            $carts_reg->save();

            return redirect()->back()->with(['success' => 'Produk berhasil ditambahkan ke keranjang']);
        }

        $carts = $this->getCarts();
        if ($carts && array_key_exists($request->product_variant_id, $carts)) {
            $carts[$request->product_variant_id]['qty'] += $request->qty;
        } else {
            $product_variant = ProductVariant::with('product')->find($request->product_variant_id);
            $carts[$request->product_variant_id] = [
                'qty' => $request->qty,
                'is_avail' => true,
                'product_id' => $product_variant->product->id,
                'product_name' => $product_variant->product->name,
                'product_image' => $product_variant->product->image,
                'product_variant_id' => $product_variant->id,
                'product_variant_name' => $product_variant->name,
                'product_variant_price' => $product_variant->price,
                'product_variant_image' => $product_variant->image,
                'product_variant_weight' => $product_variant->weight
            ];
        }

        $cookie = cookie('ecomm-carts', json_encode($carts), 2880);
        return redirect()->back()->with(['success' => 'Produk berhasil ditambahkan ke keranjang'])->cookie($cookie);
    }

    public function listCart()
    {
        $carts_reg = NULL;

        if(auth()->guard('customer')->check()) {
            $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();
            if(CartDetail::where('cart_id', $carts_reg->id) == NULL) {
                $carts_reg->variant = NULL;
            } else {
                $carts_reg->with('details.variant.product');
            }
        }

        $carts = $this->getCarts();
        $subtotal = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_variant_price'];
        });
        return view('ecommerce.cart', compact('carts', 'subtotal', 'carts_reg'));
    }

    public function updateSeamlessReg(Request $request) {
        $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();
        $product_variant = ProductVariant::where('id', $request->id)->first();
        $carts_variant = CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $request->id)->first();

        if ($request->qty == 0) {
            CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $row)->delete();
        }

        $carts_variant->update([
            'qty' => $request->qty,
            'price' => $request->qty * $product_variant->price
        ]);

        if($product_variant->stock < $carts_variant->qty) {
            $carts_variant->is_avail = false;
        } else {
            $carts_variant->is_avail = true;
        }

        $carts_variant->save();

        $total_cost = CartDetail::where('cart_id', $carts_reg->id)->sum('price');
        $carts_reg->total_cost = $total_cost;
        $carts_reg->save();

        $return = array();
        $return['subtotal'] = $carts_reg->total_cost;
        $return['variant'] = $carts_variant;

        return json_encode($return);
    }

    public function updateSeamlessUnreg(Request $request) {
        $return = array();
        $carts = $this->getCarts();
        if ($request->qty == 0) {
            unset($carts[$request->id]);
        } else {
            $carts[$request->id]['qty'] = $request->qty;
            $variant = ProductVariant::where('id', $request->id)->first();
            if($variant->stock >= $carts[$request->id]['qty']) {
                $carts[$request->id]['is_avail'] = true;
            } else {
                $carts[$request->id]['is_avail'] = false;
            }
        }
        $subtotal = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_variant_price'];
        });
        $return['carts'] = $carts;
        $return['subtotal'] = $subtotal;
        
        $cookie = cookie('ecomm-carts', json_encode($carts), 2880);
        return response()->json($return)->cookie($cookie);
    }

    public function updateCart(Request $request)
    {
        if (auth()->guard('customer')->check()) {
            $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();

            foreach ($request->product_variant_id as $key => $row) {
                $product_variant = ProductVariant::where('id', $row)->first();
                if ($request->qty[$key] == 0) {
                    CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $row)->delete();
                } else {
                    CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $row)->update([
                        'qty' => $request->qty[$key],
                        'price' => $request->qty[$key] * $product_variant->price
                    ]);

                    $carts_variant = CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $row)->first();
                    // $variant = ProductVariant::where('id', $request->product_variant_id[$key])->first();

                    if($product_variant->stock < $carts_variant->qty) {
                        $carts_variant->is_avail = false;
                    } else {
                        $carts_variant->is_avail = true;
                    }
                    $carts_variant->save();

                }
            }

            $total_cost = CartDetail::where('cart_id', $carts_reg->id)->sum('price');
            $carts_reg->total_cost = $total_cost;
            $carts_reg->save();
            return json_encode($carts_reg);
        }

        $carts = $this->getCarts();
        foreach ($request->product_variant_id as $key => $row) {
            if ($request->qty[$key] == 0) {
                unset($carts[$row]);
            } else {
                $carts[$row]['qty'] = $request->qty[$key];
                $variant = ProductVariant::where('id', $row)->first();
                if($variant->stock >= $carts[$row]['qty']) {
                    $carts[$row]['is_avail'] = true;
                } else {
                    $carts[$row]['is_avail'] = false;
                }
            }
        }
        $cookie = cookie('ecomm-carts', json_encode($carts), 2880);
        return redirect()->back()->cookie($cookie);
    }

    public function removeFromCart($id)
    {
        if (auth()->guard('customer')->check()) {
            $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();
            CartDetail::where('cart_id', $carts_reg->id)->where('product_variant_id', $id)->delete();
            $total_cost = CartDetail::where('cart_id', $carts_reg->id)->sum('price');
            $carts_reg->total_cost = $total_cost;
            $carts_reg->save();
            return redirect()->back();
        }

        $carts = $this->getCarts();
        if ($carts && array_key_exists($id, $carts)) {
            unset($carts[$id]);
        }
        $cookie = cookie('ecomm-carts', json_encode($carts), 2880);
        return redirect()->back()->cookie($cookie);
    }

    public function checkout()
    {
        $provinces = Province::where('name', 'Jawa Timur')->get();
        $unavail = false;
        $carts_reg = NULL;

        if (auth()->guard('customer')->check()) {
            $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();
            $carts_detail = CartDetail::where('cart_id', $carts_reg->id)->get();
            $weight = 0;

            foreach($carts_detail as $cd) {
                $variant = ProductVariant::where('id', $cd->product_variant_id)->first();
                if($variant->stock < $cd->qty) {
                    $cd->is_avail = false;
                    $unavail = true;
                } else {
                    $cd->is_avail = true;
                }
                $weight += $variant->weight * $cd->qty;
            }

            if($unavail) {
                return redirect()->back();
            }

            return view('ecommerce.checkout', compact('provinces', 'weight'));
        }

        $carts = $this->getCarts();
        foreach ($carts as $key => $value) {
            $variant = ProductVariant::where('id', $value['product_variant_id'])->first();
            if($variant->stock >= $value['qty']) {
                $carts[$key]['is_avail'] = true;
            } else {
                $carts[$key]['is_avail'] = false;
                $unavail = true;
            }
            # code...
        }

        $cookie = cookie('ecomm-carts', json_encode($carts), 2880);
        if($unavail) {
            return redirect()->back()->cookie($cookie);
        }

        $subtotal = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_variant_price'];
        });
        $weight = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_variant_weight'];
        });
        return view('ecommerce.checkout', compact('provinces', 'carts', 'subtotal', 'weight'));
    }

    public function getCity()
    {
        $cities = City::where('province_id', request()->province_id)->get();
        return response()->json(['status' => 'success', 'data' => $cities]);
    }

    public function getDistrict()
    {
        $districts = District::where('city_id', request()->city_id)->get();
        return response()->json(['status' => 'success', 'data' => $districts]);
    }

    public function processCheckout(Request $request)
    {
        $this->validate($request, [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required',
            'email' => 'required|email',
            'customer_address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'courier' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $customer = Customer::where('email', $request->email)->first();
            if (!auth()->guard('customer')->check() && $customer) {
                return redirect()->back()->with(['error' => 'E-mail sudah terdaftar. Silakan login terlebih dahulu.']);
            }

            $subtotal = 0;
            $password = Str::random(8);

            if (!auth()->guard('customer')->check()) {
                $carts = $this->getCarts();
                $subtotal = collect($carts)->sum(function($q) {
                    return $q['qty'] * $q['product_variant_price'];
                });

                if ($request->make_account_value == "true") {
                    $customer = Customer::create([
                        'name' => $request->customer_name,
                        'email' => $request->email,
                        'password' => $password,
                        'phone_number' => $request->customer_phone,
                        'address' => $request->customer_address,
                        'district_id' => $request->district_id,
                        'activate_token' => Str::random(30),
                        'status' => false
                    ]);

                    $customer_id = $customer->id;

                    $carts_reg = Cart::create([
                        'customer_id' => $customer_id,
                        'total_cost' => 0
                    ]);
                } else {
                    $customer_id = 0;
                }
            } else {
                $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();
                $carts_detail = CartDetail::where('cart_id', $carts_reg->id)->get();

                $customer_id = $customer->id;
                foreach($carts_detail as $cd) {
                    $subtotal += $cd->price;
                }
            }

            $shipping = explode('-', $request->courier);
            $order = Order::create([
                'invoice' => Str::random(4) . '-' . time(),
                'customer_id' => $customer_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'district_id' => $request->district_id,
                'subtotal' => $subtotal,
                'total_cost' => $subtotal + $shipping[2],
                'shipping_cost' => $shipping[2],
                'shipping' => $shipping[0] . '-' . $shipping[1],
                'free_access' => false
            ]);
            
            $order_created = Carbon::create($order->created_at->toDateTimeString());
            $order->invalid_at = $order_created->addMinutes(90)->toDateTimeString();
            $order->save();
            
            if(auth()->guard('customer')->check()) {
                $carts = CartDetail::where('cart_id', $carts_reg->id)->get();
                
                foreach ($carts as $row) {
                    $variant = ProductVariant::where('id', $row->product_variant_id)->first();
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $row->product_variant_id,
                        'price' => $variant->price,
                        'qty' => $row->qty,
                        'weight' => $variant->weight
                    ]);

                    $variant->stock -= $row->qty;
                    $variant->save();

                    $row->delete();
                }
            } else {
                foreach ($carts as $row) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $row['product_variant_id'],
                        'price' => $row['product_variant_price'],
                        'qty' => $row['qty'],
                        'weight' => $row['product_variant_weight']
                    ]);

                    $product = ProductVariant::where('id', $row['product_variant_id'])->first();
                    $product->stock -= $row['qty'];
                    $product->save();
                }
            }

            DB::commit();

            $carts = [];
            $cookie = cookie('ecomm-carts', json_encode($carts), 2880);
 
            if (!auth()->guard('customer')->check() && $request->make_account_value == "true") {
                $customer = Customer::where('email', $request->email)->first();
                Mail::to($request->email)->send(new CustomerRegisterMail($customer, $password));
            } else if (!auth()->guard('customer')->check() && $request->make_account_value == "false") {
                Mail::to($request->email)->send(new NoRegisterMail($order));
                $order->free_access = true;
                $order->save();
            }
            return redirect(route('front.finish_checkout', $order->invoice))->cookie($cookie);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function checkoutFinish($invoice)
    {
        $order = Order::with(['district.city'])->where('invoice', $invoice)->first();
        return view('ecommerce.checkout_finish', compact('order'));
    }

    public function getCourier(Request $request)
    {
        $this->validate($request, [
            'destination' => 'required',
            'weight' => 'required|integer'
        ]);

        $url = 'https://ruangapi.com/api/v1/shipping';
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'd1JlYPgwNExLRQl6jUSyfZOCoN7SxpBk8bU6gN3D'
            ],
            'form_params' => [
                'origin' => 22,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => 'jnt,sicepat'
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body;
    }
}
