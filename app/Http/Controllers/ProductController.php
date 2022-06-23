<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = new Product();
        if ($request->search) {
            $products = $products->where('name', 'LIKE', "%{$request->search}%");
        }
        $products = $products->latest()->paginate(10);
        if (request()->wantsJson()) {
            return Product::collection($products);
        }
        return view('products.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
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
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image',
                'barcode' => 'required|string|max:50|unique:products',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'quantity' => 'required|integer',
                'status' => 'required|boolean',
            ], 
            [
                'name.required' => 'Không được để trống tên sản phẩm',
                'name.string' => 'Tên sản phẩm không đúng định dạng',
                'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
                'description.nullable' => 'Mô tả sản phẩm không được để trống',
                'description.string' => 'Mô tả sản phẩm không đúng định dạng',
                'image.nullable' => 'Không được để trống ảnh sản phẩm',
                'image.image' => 'File tải lên phải là ảnh',
                'barcode.required' => 'Không được để trống mã vạch',
                'barcode.string' => 'Mã vạch không đúng định dạng',
                'barcode.max' => 'Mã vạch không được vượt quá 50 ký tự',
                'barcode.unique' => 'Mã vạch đã tồn tại',
                'price.required' => 'Không được để trống giá sản phẩm',
                'price.regex' => 'Giá sản phẩm không đúng định dạng',
                'quantity.required' => 'Không được để trống số lượng',
                'quantity.integer' => 'Số lượng sản phẩm phải là số nguyên'
            ]
          );

        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->status
        ]);

        if ($product) {
            $message = $request->validate;
            return redirect($message)->route('products.index')->with('success', 'Thêm sản phẩm mới thành công.');
        }else{
            return redirect()->back()->with('error', 'Xảy ra lỗi khi thêm nhân viên.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit')->with('product', $product);
    }

    public function generateBarcode(Request $request){
        $id = $request->get('id');
        $product = Product::find($id);

        return view('barcode')->with('product',$product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'quantity' => 'required|integer',
                'status' => 'required|boolean',
            ], 
            [
                'name.required' => 'Không được để trống tên sản phẩm',
                'name.string' => 'Tên sản phẩm không đúng định dạng',
                'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
                'description.nullable' => 'Mô tả sản phẩm không được để trống',
                'description.string' => 'Mô tả sản phẩm không đúng định dạng',
                'image.nullable' => 'Không được để trống ảnh sản phẩm',
                'image.image' => 'File tải lên phải là ảnh',
                'barcode.string' => 'Mã vạch không đúng định dạng',
                'barcode.max' => 'Mã vạch không được vượt quá 50 ký tự',
                'barcode.unique' => 'Mã vạch đã tồn tại',
                'price.required' => 'Không được để trống giá sản phẩm',
                'price.regex' => 'Giá sản phẩm không đúng định dạng',
                'quantity.required' => 'Không được để trống số lượng',
                'quantity.integer' => 'Số lượng sản phẩm phải là số nguyên'
            ]
          );

        $product->name = $request->name;
        $product->description = $request->description;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->status = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::delete($product->image);
            }
            // Store image
            $image_path = $request->file('image')->store('products', 'public');
            // Save to Database
            $product->image = $image_path;
        }

        if ($product->save()) {
            $message = $request->validate;
            return redirect($message)->route('products.index')->with('success', 'Thêm sản phẩm mới thành công.');
        }else{
            return redirect()->back()->with('error', 'Xảy ra lỗi khi thêm sản phẩm.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }
        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
