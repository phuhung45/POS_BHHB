<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
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
        $users = User::all();
        // dd($users);
        $receipts = Receipt::select(DB::raw('price'));
        $receipts->where(['status' => 1]);
        // dd($receipts);
        // $colected = Receipt::where('status', 1)->sum(DB::raw('price'));

        if (!empty($request->start_date)) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d') . ' 00:00:00';
            $receipts->where('created_at', '>=', $startDate);
            // $colected = Receipt::where('created_at', '>=', $startDate)->sum(DB::raw('price'));
        }

        if (!empty($request->end_date)) {
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d') . ' 23:59:59';
            $receipts->where('created_at', '<=', $endDate);
            // $colected = Receipt::where('created_at', '<=', $endDate)->sum(DB::raw('price'));
        }

        //total

        //colected
        if (!empty($request->search)) {
            $receipts->where('name', 'LIKE', "%{$request->search}%");
        }

        $receipts = $receipts->latest()->paginate(10);
        // $receipts = $receipts->getCollection()->sum('price');

        if (request()->wantsJson()) {
            return response(Receipt::collection($receipts));
        }

        return view('receipts.index', compact('receipts', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        // dd($users);
        return view('receipts.create',compact('users'));
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
            $image_path = $request->file('image')->store('receipts', 'public');
        }

        $receipts = Receipt::create([
            'user' => $request->user,
            'image' => $image_path,
            'price' => $request->price,
            'status' => $request->status
        ]);

        if (!$receipts) {
            return redirect()->back()->with('error', 'Sorry, there a problem while creating product.');
        }
        return redirect()->route('receipts.index')->with('success', 'Success, you product have been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
        return view('receipts.edit')->with('receipt', $receipt);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->price = $request->price;
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
        return redirect()->route('receipts.index')->with('success', 'Success, your product have been updated.');
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