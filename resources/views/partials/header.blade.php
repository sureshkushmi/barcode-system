<!-- Navbar -->
<nav class="app-header navbar navbar-expand bg-body">
  <!--begin::Container-->
  <div class="container-fluid">
    <!-- Start navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
            @php
                $active = request()->routeIs('superadmin.dashboard') ? 'active' : '';
            @endphp

            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ $active }}">Dashboard</a>
            @else
                <a href="{{ route('users.dashboard') }}" class="nav-link {{ $active }}">Dashboard</a>
            @endif
      </li>

      <li class="nav-item d-none d-md-block">
          <a href="{{ route('profile.edit') }}" class="nav-link">Profile</a>
      </li>

    </ul>
    <!-- End navbar links -->

    <ul class="navbar-nav ms-auto">
      <!-- Navbar Search -->
      <!--<li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>-->

          <!-- Notifications Dropdown Menu 
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge text-bg-warning">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i> 4 new messages
            <span class="float-end text-secondary fs-7">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-people-fill me-2"></i> 8 friend requests
            <span class="float-end text-secondary fs-7">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
            <span class="float-end text-secondary fs-7">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">
            See All Notifications
          </a>
        </div>
      </li>
      -->
      <li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <!-- User icon -->
        <i class="bi bi-person-circle me-1"></i> <!-- user icon -->

        <!-- Username + dropdown arrow together -->
        <span class="d-none d-md-inline">
            {{ Auth::user()->name }}
            <i class="bi bi-caret-down-fill ms-1"></i> <!-- dropdown arrow -->
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <!-- Menu Footer -->
        <li class="user-footer d-flex justify-content-between px-3 py-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">
                <i class="fas fa-user-cog me-1"></i> Profile
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-default btn-flat">
                    <i class="fas fa-sign-out-alt me-1"></i> Sign out
                </button>
            </form>
        </li>
    </ul>
</li>

    </ul>
  </div>
  <!--end::Container-->
</nav>
<!-- /.navbar -->