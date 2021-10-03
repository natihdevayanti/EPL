<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Customer;
use Cookie;

class LoginController extends Controller
{
    public function loginForm()
    {
        if (auth()->guard('customer')->check()) return redirect(route('customer.dashboard'));
        return view('ecommerce.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|string'
        ]);

        $auth = $request->only('email', 'password');
        $auth['status'] = 1;
        if (auth()->guard('customer')->attempt($auth)) {
            return redirect()->intended(route('customer.dashboard'));
        }
        return redirect()->back()->with(['error' => 'Email / Password Salah']);
    }

    public function dashboard()
    {
        $orders = Order::selectRaw('COALESCE(sum(CASE WHEN status = 0 THEN total_cost END), 0) as pending, 
            COALESCE(count(CASE WHEN status = 3 THEN total_cost END), 0) as shipping,
            COALESCE(count(CASE WHEN status = 4 THEN total_cost END), 0) as completeOrder')
            ->where('customer_id', auth()->guard('customer')->user()->id)->get();
        
        return view('ecommerce.dashboard', compact('orders'));
    }

    public function logout()
    {
        $cookie = Cookie::forget('ecomm-carts');
        auth()->guard('customer')->logout();
        
        return redirect(route('customer.login'))->withCookie($cookie);
    }

    
}
