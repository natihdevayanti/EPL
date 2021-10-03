<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\UserType;
use File;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type_id == 1) {
            $user = User::with(['user_type'])->orderBy('created_at');
            $user_type = UserType::orderBy('id')->get();
            if (request()->q != '') {
                $user = $user->where('name', 'LIKE', '%' . request()->q . '%');
            }
            $user = $user->paginate(10);
            return view('admin.admin_list', compact('user', 'user_type'));
        }
    }

    public function create()
    {
        if (Auth::user()->user_type_id == 1) {
            $user_type = UserType::orderBy('id')->get();
            return view('admin.admin_list', compact('user', 'user_type'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->user_type_id == 1) {
            $this->validate($request, [
                'user_type_id' => 'required|exists:user_types,id',
                'name' => 'required|string|max:100',
                'email' => 'required',
                'password' => 'required'
            ]);

            $user = User::create([
                'user_type_id' => $request->user_type_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return redirect(route('admin.index'))->with(['success' => 'Admin Baru Ditambahkan!']);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->user_type_id == 1) {
            $user = User::find($id);
            $user_type = UserType::orderBy('id')->get();
            return view('admin.admin_edit', compact('user', 'user_type'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->user_type_id == 1) {
            $this->validate($request, [
                'user_type_id' => 'required|exists:user_types,id',
                'name' => 'required|string|max:100',
                'email' => 'required'
            ]);

            $user = User::find($id);

            $user->update([
                'user_type_id' => $request->user_type_id,
                'name' => $request->name,
                'email' => $request->email
            ]);
            return redirect(route('admin.index'))->with(['success' => 'Data Admin Diperbaharui!']);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->user_type_id == 1) {
            $user = User::find($id);
            $user->delete();
            return redirect(route('admin.index'))->with(['success' => 'Admin Sudah Dihapus!']);
        }
    }

}