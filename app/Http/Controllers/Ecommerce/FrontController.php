<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Category;
use App\Customer;
use App\Province;
use App\Order;
use App\SliderContent;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)->with('variant')->orderBy('created_at', 'DESC')->limit(5)->get();
        $slider_contents = SliderContent::all();
        $features = Product::where('status', 1)->where('is_featured', 1)->get();

        return view('ecommerce.index', compact('products', 'slider_contents', 'features'));
    }

    public function about() 
    { 
        return view('ecommerce.about');
    }
    
    public function product()
    {
        $products = Product::where('status', 1)->orderBy('created_at', 'DESC');
        $categories = Category::all();

        $str = request()->q;
        $searchVal = preg_split('/\s+/', $str, -1, PREG_SPLIT_NO_EMPTY);

        if (request()->q != '') {
            $products = $products->where(function($q) use ($searchVal) {
                foreach ($searchVal as $val){
                    $q->orWhere('name', 'like', "%{$val}%");
                }
            });
        }
        
        $products = $products->paginate(12);
        return view('ecommerce.product', compact('products', 'categories'));
    }

    public function categoryProduct($slug)
    {
        $products = Category::where('slug', $slug)->first()->product()->orderBy('created_at', 'DESC')->paginate(12);
        return view('ecommerce.product', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'variant'])->where('id', $id)->first();
        return view('ecommerce.show', compact('product'));
    }

    public function verifyCustomerRegistration($token)
    {
        $customer = Customer::where('activate_token', $token)->first();
        $msg = "error";
        if ($customer) {
            $customer->update([
                'status' => 1
            ]);
            $msg = "success";
        }

        return view('ecommerce.set_password', compact('customer', 'msg'));
    }

    public function setPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|string'
        ]);

        $customer = Customer::where('email', $request->email)->first();
    
        if($customer) {
            $customer->update([
                'password' => $request->password,
                'activate_token' => null
            ]);

            $auth = $request->only('email', 'password');
            $auth['status'] = 1;
            if (auth()->guard('customer')->attempt($auth)) {
                return redirect()->intended(route('customer.dashboard'));
            }
        }
        
        return route('customer.login')->with(['error' => 'Password Gagal Diubah, Login Menggunakan Password Sementara']);
    }

    public function customerSettingForm()
    {
        $customer = auth()->guard('customer')->user()->load('district');
        $provinces = Province::orderBy('name', 'ASC')->get();
        return view('ecommerce.setting', compact('customer', 'provinces'));
    }

    public function customerUpdateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'phone_number' => 'required|max:15',
            'address' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'password' => 'nullable|string|min:6'
        ]);

        $user = auth()->guard('customer')->user();
        $data = $request->only('name', 'phone_number', 'address', 'district_id');
        if ($request->password != '') {
            $data['password'] = $request->password;
        }
        $user->update($data);
        return redirect()->back()->with(['success' => 'Profil berhasil diperbaharui']);
    }

    public function getVariantInfo($var)
    {
        return DB::select("SELECT name, weight, stock, price, image FROM product_variants WHERE id=".$var);
    }

    public function findOrder()
    {
        return view('ecommerce.find_order');
    }

    public function showOrder(Request $request)
    {
        $this->validate($request, [
            'invoice' => 'required|string'
        ]);

        $order = Order::where('invoice', $request->invoice)->first();
        if($order) {
            if($order->free_access == 1) {
                return redirect(route('customer.view_order_unregistered', ['invoice' => $order->invoice]));
            } else if($order->free_access == 0) {
                return redirect()->back()->with(['error' => 'Silakan LOG IN untuk dapat melihat detail pesanan '. $request->invoice]);
            }    
        }
        return redirect()->back()->with(['error' => 'Pesanan '. $request->invoice . ' tidak ditemukan']);
    }
}
