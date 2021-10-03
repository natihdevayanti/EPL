<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Customer;
use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    public function signupForm(){
        return view('ecommerce.signup');
    }

    protected function signup(Request $request){
        $this->validate($request, [
            'customer_name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $cust_email = Customer::where('email', $request->email)->first();
            if ($cust_email !== NULL) {
                return redirect()->back()->with(['error' => 'Akun sudah terdaftar, silakan login']);
            }

        $customer = Customer::create([
            'name' => $request->customer_name,
            'email' => $request->email,
            'password' => $request->password,
            'status' => true
        ]);

        Cart::create([
            'customer_id' => $customer->id,
            'total_cost' => 0
        ]);

        $auth = $request->only('email', 'password');
        $auth['status'] = 1;
        if (auth()->guard('customer')->attempt($auth)) {
            return redirect()->intended(route('customer.dashboard'));
        }
    }
}