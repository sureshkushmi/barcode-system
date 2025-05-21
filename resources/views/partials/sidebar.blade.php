<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          @php
          $active = request()->routeIs('dashboard') ? 'active' : '';
        @endphp

        <a href="{{ route('dashboard') }}" class="brand-link {{ $active }}">
          <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
          <span class="brand-text fw-light">Barcode</span>
        </a>

          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
            @if(Auth::user()->role == 'admin')
              <li class="nav-item menu-open">
               <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('superadmin.settings.edit') }}" 
                          class="nav-link {{ request()->routeIs('superadmin.settings.edit') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Configure ShippingEasy</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('superadmin.users') }}" 
                          class="nav-link {{ request()->routeIs('superadmin.users') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('user.reports') }}" 
                          class="nav-link {{ request()->routeIs('user.reports') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                </ul>

              </li>
              @endif
              @if(Auth::user()->role == 'users')
              <li class="nav-item">
                <a href="{{route('user.reports')}}" class="nav-link {{ request()->routeIs('user.reports') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>My Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('scan.label')}}" class="nav-link {{ request()->routeIs('scan.label') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Scan Shipping Label</p>
                </a>
              </li>
              @endif 
                </ul>
              </li>
              
              
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>