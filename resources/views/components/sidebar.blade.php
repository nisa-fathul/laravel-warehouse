<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- INVENTORY MANAGEMENT -->
        <li class="nav-heading">INVENTORY MANAGEMENT</li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'dashboard' ? '' : 'collapsed' }}" href="?page=dashboard">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'inventory' ? '' : 'collapsed' }}" href="?page=inventory">
                <i class="bi bi-box-seam"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'forecast' ? '' : 'collapsed' }}" href="?page=forecast">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Forecast</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'orders' ? '' : 'collapsed' }}" href="?page=orders">
                <i class="bi bi-cart-check"></i>
                <span>Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'alerts' ? '' : 'collapsed' }}" href="?page=alerts">
                <i class="bi bi-bell"></i>
                <span>Alerts</span>

                @php
                $ac = count(array_filter($alerts ?? [], fn($a) => $a['level'] === 'critical'));
                @endphp

                @if($ac > 0)
                <span class="badge bg-danger ms-auto">
                    {{ $ac }}
                </span>
                @endif
            </a>
        </li>
        <!-- DELIVERY -->
        <li class="nav-heading">DELIVERY</li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'delivery_in' ? '' : 'collapsed' }}" href="?page=delivery_in">
                <i class="bi bi-box-arrow-in-down"></i>
                <span>Delivery In</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'delivery_out' ? '' : 'collapsed' }}" href="?page=delivery_out">
                <i class="bi bi-box-arrow-up"></i>
                <span>Delivery Out</span>
            </a>
        </li>

        <!-- REPORT -->
        <li class="nav-heading">REPORT</li>

        <li class="nav-item">
            <a class="nav-link {{ request('page') == 'report' ? '' : 'collapsed' }}" href="?page=report">
                <i class="bi bi-bar-chart"></i>
                <span>Sales Report</span>
            </a>
        </li>

        <!-- MENU LAMA -->
        <li class="nav-heading">MAIN MENU</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('role.index') ? '' : 'collapsed' }}"
                href="{{ route('role.index') }}">
                <i class="bi bi-grid"></i>
                <span>Roles</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('permission.index') ? '' : 'collapsed' }}"
                href="{{ route('permission.index') }}">
                <i class="bi bi-people"></i>
                <span>Permission</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.assign.index') ? '' : 'collapsed' }}"
                href="{{ route('user.assign.index') }}">
                <i class="bi bi-person-gear"></i>
                <span>User Assign</span>
            </a>
        </li>
    </ul>
</aside>
