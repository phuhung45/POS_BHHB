<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('images/logo.jpg') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->getFullname() }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{route('home')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Bảng điều khiển</p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ activeSegment('reports') }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Báo cáo thu - chi</p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Danh sách sản phẩm</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>Giỏ hàng</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>Danh sách Hóa Đơn</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customers.index') }}" class="nav-link {{ activeSegment('customers') }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Danh sách khách hàng</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('cashiers.index') }}" class="nav-link {{ activeSegment('cashiers') }}">
                        <i class="nav-icon fas fa-desktop"></i>
                        <p>Danh sách nhân viên</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Cài đặt</p>
                    </a>
                </li>
                <li class="nav-item">
                    {{-- <a href="/logout" class="nav-link" onclick="document.getElementById('logout-form').submit()"> --}}
                    <a href="{{route('logout.get')}}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Thoát</p>
                        {{-- <form action="{{route('logout')}}" method="POST" id="logout-form">
                            @csrf
                        </form> --}}
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
