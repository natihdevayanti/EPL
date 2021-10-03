<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\ProductVariant;
use App\Category;
use File;
use App\Jobs\ProductJob;
use App\Jobs\MarketplaceJob;

class ProductController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type_id == 1) {
            $product = Product::with(['category', 'variant'])->orderBy('created_at', 'DESC');
            $category = Category::orderBy('name', 'DESC')->get();
            $variant = ProductVariant::with(['product'])->get();
            if (request()->q != '') {
                $product = $product->where('name', 'LIKE', '%' . request()->q . '%');
            }
            $product = $product->paginate(10);
            return view('products.index', compact('product', 'category', 'variant'));
        }
    }

    public function create()
    {
        if (Auth::user()->user_type_id == 1) {
            $category = Category::orderBy('name', 'DESC')->get();
            return view('products.create', compact('category'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->user_type_id == 1) {
            $this->validate($request, [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:100',
                'description' => 'required',
                'status' => 'required|boolean',
                'image' => 'required|image|mimes:png,jpeg,jpg'
            ]);
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/products', $filename);

                $product = Product::create([
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                    'slug' => $request->name,
                    'description' => $request->description,
                    'status' => $request->status,
                    'image' => $filename
                ]);

                $product_id = $product->id;

                $this->storeVariant($product_id, $request);

                return redirect(route('product.index'))->with(['success' => 'Produk dan Varian Baru Ditambahkan!']);
            }
        }
    }
    
    public function show($id)
    {
        $product = Product::where('id', $id)->with('variant', 'category')->first();
        return view('products.show', compact('product'));
    }

    public function storeVariant($product_id, Request $request)
    {
        if (Auth::user()->user_type_id == 1) {
            $this->validate($request, [
                'name_var' => 'required|string',
                'price' => 'required|integer',
                'weight' => 'required|integer',
                'minimum_stock' => 'required|integer',
                'stock' => 'required|integer',
                'image' => 'required|image|mimes:png,jpeg,jpg'
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . Str::slug($request->name_var) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/variants', $filename);

                $product = ProductVariant::create([
                    'product_id' => $product_id,
                    'name' => $request->name_var,
                    'slug' => $request->name_var,
                    'price' => $request->price,
                    'weight' => $request->weight,
                    'minimum_stock' => $request->minimum_stock,
                    'stock' => $request->stock,
                    'image' => $filename
                ]);
            }
        }
    }

    public function edit($id)
    {
        if (Auth::user()->user_type_id == 1) {
            $product = Product::find($id);
            $category = Category::orderBy('name', 'DESC')->get();
            return view('products.edit', compact('product', 'category'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->user_type_id == 1) {
            $this->validate($request, [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string',
                'description' => 'required',
                'status' => 'required|boolean',
                'image' => 'nullable|image|mimes:png,jpeg,jpg'
            ]);

            $product = Product::find($id);
            $filename = $product->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/products', $filename);
                File::delete(storage_path('app/public/products/' . $product->image));
            }

            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'image' => $filename
            ]);
            return redirect(route('product.index'))->with(['success' => 'Data Produk Diperbaharui!']);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->user_type_id == 1) {
            $product = Product::find($id);
            File::delete(storage_path('app/public/products/' . $product->image));
            $product->delete();

            $product_variants = ProductVariant::where('product_id', $id)->get();

            foreach($product_variants as $v) {
                $v->delete();
            }
            return redirect(route('product.index'))->with(['success' => 'Produk Sudah Dihapus!']);
        }
    }

    public function massUploadForm()
    {
        if (Auth::user()->user_type_id == 1) {
            $category = Category::orderBy('name', 'DESC')->get();
            return view('products.bulk', compact('category'));
        }
    }

    public function massUpload(Request $request)
    {
        if (Auth::user()->user_type_id == 1) {
            $this->validate($request, [
                'category_id' => 'required|exists:categories,id',
                'file' => 'required|mimes:xlsx'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '-product.' . $file->getClientOriginalExtension();
                $file->storeAs('public/uploads', $filename);

                ProductJob::dispatch($request->category_id, $filename);
                return redirect()->back()->with(['success' => 'Upload Produk Dijadwalkan']);
            }
        }
    }

    public function uploadViaMarketplace(Request $request)
    {
        $this->validate($request, [
            'marketplace' => 'required|string',
            'username' => 'required|string'
        ]);

        MarketplaceJob::dispatch($request->username, 10);
        return redirect()->back()->with(['success' => 'Produk Dalam Antrian']);
    }

    
}