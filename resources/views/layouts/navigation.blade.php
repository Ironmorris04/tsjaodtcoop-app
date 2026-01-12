<!-- resources/views/layouts/navigation.blade.php -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-bus"></i>
        <span>{{ config('app.name', 'Transport Coop') }}</span>
    </div>
    
    <ul class="sidebar-menu">
        @auth
            @if(auth()->user()->role === 'admin')
                <!-- Admin Menu -->
                <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.operators*') ? 'active' : '' }}">
                    <a href="{{ route('admin.operators.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Operators</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.vehicles*') ? 'active' : '' }}">
                    <a href="{{ route('admin.vehicles.index') }}">
                        <i class="fas fa-bus-alt"></i>
                        <span>Vehicles</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.routes*') ? 'active' : '' }}">
                    <a href="{{ route('admin.routes.index') }}">
                        <i class="fas fa-route"></i>
                        <span>Routes</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Bookings</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.index') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Payments</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings') }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                
            @elseif(auth()->user()->role === 'operator')
                <!-- Operator Menu -->
                <li class="menu-item {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('operator.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('operator.vehicles*') ? 'active' : '' }}">
                    <a href="{{ route('operator.vehicles.index') }}">
                        <i class="fas fa-bus-alt"></i>
                        <span>My Vehicles</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('operator.trips*') ? 'active' : '' }}">
                    <a href="{{ route('operator.trips.index') }}">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Trips</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('operator.bookings*') ? 'active' : '' }}">
                    <a href="{{ route('operator.bookings.index') }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Bookings</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('operator.earnings*') ? 'active' : '' }}">
                    <a href="{{ route('operator.earnings.index') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Earnings</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('operator.profile*') ? 'active' : '' }}">
                    <a href="{{ route('operator.profile') }}">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                
            @else
                <!-- Member/Passenger Menu -->
                <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('bookings*') ? 'active' : '' }}">
                    <a href="{{ route('bookings.index') }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span>My Bookings</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('routes*') ? 'active' : '' }}">
                    <a href="{{ route('routes.index') }}">
                        <i class="fas fa-route"></i>
                        <span>Routes</span>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
            @endif
            
            <!-- Logout (Common for all roles) -->
            <li class="menu-item">
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
    </ul>
</aside>