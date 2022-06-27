<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'barcode' => 'required|string|max:50|unique:products',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'required|integer',
            'status' => 'required|boolean',
        ];
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
        ];
    }
}
