<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reports = new Product();
        if ($request->search) {
            $reports = $reports->where('name', 'LIKE', "%{$request->search}%");
        }
        $reports = $reports->latest()->paginate(10);
        if (request()->wantsJson()) {
            return Product::collection($reports);
        }
        return view('reports.index')->with('reports', $reports);
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
        $report_price = $request->price;
        $report_quantity = $request->quantity;
        $report_total = $report_price * $report_quantity;
        return $report_total;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function total(Request $request)
    {
        $report_price = $request->price;
        $report_quantity = $request->quantity;
        $total = $report_price * $report_quantity;
        return $this->items->map(function ($total){
            return $total->price;
        })->sum();
    }
}
