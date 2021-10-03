<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Product;
use App\Category;
use File;
use App\Jobs\ProductJob;
use App\Jobs\MarketplaceJob;
use App\ProductVariant;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variant = ProductVariant::with(['product'])->orderBy('product_id', 'DESC');
        $product = Product::orderBy('name', 'DESC')->get();
        if (request()->q != '') {
            $variant = $variant->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $variant = $variant->paginate(10);
        return view('products.index', compact('variant', 'product'));
    }

    public function get($id)
    {
        $variant = ProductVariant::find($id);
        return json_encode($variant);
    }

    public function create()
    {
        $product = Product::get();
        return view('products.variant.create', compact('product'));
    }

    public function createVariant($id)
    {
        $product = Product::find($id);
        return view('products.variant.create', compact('product'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:100',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'minimum_stock' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:png,jpeg,jpg'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/variants', $filename);

            $product = ProductVariant::create([
                'product_id' => $request->product_id,
                'name' => $request->name,
                'slug' => $request->name,
                'price' => $request->price,
                'weight' => $request->weight,
                'minimum_stock' => $request->minimum_stock,
                'stock' => $request->stock,
                'image' => $filename
            ]);
            return redirect(route('product.index'))->with(['success' => 'Varian Produk Baru Ditambahkan!']);
        }
    }

    public function edit($id)
    {
        $variant = ProductVariant::find($id);
        $product = Product::orderBy('name', 'DESC')->get();
        return view('products.variant.edit', compact('variant', 'product'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'minimum_stock' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:png,jpeg,jpg'
        ]);

        $product = ProductVariant::find($id);
        $filename = $product->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/variants', $filename);
            File::delete(storage_path('app/public/variants/' . $product->image));
        }

        try{
            $product->update([
                'product_id' => $request->product_id,
                'name' => $request->name,
                'price' => $request->price,
                'weight' => $request->weight,
                'minimum_stock' => $request->minimum_stock,
                'stock' => $request->stock,
                'image' => $filename
            ]);
        } catch (Exception $e) {
            return redirect(route('product.index'))->with(['error' => $e->getMessage()]);
        }
        
        return redirect(route('product.index'))->with(['success' => 'Data Varian Produk Diperbaharui!']);
    }

    public function destroy($id)
    {
        $variant = ProductVariant::find($id);
        File::delete(storage_path('app/public/products/variants' . $variant->image));
        $variant->delete();
        return redirect(route('product.index'))->with(['success' => 'Varian Produk Sudah Dihapus']);
    }

    public function massUploadForm()
    {
        $category = Category::orderBy('name', 'DESC')->get();
        return view('products.bulk', compact('category'));
    }

    public function massUpload(Request $request)
    {
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