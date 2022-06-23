<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
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
                Customer::all()
            );
        }
        $customers = Customer::latest()->paginate(10);
        return view('customers.index')->with('customers', $customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
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
                'first_name' => 'required|string|max:20',
                'last_name' => 'required|string|max:20',
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'avatar' => 'nullable|image',
            ], 
            [
                'first_name.required' => 'Không được để trống họ',
                'first_name.string' => 'Định dạng họ không đúng',
                'first_name.max' => 'Họ tối đa không vượt quá 20 ký tự',
                'last_name.required' => 'Không được để trống tên',
                'last_name.string' => 'Định dạng tên không đúng',
                'last_name.max' => 'Tên tối đa không vượt quá 20 ký tự',
                'phone.string' => 'Số điện thoại không đúng định dạng',
                'phone.max' => 'Số điện thoại không quá 20 số',
                'email.email' => 'Email không đúng định dạng',
                'address.string' => 'Địa chỉ không đúng định dạng',
                'avatar.image' => 'Ảnh tải lên không đúng định dạng'
            ]
          );

        $avatar_path = '';

        if ($request->hasFile('avatar')) {
            $avatar_path = $request->file('avatar')->store('customers', 'public');
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $avatar_path,
            'user_id' => $request->user()->id,
        ]);

        if ($customer) {
            $message = $request->validate;
            return redirect($message)->route('customers.index')->with('success', 'Thêm khách hàng mới thành công.');
        }else{
            return redirect()->back()->with('error', 'Xảy ra lỗi khi thêm khách hàng.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $request->validate(
            [
                'first_name' => 'required|string|max:20',
                'last_name' => 'required|string|max:20',
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'avatar' => 'nullable|image',
            ], 
            [
                'first_name.required' => 'Không được để trống họ',
                'first_name.string' => 'Định dạng họ không đúng',
                'first_name.max' => 'Họ tối đa không vượt quá 20 ký tự',
                'last_name.required' => 'Không được để trống tên',
                'last_name.string' => 'Định dạng tên không đúng',
                'last_name.max' => 'Tên tối đa không vượt quá 20 ký tự',
                'phone.string' => 'Số điện thoại không đúng định dạng',
                'phone.max' => 'Số điện thoại không quá 20 số',
                'email.email' => 'Email không đúng định dạng',
                'address.string' => 'Địa chỉ không đúng định dạng',
                'avatar.image' => 'Ảnh tải lên không đúng định dạng'
            ]
          );

        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($customer->avatar) {
                Storage::delete($customer->avatar);
            }
            // Store avatar
            $avatar_path = $request->file('avatar')->store('customers', 'public');
            // Save to Database
            $customer->avatar = $avatar_path;
        }

        if ($customer->save()) {
            $message = $request->validate;
            return redirect($message)->route('customers.index')->with('success', 'Thêm khách hàng thành công.');
        }else{
            return redirect()->back()->with('error', 'Xảy ra lỗi khi thêm khách hàng.');
        }
    }

    public function destroy(Customer $customer)
    {
        if ($customer->avatar) {
            Storage::delete($customer->avatar);
        }

        $customer->delete();

       return response()->json([
           'success' => true
       ]);
    }
}
