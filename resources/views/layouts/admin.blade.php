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
    document.addEventListener("DOMContentLoaded", function () {
// 📊 User Scan Bar Chart
const chartCanvas = document.getElementById('user-scan-chart');
  const filterSelect = document.getElementById('scan-filter');
  const dateRangeInput = document.querySelector('input[name="datetimes"]');

  if (chartCanvas && filterSelect && dateRangeInput) {
    const ctx = chartCanvas.getContext('2d');
    let userScanChart = new Chart(ctx, {
      type: 'bar',
      data: { labels: [], datasets: [{ label: 'Total Scanned', data: [], backgroundColor: 'rgba(54, 162, 235, 0.6)' }] },
      options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    function fetchScanStats(params = { filter: filterSelect.value || 'week' }) {
      fetch(`/api/user-scan-stats?${new URLSearchParams(params)}`)
        .then(res => res.json())
        .then(data => {
          const labels = data.map(item => item.name);
          const scanData = data.map(item => parseInt(item.total_scanned));
          userScanChart.data.labels = labels;
          userScanChart.data.datasets[0].data = scanData;
          userScanChart.update();
        })
        .catch(error => console.error('API error:', error));
    }

    $(dateRangeInput).daterangepicker({
      timePicker: true,
      startDate: moment().startOf('hour'),
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale: { format: 'M/DD hh:mm A' }
    });

    $(dateRangeInput).on('apply.daterangepicker', function(ev, picker) {
      filterSelect.value = '';
      const startDate = picker.startDate.format('YYYY-MM-DD HH:mm:ss');
      const endDate = picker.endDate.format('YYYY-MM-DD HH:mm:ss');
      this.value = picker.startDate.format('M/DD hh:mm A') + ' - ' + picker.endDate.format('M/DD hh:mm A');
      fetchScanStats({ start_date: startDate, end_date: endDate });
    });

    filterSelect.addEventListener('change', () => {
      dateRangeInput.value = '';
      fetchScanStats({ filter: filterSelect.value });
    });

    fetchScanStats();
  }

  // 📊 Scan Status Doughnut Chart
  const statusChartElem = document.getElementById("status-chart");
  if (statusChartElem) {
    const ctx = statusChartElem.getContext('2d');
    let statusChart;

    function updateStatusChart(data) {
      const labels = ['Delivered', 'Pending', 'Shipped', 'Failed'];
      const counts = [data.delivered || 0, data.pending || 0, data.shipped || 0, data.failed || 0];

      if (statusChart) {
        statusChart.data.datasets[0].data = counts;
        statusChart.update();
      } else {
        statusChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: labels,
            datasets: [{
              label: 'Scan Status',
              data: counts,
              backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
          }
        });
      }
    }

    function fetchStatusData() {
      fetch('/api/scan-status-summary')
        .then(response => {
          if (!response.ok) throw new Error('Server Error');
          return response.json();
        })
        .then(data => updateStatusChart(data))
        .catch(err => console.error('Error fetching status data:', err));
    }

    fetchStatusData();
    setInterval(fetchStatusData, 60000);
  }

  // 📈 Scan Trend Line Chart
  const scanTrendChartElem = document.getElementById("scan-trend-chart");
  if (scanTrendChartElem) {
    const ctx = scanTrendChartElem.getContext("2d");
    let scanTrendChart;

    function updateScanTrendChart(labels, data) {
      if (scanTrendChart) {
        scanTrendChart.data.labels = labels;
        scanTrendChart.data.datasets[0].data = data;
        scanTrendChart.update();
      } else {
        scanTrendChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: 'Scans',
              data: data,
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
    }

    function fetchScanTrendData() {
      fetch('/api/scan-trend-week')
        .then(res => {
          if (!res.ok) throw new Error("Network response error");
          return res.json();
        })
        .then(data => updateScanTrendChart(data.labels, data.data))
        .catch(err => console.error("Scan trend fetch error:", err));
    }

    fetchScanTrendData();
    setInterval(fetchScanTrendData, 60000);
  }


  // Creating dynamic data with date range users dashboard
  
        // Chart 4: Daily Scans (Static)
        const dailyScanChart = document.getElementById("daily-scans-chart");
let chartInstance;

function updateScanTrendChart(labels, data) {
  if (chartInstance) {
    chartInstance.data.labels = labels;
    chartInstance.data.datasets[0].data = data;
    chartInstance.update();
  } else {
    chartInstance = new Chart(dailyScanChart, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Items Scanned',
          data: data,
          backgroundColor: '#007bff'
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } }
      }
    });
  }
}

function fetchScanTrendData() {
  fetch('/api/scan-trend-week', {
    headers: {
      'Accept': 'application/json',
      // Add auth headers here if necessary
    }
  })
    .then(res => {
      if (!res.ok) throw new Error("Network response error");
      return res.json();
    })
    .then(data => updateScanTrendChart(data.labels, data.data))
    .catch(err => console.error("Scan trend fetch error:", err));
}

if (dailyScanChart) {
  fetchScanTrendData();
}

        
        

        // Chart 5: User Status (Static)
        const userStatusChart = document.getElementById("user-status-chart");
let statusChartInstance;

function updateStatusChart(data) {
  if (statusChartInstance) {
    statusChartInstance.data.datasets[0].data = [
      data.completed,
      data.pending,
      data.failed
    ];
    statusChartInstance.update();
  } else {
    statusChartInstance = new Chart(userStatusChart, {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'Pending', 'Failed'],
        datasets: [{
          data: [
            data.completed,
            data.pending,
            data.failed
          ],
          backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  }
}

function fetchScanStatusOverview() {
  fetch('/api/scan-status-overview', {
    headers: {
      'Accept': 'application/json',
      // add auth headers if needed
    }
  })
    .then(res => {
      if (!res.ok) throw new Error("Network response error");
      return res.json();
    })
    .then(data => updateStatusChart(data))
    .catch(err => console.error("Scan status fetch error:", err));
}

if (userStatusChart) {
  fetchScanStatusOverview();
}


        // Chart 6: User Trend (Static)
        const userTrendChart = document.getElementById("user-trend-chart");
let trendChartInstance;

function updateUserTrendChart(labels, data) {
  if (trendChartInstance) {
    trendChartInstance.data.labels = labels;
    trendChartInstance.data.datasets[0].data = data;
    trendChartInstance.update();
  } else {
    trendChartInstance = new Chart(userTrendChart, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Scanned Items',
          data: data,
          borderColor: '#17a2b8',
          fill: false,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } }
      }
    });
  }
}

function fetchUserTrendData() {
  fetch('/api/user-scan-trend', {
    headers: {
      'Accept': 'application/json',
      // Add auth headers if needed
    }
  })
  .then(res => {
    if (!res.ok) throw new Error("Network response error");
    return res.json();
  })
  .then(data => updateUserTrendChart(data.labels, data.data))
  .catch(err => console.error("User trend fetch error:", err));
}

if (userTrendChart) {
  fetchUserTrendData();
}

      });
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
