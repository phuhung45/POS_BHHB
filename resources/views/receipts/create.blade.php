@extends('layouts.admin')

@section('title', 'Thêm sản hóa đơn')
@section('content-header', 'Thêm sản hóa đơn')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('receipts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="name">Người thanh toán</label>
                <select name="cashier" class="form-control @error('cashier') is-invalid @enderror" id="cashier">
                    @foreach ($users as $user)
                    <option value="name">{{$user->email}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="image">Hình ảnh</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image" id="image">
                    <label class="custom-file-label" for="image">Tải lên hình ảnh hóa đơn</label>
                </div>
                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Giá</label>
                <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" id="price"
                    placeholder="Nhập tên sản phẩm" value="{{ old('price') }}">
                @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                    <option value="1" {{ old('status') === 1 ? 'selected' : ''}}>Active</option>
                    <option value="0" {{ old('status') === 0 ? 'selected' : ''}}>Inactive</option>
                </select>
                @error('status')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Tải lên hóa đơn</button>
            <a href="{{ route('receipts.index') }}"><button class="btn btn-danger btn-close" type="button">Hủy</button></a>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@endsection
