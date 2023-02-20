<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
   with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link {{setActive('dashboard')}}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>
        {{-- menu-open --}}
        @if (auth()->user()->can('role-management-list') || auth()->user()->can('admin-management-list'))
        <li class="nav-item {{isActive('role.*') || isActive('admin.*') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link {{isActive('role.*') || isActive('admin.*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    Management
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('role-management-list')
                <li class="nav-item">
                    <a href="{{route('role.index')}}" class="nav-link {{setActive('role.*')}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Role Permissions</p>
                    </a>
                </li>
                @endcan()
                @can('admin-management-list')
                <li class="nav-item">
                    <a href="{{route('admin.index')}}" class="nav-link {{setActive('admin.*')}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Admin Management</p>
                    </a>
                </li>
                @endcan()
            </ul>
        </li>
        @endif
        @can('payment-type-list')
        <li class="nav-item">
            <a href="{{route('payment.index')}}" class="nav-link {{setActive('payment.*')}}">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                    Payment Type
                </p>
            </a>
        </li>
        @endcan
        @can('bank-payment-list')    
        <li class="nav-item">
            <a href="{{route('bank.index')}}" class="nav-link {{setActive('bank.*')}}">
                <i class="nav-icon fas fa-wallet"></i>
                <p>
                    Bank
                </p>
            </a>
        </li>
        @endcan
        @can('admin-bank-list')
        <li class="nav-item">
            <a href="{{route('admin-bank.index')}}" class="nav-link {{setActive('admin-bank.*')}}">
                <i class="nav-icon fas fa-wallet"></i>
                <p>
                    Admin Bank
                </p>
            </a>
        </li>
        @endcan
        @can('company-setting-list')
        <li class="nav-item">
            <a href="{{route('company-setting.list')}}" class="nav-link {{setActive('company-setting.*')}}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    Company Setting
                </p>
            </a>
        </li>
        @endcan
        <li class="nav-item">
            <a href="{{route('logout')}}" class="nav-link">
                <i class="nav-icon fas fa-power-off"></i>
                <p>
                    Logout
                </p>
            </a>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->