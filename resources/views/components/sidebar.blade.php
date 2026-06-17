<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- INVENTORY MANAGEMENT -->
        <li class="nav-heading">INVENTORY MANAGEMENT</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @hasanyrole(['Admin'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('inventory.*') ? '' : 'collapsed' }}"
                    href="{{ route('inventory.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Inventory</span>
                </a>
            </li>
        @endhasanyrole

        @hasanyrole(['Admin', 'Staf Gudang', 'Manajemen'])
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('forecast.*') ? '' : 'collapsed' }}"
                href="{{ route('forecast.index') }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Forecast</span>
            </a>
        </li>
        @endhasanyrole

        @hasanyrole(['Staf Gudang'])
            <li class="nav-heading">DELIVERY</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaction.index') && request()->route('type') == 'in' ? '' : 'collapsed' }}"
                    href="{{ route('transaction.index', ['type' => 'in']) }}">
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span>Delivery In</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaction.index') && request()->route('type') == 'out' ? '' : 'collapsed' }}"
                    href="{{ route('transaction.index', ['type' => 'out']) }}">
                    <i class="bi bi-box-arrow-up"></i>
                    <span>Delivery Out</span>
                </a>
            </li>
        @endhasanyrole

        @hasanyrole(['Manajemen', 'Staf Gudang'])
            <li class="nav-heading">REPORT</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('report.*') ? '' : 'collapsed' }}"
                    href="{{ route('report.index') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>Sales Report</span>
                </a>
            </li>
        @endhasanyrole

        @hasanyrole(['Admin'])
            <!-- MENU LAMA -->
            <li class="nav-heading">MAIN MENU</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('role.index') ? '' : 'collapsed' }}"
                    href="{{ route('role.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Roles</span>
                </a>
            </li>
            {{-- <li class="nav-item">
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
            </li> --}}
        @endhasanyrole
    </ul>
</aside>
