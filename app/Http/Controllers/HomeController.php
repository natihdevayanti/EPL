<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use stdClass;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\ProductVariant;
use App\Payment;
use App\SliderContent;

use Carbon\Carbon;
use PDF;
use DB;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $day_start = Carbon::today()->format('Y-m-d H:i:s');
        $day_end = Carbon::now()->format('Y-m-d H:i:s');
        $month_start = Carbon::now()->startOfMonth();
        $month_end = Carbon::now()->endOfMonth();
        
        $daily = new stdClass();
        $monthly = new stdClass();

        $finished = Order::where('status', 4)->whereBetween('updated_at', [$day_start, $day_end])->get();
        $daily->sold = 0;
        foreach ($finished as $f) {
            $daily->sold += OrderDetail::where('order_id', $f->id)->sum('qty');
        }

        $daily->income = Payment::where('status', 1)->whereBetween('updated_at', [$day_start, $day_end])->sum('amount');
        $daily->success = Payment::where('status', 1)->whereBetween('updated_at', [$day_start, $day_end])->count();
        $daily->failed = Order::where('status', 6)->whereBetween('updated_at', [$day_start, $day_end])->count();
        $daily->active_product = Product::where('status', 1)->count();
        $daily->on_process = Order::where('status', 2)->count();

        $finished = Order::where('status', 4)->whereBetween('updated_at', [$month_start, $month_end])->get();
        
        $monthly->sold = 0;
        foreach ($finished as $f) {
            $monthly->sold += OrderDetail::where('order_id', $f->id)->sum('qty');
        }

        $monthly->income = Payment::where('status', 1)->whereBetween('updated_at', [$month_start, $month_end])->sum('amount');
        $monthly->success = Payment::where('status', 1)->whereBetween('updated_at', [$month_start, $month_end])->count();
        $monthly->failed = Order::where('status', 6)->whereBetween('updated_at', [$month_start, $month_end])->count();

        $restock = ProductVariant::with('product')->whereRaw('stock <= minimum_stock')->get();
        $restock_qty = $restock->count();
        return view('home', compact('daily', 'monthly', 'restock', 'restock_qty'));
    }

    public function financeReport()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ' ,request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $monthly_income = Order::where('status', 2)->orWhere('status', 3)->orWhere('status', 4)->whereBetween('updated_at', [$start, $end])->sum('subtotal');
        $orders = Order::with(['district'])->where('status', 2)->orWhere('status', 3)->orWhere('status', 4)->whereBetween('created_at', [$start, $end])->get();
        return view('report.finance', compact('orders', 'monthly_income'));
    }

    public function productStatistic()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ' ,request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $product = Product::where('status', 1)->with(['variant'])->orderBy('name', 'ASC')->get();

        foreach($product as $p){
            foreach($p->variant as $pv){
                $pv['sold'] = OrderDetail::where('product_variant_id', $pv['id'])->where('order_status', "!=", 1)->whereBetween('updated_at', [$start, $end])->sum('qty');
            }
        }
        
        return view('report.product_statistic', compact('product'));
    }

    public function orderReportPdf($daterange)
    {
        $date = explode('+', $daterange);
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $orders = Order::with(['district'])->whereBetween('created_at', [$start, $end])->get();
        $pdf = PDF::loadView('report.order_pdf', compact('orders', 'date'));
        return $pdf->stream();
    }

    public function returnReport()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ' ,request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $orders = Order::with(['district'])->has('return')->whereBetween('created_at', [$start, $end])->get();
        return view('report.return', compact('orders'));
    }

    public function returnReportPdf($daterange)
    {
        $date = explode('+', $daterange);
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $orders = Order::with(['district'])->has('return')->whereBetween('created_at', [$start, $end])->get();
        $pdf = PDF::loadView('report.return_pdf', compact('orders', 'date'));
        return $pdf->stream();
    }

    public function homeManagement()
    {
        $slider_contents = SliderContent::all();
        $products = Product::orderBy('name', 'DESC')->get();
        $features = Product::where('is_featured', 1)->get();

        return view('admin.home_management', compact('slider_contents', 'features', 'products'));
    }

    public function deleteFromSlider($id)
    {
        $content = SliderContent::find($id);
        $content->delete();
        return redirect(route('admin.home_management'));
    }

    public function viewEditSlider($id)
    {
        $slider_content = SliderContent::find($id);
        return view('admin.edit_slider', compact('slider_content'));
    }

    public function viewAddSlider()
    {
        return view('admin.add_slider');
    }

    public function show($id)
    {
        $slider = SliderContent::where('id', $id)->first();
        $str = "storage/slider/" . $slider['image'];
        $slider['path'] = asset($str);
            return json_encode($slider);
    }

    public function addToSlider(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:50',
            'subtitle' => 'nullable|string',
            'text' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpeg,jpg'
        ]);

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/slider', $filename);

            $slider_content = SliderContent::create([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'text' => $request->text,
                'image' => $filename
            ]);

            return redirect(route('admin.home_management'))->with(['success' => 'Konten Slider Ditambahkan']);
        }
    }

    public function editSlider(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'title' => 'required|string|max:50',
            'subtitle' => 'nullable|string',
            'text' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpeg,jpg'
        ]);

        $content = SliderContent::find($request->id);
        $filename = $content->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/slider', $filename);
            File::delete(storage_path('app/public/slider/' . $content->image));
        }

        try{
            $content->update([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'text' => $request->text,
                'image' => $filename
            ]);
        } catch (Exception $e) {
            return redirect(route('admin.home_management'))->with(['error' => $e->getMessage()]);
        }
        
        return redirect(route('admin.home_management'))->with(['success' => 'Konten Slider Diperbaharui!']);
    }

    public function deleteFromFeatured($id)
    {
        $featured = Product::find($id);
        $featured->is_featured = false;
        $featured->save();

        return redirect(route('admin.home_management'))->with(['success' => 'Produk Fitur Dihapus']);
    }
    
    public function addToFeatured(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id'
        ]);

        $featured = Product::find($request->product_id);
        $featured->is_featured = true;
        $featured->save();

        return redirect(route('admin.home_management'))->with(['success' => 'Produk Fitur Ditambahkan']);
    }
}
