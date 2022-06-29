@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')
@section('content-header', 'Danh sách sản phẩm')
@section('content-actions')
<a href="{{route('reports.create')}}" class="btn btn-primary">Thêm sản phẩm mới</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card product-list">
    <div class="card-body">
    <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="{{route('reports.index')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td>{{$report->id}}</td>
                    <td>{{$report->name}}</td>
                    <td><img class="report-img" style="" src="{{ Storage::url($report->image) }}" alt="" width="70px" height="70px"></td>
                    <td>{{number_format($report->price)}}</td>
                    <td>{{number_format($report->quantity)}}</td>
                    <td>{{number_format($report->price * $report->quantity)}}</td>
                    <td>
                        <span
                            class="right badge badge-{{ $report->status ? 'success' : 'danger' }}">{{$report->status ? 'Active' : 'Inactive'}}</span>
                    </td>
                    <td>{{$report->created_at}}</td>
                    <td>
                        <a href="{{ route('reports.edit', $report) }}" class="btn btn-primary"><i
                                class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-delete" data-url="{{route('reports.destroy', $report)}}"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th>Tiền nhập hàng</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
            <thead>
            <tr>
                    <th>Tiền bán hàng</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($colected) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
            <tr>
                    <th>Tiền lãi</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($colected - $total) }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
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
