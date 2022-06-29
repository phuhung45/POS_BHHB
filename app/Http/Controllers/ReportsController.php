<?php

namespace App\Http\Controllers;

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
        $colected = OrderItem::all()->sum(DB::raw('price'));
        $total = Report::where('status', 1)->sum(DB::raw('price*quantity'));
        // dd($total);
        $end = $request->end_date;
        // dd($end);
        $start = $request->start_date;
        // dd($start);
        $reports = new Report();
        $reports = Report::all()->get();
        
        
        if($request->end_date && empty($request->start_date)) {
            $total  = Report::where('status', 1)->where('created_at', '<=', $end)->sum(DB::raw('price*quantity'));
            $colected = OrderItem::where('created_at', '<=', $end . ' 23:59:59')->sum(DB::raw('price'));
            $reports = Report::where('created_at', '<=', $end . ' 23:59:59');
        }
        elseif($request->start_date && empty($request->end_date)) {
            $total = Report::where('status', 1)->where('created_at', '>=', $start)->sum(DB::raw('price*quantity'));
            $colected = OrderItem::where('created_at', '>=', $start)->sum(DB::raw('price'));
            $reports = Report::where('created_at', '>=', $start);
        }
        elseif($request->start_date && $request->end_date) {
            $total  = Report::where('created_at', '>=', $start)->where('created_at', '<=', $end . ' 23:59:59')->sum(DB::raw('price*quantity'));
            $colected = OrderItem::where('created_at', '>=', $start)->where('created_at', '<=', $end . ' 23:59:59')->sum(DB::raw('price'));
            $reports = Report::where('created_at', '>=', $start)->where('created_at', '<=', $end . ' 23:59:59')->get('id');
        } 

        $reports = new Report();
        if ($request->search) {
            $reports = $reports->where('name', 'LIKE', "%{$request->search}%");
        }
        
        // $reports = $reports->with('Report')->latest()->paginate(10);
        $reports = Report::where('status', 1)->latest()->paginate(10);
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
    public function store(Request $request)
    {
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('reports', 'public');
        }

        $report = Report::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->status
        ]);

        if (!$report) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating product.');
        }
        return redirect()->route('reports.index')->with('success', 'Success, you product have been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
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
     * @param  \App\Models\Report  $report
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
        return redirect()->route('reports.index')->with('success', 'Success, your product have been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
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