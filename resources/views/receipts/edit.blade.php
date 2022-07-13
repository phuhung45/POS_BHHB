@extends('layouts.admin')

@section('title', 'Chi tiết hóa đơn')
@section('content-header', 'Chi tiết hóa đơn')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('receipts.update', 'id') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

                <tr>
                    <td><img class="receipt-img" style="" src="{{ Storage::url($receipt->image) }}" alt="" width="400px" height="700px"></td>
                </tr>


            <!-- <button class="btn btn-primary" type="submit">Cập nhật</button> -->
            <a href="{{ route('receipts.index') }}"><button class="btn btn-danger btn-close" type="button">Đóng</button></a>
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
