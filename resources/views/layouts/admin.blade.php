<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AdminLTE Dashboard')</title>

    {{-- AdminLTE CSS --}}
    <link href="{{ asset('adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
   
    {{-- Fonts (optional) --}}
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">


    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->


    <!--end::Required Plugin(AdminLTE)-->


    {{-- Vite CSS --}}
    @vite(['resources/css/app.css'])

    @stack('styles')
    </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      {{-- Header --}}
      @include('partials.header')

      {{-- Sidebar --}}
      @include('partials.sidebar')

      {{-- Main Content --}}
      <main class="app-main">
        @yield('content')
      </div>

      {{-- Footer --}}
      @include('partials.footer')
    </div>

    {{-- AdminLTE JS --}}
   
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!-- Chart 1: Quantity Scanned by User (Bar Chart) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.onload = function () {
  // Chart 1: Overall User Scans
  const userScanChart = document.getElementById("user-scan-chart");
  if (userScanChart) {
    new Chart(userScanChart, {
      type: 'bar',
      data: {
        labels: ['User A', 'User B', 'User C', 'User D'],
        datasets: [{
          label: 'Scans',
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          data: [20, 35, 15, 40]
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } }
      }
    });
  }

  // Chart 2: Scans by Status
  const statusChart = document.getElementById("status-chart");
  if (statusChart) {
    new Chart(statusChart, {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'Pending', 'Failed'],
        datasets: [{
          label: 'Status',
          data: [60, 25, 15],
          backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  }

  // Chart 3: Scans Over Time
  const scanTrendChart = document.getElementById("scan-trend-chart");
  if (scanTrendChart) {
    new Chart(scanTrendChart, {
      type: 'line',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'Scans',
          data: [5, 12, 8, 15, 20, 10, 7],
          borderColor: '#17a2b8',
          backgroundColor: 'rgba(23, 162, 184, 0.2)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } }
      }
    });
  }

  // USER-LEVEL DASHBOARD CHARTS
  const dailyScanChart = document.getElementById("daily-scans-chart");
  if (dailyScanChart) {
    new Chart(dailyScanChart, {
      type: 'bar',
      data: {
        labels: ['May 16', 'May 17', 'May 18', 'May 19', 'May 20', 'May 21'],
        datasets: [{
          label: 'Items Scanned',
          data: [10, 14, 8, 16, 12, 20],
          backgroundColor: '#007bff'
        }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
  }

  const userStatusChart = document.getElementById("user-status-chart");
  if (userStatusChart) {
    new Chart(userStatusChart, {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'Pending', 'Failed'],
        datasets: [{
          data: [100, 15, 5],
          backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
      },
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
  }

  const userTrendChart = document.getElementById("user-trend-chart");
  if (userTrendChart) {
    new Chart(userTrendChart, {
      type: 'line',
      data: {
        labels: ['Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue'],
        datasets: [{
          label: 'Scanned Items',
          data: [5, 9, 6, 12, 15, 14, 11],
          borderColor: '#17a2b8',
          fill: false,
          tension: 0.3
        }]
      },
      options: { responsive: true, plugins: { legend: { display: true } } }
    });
  }
}
</script>
<!-------- date picker ----------------------->
<script>
$(function() {
  $('input[name="datetimes"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'M/DD hh:mm A'
    }
  });
});
</script>


    <!--end::Script-->
    {{-- Vite JS --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
  </body>
</html>
