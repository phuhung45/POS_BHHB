@extends('layouts.admin')

@section('title', 'Báo cáo thu-chi')
@section('content-header', 'Báo cáo thu-chi')
@section('content-actions')
{{-- <a href="{{route('products.create')}}" class="btn btn-primary">Thêm sản phẩm mới</a> --}}
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card product-list">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Mã vạch</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td>{{$report->id}}</td>
                    <td>{{$report->name}}</td>
                    <td><img class="product-img" src="{{ Storage::url($report->image) }}" alt=""></td>
                    <td>{{$report->barcode}}</td>
                    <td>{{$report->price}}</td>
                    <td>{{$report->quantity}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{ number_format($report->price * $report->quantity) }}</td>
                </tr>

                <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    {{-- <th>{{ config('settings.currency_symbol') }} {{ $report->sum($report->price * $report->quantity) }}</th> --}}
                </tr>
                </tfoot>
                @endforeach
            </tbody>
        </table>
        {{ $reports->render() }}
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-delete', function () {
            $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn chắc chắn muốn xóa sản phẩm này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có, tôi chắc chắn!',
                cancelButtonText: 'Không',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {_method: 'DELETE', _token: '{{csrf_token()}}'}, function (res) {
                        $this.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        })
                    })
                }
            })
        })
    })
</script>
@endsection
