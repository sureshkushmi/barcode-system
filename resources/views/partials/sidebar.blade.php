<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          @php
          $active = request()->routeIs('dashboard') ? 'active' : '';
        @endphp
        @if(Auth::check() && Auth::user()->role === 'admin')
        <a href="{{ route('superadmin.dashboard') }}" class="brand-link {{ $active }}">
                <img src="{{ asset('img/scanlogo.png') }}" alt="scann ship Logo" class="brand-image opacity-75 shadow" />
         
            </a>
            @else
            <a href="{{ route('users.dashboard') }}" class="brand-link {{ $active }}">
            <img src="{{ asset('img/scanlogo.png') }}" alt="scann ship Logo" class="brand-image opacity-75 shadow" />
                   </a>
            @endif


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
                            <i class="nav-icon bi bi-gear"></i>
                            <p>Configure ShippingEasy</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('superadmin.users') }}" 
                          class="nav-link {{ request()->routeIs('superadmin.users') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.messages.inbox') }}" 
                           class="nav-link {{ request()->routeIs('superadmin.messages.inbox') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-envelope-open"></i>
                            <p>Inbox</p>
                        </a>
                    </li>


                     <li class="nav-item">
                        <a href="{{ route('superadmin.messages.index') }}" 
                          class="nav-link {{ request()->routeIs('superadmin.messages.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-chat-dots"></i>
                            <p>Sent Messages</p>
                        </a>
                    </li> 

                   <li class="nav-item">
                        <a href="{{ route('superadmin.reports.alluser-scanning') }}" 
                          class="nav-link {{ request()->routeIs('superadmin.reports.alluser-scanning') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-data"></i>
                            <p>User Scanning Report</p>
                        </a>
                    </li> 
                    <li class="nav-item">
                      <a href="{{route('superadmin.syncOrdersFromShippingEasy')}}" class="nav-link {{ request()->routeIs('superadmin.syncOrdersFromShippingEasy') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-arrow-repeat"></i>
                        <p>Sync Orders</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('superadmin.orders')}}" class="nav-link {{ request()->routeIs('superadmin.orders') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>View Orders</p>
                      </a>
                    </li>
                </ul>

              </li>
              @endif
              @if(Auth::user()->role == 'users')
              <li class="nav-item">
                <a href="{{route('reports.user-scanning')}}" class="nav-link {{ request()->routeIs('user.reports') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-graph-up"></i>
                  <p>My Reports</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                        <a href="{{ route('reports.users-scanning') }}" 
                          class="nav-link {{ request()->routeIs('reports.users-scanning') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-data"></i>
                            <p>User Scanning Report</p>
                        </a>
                    </li> --}}
              <li class="nav-item">
                <a href="{{route('scan.label.form')}}" class="nav-link {{ request()->routeIs('scan.label.form') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-upc-scan"></i>
                  <p>Scan Shipping Label</p>
                </a>
              </li>
              
              <li class="nav-item">
                      <a href="{{route('users.syncOrdersFromShippingEasy')}}" class="nav-link {{ request()->routeIs('superadmin.syncOrdersFromShippingEasy') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-arrow-repeat"></i>
                        <p>Sync Orders</p>
                      </a>
                    </li>
              <li class="nav-item">
                <a href="{{route('user.scan.orders')}}" class="nav-link {{ request()->routeIs('user.scan.orders') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-card-list"></i>
                  <p>scan orders</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{route('user.scan.pending-orders')}}" class="nav-link {{ request()->routeIs('user.scan.pending-orders') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-card-list"></i>
                  <p>pending scan orders</p>
                </a>
              </li>
               <li class="nav-item">
                        <a href="{{ route('users.messages.index') }}" 
                          class="nav-link {{ request()->routeIs('users.messages.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-inbox"></i>
                            <p>Messages</p>
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