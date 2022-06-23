<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\CashierStoreRequest;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response(
                User::all()
            );
        }
        $cashiers = User::latest()->paginate(10);
        return view('cashiers.index')->with('cashiers', $cashiers);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cashiers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|unique:users|min:9|max:11',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ], 
            [
                'first_name.required' => 'Không được để trống họ',
                'last_name.required' => 'Không được để trống tên',
                'phone.required' => 'Không được để trống số điện thoại',
                'email.required' => 'Không được để trống email',
                'phone.unique' => 'Số điện thoại đã tồn tại',
                'email.unique' => 'Email đã tồn tại',
                'phone.min' => 'Số điện thoại không đúng định dạng',
                'phone.max' => 'Số điện thoại không đúng định dạng',
                'email.email' => 'Email không đúng định dạng',
                'password.required' => 'Không được để trống mật khẩu'
            ]
          );

        $cashier = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request['password']),
            'is_admin' => $request->is_admin,
        ]);

        // if (!$cashier) {
        //     return redirect()->back()->with('error', 'Xảy ra lỗi khi thêm nhân viên.');
        // }
        // return redirect()->route('cashiers.index')->with('success', 'Thêm nhân viên mới thành công.');

        if ($cashier) {
            $message = $request->validate;
            return redirect($message)->route('cashiers.index')->with('success', 'Thêm nhân viên mới thành công.');
        }else{
            return redirect()->back()->with('error', 'Xảy ra lỗi khi thêm nhân viên.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $cashier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $cashier)
    {
        return view('cashiers.edit', compact('cashier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $cashier)
    {
        $cashier->first_name = $request->first_name;
        $cashier->last_name = $request->last_name;
        $cashier->email = $request->email;
        $cashier->phone = $request->phone;
        $cashier->password = Hash::make($request['password']);
        $cashier->is_admin = $request->is_admin;

        if (!$cashier->save()) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while updating customer.');
        }
        return redirect()->route('cashiers.index')->with('success', 'Cập nhật nhân viên thành công.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $cashier)
    {
        $cashier->delete();

       return response()->json([
           'success' => true
       ]);
    }
}
