@extends('layouts.admin')

@section('title', 'Danh sách nhân viên')
@section('content-header', 'Danh sách nhân viên')
@section('content-actions')
    <a href="{{route('cashiers.create')}}" class="btn btn-primary">Thêm nhân viên</a>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Ngày tạo</th>
                    <th>Ngày cập nhật</th>
                    <th>Vai trò</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cashiers as $cashier)
                    <tr>
                        <td>{{$cashier->id}}</td>

                        <td>{{$cashier->first_name}}</td>
                        <td>{{$cashier->last_name}}</td>
                        <td>{{$cashier->email}}</td>
                        <td>{{$cashier->phone}}</td>
                        <td>{{$cashier->created_at}}</td>
                        <td>{{$cashier->updated_at}}</td>
                        <td>
                            <span class="right badge badge-{{ $cashier->is_admin == 1 ? 'success' : 'danger' }}">{{$cashier->is_admin == 1 ? 'Admin' : 'Nhân viên'}}</span>
                        </td>
                        <td>
                            <a href="{{ route('cashiers.edit', $cashier) }}" class="btn btn-primary"><i
                                    class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-delete" data-url="{{route('cashiers.destroy', $cashier)}}"><i
                                    class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $cashiers->render() }}
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
                    text: "Bạn có chắc chắn muốn xóa nhân viên này?",
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
