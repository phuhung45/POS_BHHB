@extends('layouts.admin')

@section('title', 'Cập nhật nhân viên')
@section('content-header', 'Cập nhật nhân viên')

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('cashiers.update', $cashier) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="first_name">Họ</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                           id="first_name"
                           placeholder="Nhập vào họ của nhân viên" value="{{ old('first_name', $cashier->first_name) }}">
                    @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Tên</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                           id="last_name"
                           placeholder="Nhập vào tên của nhân viên" value="{{ old('last_name', $cashier->last_name) }}">
                    @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
                           placeholder="Nhập vào email của nhân viên" value="{{ old('email', $cashier->email) }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone"
                           placeholder="Nhập vào số điện thoại của nhân viên" value="{{ old('phone', $cashier->phone) }}">
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Mật khẩu</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password"
                           placeholder="Nhập vào mật khẩu của nhân viên" value="{{ old('password', $cashier->password) }}">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                <label for="is_admin">Vai trò</label>
                <select name="is_admin" class="form-control @error('is_admin') is-invalid @enderror" id="is_admin">
                    <option value="1" {{ old('is_admin') === 1 ? 'selected' : ''}}>Admin</option>
                    <option value="2" {{ old('is_admin') === 2 ? 'selected' : ''}}>Nhân viên</option>
                </select>
                @error('is_admin')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

                {{-- <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                           id="address"
                           placeholder="Address" value="{{ old('address', $customer->address) }}">
                    @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div> --}}

                {{-- <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="avatar" id="avatar">
                        <label class="custom-file-label" for="avatar">Choose file</label>
                    </div>
                    @error('avatar')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div> --}}


                <button class="btn btn-primary" type="submit">Cập nhật</button>
                <a href="{{ route('cashiers.index') }}"><button class="btn btn-danger btn-close" type="button">Hủy</button></a>
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
