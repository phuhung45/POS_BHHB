<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Report;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $colected = new OrderItem();
        $colected = OrderItem::all();
        $colected = OrderItem::sum('price');
        $total = Report::where('status', 1)->sum(DB::raw('price*quantity'));
        // dd($total);
        $end = $request->end_date;
        // dd($end);
        $start = $request->start_date;
        // dd($start);
        $reports = new Report();
        //
        if($request->start_date) {
            $total = Report::where('status', 1)->where('created_at', '>=', $start)->sum(DB::raw('price*quantity'));
            $reports = Report::where('created_at', '>=', $start);
        }
        else if($request->end_date) {
            $total  = Report::where('status', 1)->where('created_at', '<=', $end)->sum(DB::raw('price*quantity'));
            $reports = Report::where('created_at', '<=', $end . ' 23:59:59');
        }
        // if($request->end_date && $request->start_date) {
        //     $total = Product::where('status', 1)->where('created_at', '<=', $end && 'created_at', '>=', $start)->sum(DB::raw('price*quantity'));
        //     $reports = Product::where('created_at', '>=', $start && 'created_at', '<=', $end . ' 23:59:59');
        // }
        // if ($request->search) {
        //     $reports = $reports->where('name', 'LIKE', "%{$request->search}%");
        // }
        $reports = $reports->latest()->paginate(10);
        if (request()->wantsJson()) {
            return response(Report::collection($reports));
        }
        return view('reports.index', ['total' => $total], ['colected' => $colected])->with('reports', $reports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $report_price = $request->price;
        $report_quantity = $request->quantity;
        $report_total = $report_price * $report_quantity;
        return $report_total;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        return view('reports.edit')->with('report', $report);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->name = $request->name;
        $request->description = $request->description;
        $request->barcode = $request->barcode;
        $request->price = $request->price;
        $request->quantity = $request->quantity;
        $request->status = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($request->image) {
                Storage::delete($request->image);
            }
            // Store image
            $image_path = $request->file('image')->store('requests', 'public');
            // Save to Database
            $request->image = $image_path;
        }

        if (!$request->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating product.');
        }
        return redirect()->route('products.index')->with('success', 'Success, your product have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->image) {
            Storage::delete($request->image);
        }
        $request->delete();

        return response()->json([
            'success' => true
        ]);
    }
}