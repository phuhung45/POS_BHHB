<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//     public function indexBackup(Request $request)
//     {
//         $colected = OrderItem::all()->sum(DB::raw('price'));
//         $total = Report::where('status', 1)->sum(DB::raw('price*quantity'));
//         // dd($total);
//         $start = Carbon::parse($request->start_date)->format('Y-m-d') . ' 00:00:00';
//         $end = Carbon::parse($request->end_date)->format('Y-m-d') . ' 23:59:59';
//         // $reports = new Report();
//         $reports = Report::all();

//         //empty start_date and empty end_date
//         if($request->end_date && empty($request->start_date)) {
//             // dump(1);
//             $reports = Report::where('created_at', '<=', date('Y-m-d'));
//             // dd($reports);
//             $total  = Report::where('status', 1)->where('created_at', '<=', $end)->sum(DB::raw('price*quantity'));
//             // dd($total);
//             $colected = OrderItem::where('created_at', '<=', $end)->sum(DB::raw('price'));
//         }
//         elseif($request->start_date && empty($request->end_date)) {
//             // dump(2);
//             // dump($start);
//             $reports = Report::where('created_at', '>=', date('Y-m-d'));
//             // dd($reports);
//             $total = Report::where('status', 1)->where('created_at', '>=', $start)->sum(DB::raw('price*quantity'));
//             $colected = OrderItem::where('created_at', '>=', $start)->sum(DB::raw('price'));
//         }
//         elseif($request->start_date && $request->end_date) {
//             // dump(3);
//             $reports = Report::whereBetween('created_at', [$start, $end])->get();
//             // dd($reports);
//             $total  = Report::where('created_at', '>=', $start)->where('created_at', '<=', $end . ' 23:59:59')->sum(DB::raw('price*quantity'));
//             $colected = OrderItem::where('created_at', '>=', $start)->where('created_at', '<=', $end . ' 23:59:59')->sum(DB::raw('price'));
//         }
// // dd($reports);
//         if ($request->search) {
//             $reports = $reports->where('name', 'LIKE', "%{$request->search}%");
//         }

//         $reports = Report::where('status', 1)->where('created_at', '<=', $end . ' 23:59:59')->latest()->paginate(10);
//         if (request()->wantsJson()) {
//             return response(Report::collection($reports));
//         }


//         // return view('reports.index', ['total' => $total], ['colected' => $colected])->with('reports', $reports);
//         return view('reports.index', compact('total', 'colected', 'reports'));
//     }

    public function index(Request $request)
    {
        $colected = OrderItem::all()->sum(DB::raw('price'));

        $reports = Report::select(['*', DB::raw('price * quantity as total_price')]);
        $reports->where(['status' => 1]);

        if (!empty($request->start_date)) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d') . ' 00:00:00';
            $reports->where('created_at', '>=', $startDate);
            $colected = OrderItem::where('created_at', '>=', $startDate)->sum(DB::raw('price'));
        }

        if (!empty($request->end_date)) {
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d') . ' 23:59:59';
            $reports->where('created_at', '<=', $endDate);
            $colected = OrderItem::where('created_at', '<=', $endDate)->sum(DB::raw('price'));
        }

        //total

        //colected
        if (!empty($request->search)) {
            $reports->where('name', 'LIKE', "%{$request->search}%");
        }

        $reports = $reports->latest()->paginate(10);
        $total = $reports->getCollection()->sum('total_price');

        if (request()->wantsJson()) {
            return response(Report::collection($reports));
        }


        // return view('reports.index', ['total' => $total], ['colected' => $colected])->with('reports', $reports);
        return view('reports.index', compact('total', 'colected', 'reports'));
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