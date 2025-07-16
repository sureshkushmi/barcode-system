<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Barcode Dashboard')</title>

    {{-- AdminLTE CSS --}}
    <link href="{{ asset('adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
   
    {{-- Fonts (optional) --}}
    <!-- Font Awesome -->
   

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
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('icons_main/font/bootstrap-icons.min.css') }}">
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
  window.BASE_URL = "{{ url('/') }}";
</script>
  <script>
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

    document.addEventListener("DOMContentLoaded", function () {
//User Scan Bar Chart
    const chartCanvas = document.getElementById('top-customers-chart');
    const filterSelect = document.getElementById('scan-filter');
    const dateRangeInput = document.querySelector('input[name="datetimes"]');
    
    //  Top Customers Bar Chart
const topCustomersElem = document.getElementById("top-customers-chart");
if (topCustomersElem) {
  const ctx = topCustomersElem.getContext('2d');
  let topCustomersChart;

  function updateTopCustomersChart(data) {
    const labels = Object.keys(data.customers);     // Customer names
    const counts = Object.values(data.customers);   // Order counts

    if (topCustomersChart) {
      topCustomersChart.data.labels = labels;
      topCustomersChart.data.datasets[0].data = counts;
      topCustomersChart.update();
    } else {
      topCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Orders',
            data: counts,
            backgroundColor: 'rgba(0,166,90,0.9)',
            borderColor: 'rgba(0,166,90,1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: { display: true, text: 'Orders' }
            },
            x: {
              title: { display: true, text: 'Customers' }
            }
          },
          plugins: { legend: { display: false } }
        }
      });
    }
  }

  function fetchTopCustomersData() {
    fetch(`${window.BASE_URL}/api/top-customers`)
      .then(response => {
        if (!response.ok) throw new Error('Failed to load data');
        return response.json();
      })
      .then(data => updateTopCustomersChart(data))
      .catch(error => console.error('Error fetching top customers:', error));
  }

  fetchTopCustomersData();
  setInterval(fetchTopCustomersData, 60000); // Refresh every 60 seconds
}



  /*if (chartCanvas && filterSelect && dateRangeInput) {
    const ctx = chartCanvas.getContext('2d');
    let userScanChart = new Chart(ctx, {
      type: 'bar',
      data: { labels: [], datasets: [{ label: 'Total Scanned', data: [], backgroundColor: 'rgba(54, 162, 235, 0.6)' }] },
      options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    function fetchScanStats(params = { filter: filterSelect.value || 'week' }) {
      fetch(`${window.BASE_URL}/api/user-scan-stats?${new URLSearchParams(params)}`)
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
  }   */

  //  Scan Status Doughnut Chart
  const statusChartElem = document.getElementById("status-chart");
  if (statusChartElem) {
    const ctx = statusChartElem.getContext('2d');
    let statusChart;

    function updateStatusChart(data) {
        const labels = ['Completed', 'Scanned','Failed'];
        const counts = [data.completed || 0, data.scanned || 0,  data.scanned || 0 ];

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
      fetch(`${window.BASE_URL}/api/scan-status-summary`)
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

  // Scan Trend Line Chart
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
      fetch(`${window.BASE_URL}/api/scan-over-time`)
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


  
});
</script>

<script>
  // Creating dynamic data with date range users dashboard
  const dailyScanChart = document.getElementById("daily-scans-chart");
  const scanStatusChart = document.getElementById("scan-status-chart");
  const scanningTrendChart = document.getElementById("scanning-trend-chart");

  let barChart, pieChart, lineChart;

  async function fetchDashboardStats() {
    try {
      const response = await fetch(`${window.BASE_URL}/dashboard-stats-users`, {
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('token') // adjust if you use sessions
        }
      });

      const data = await response.json();

      // === 1. Bar Chart: My Daily Scans ===
      if (barChart) barChart.destroy();
      barChart = new Chart(dailyScanChart, {
        type: 'bar',
        data: {
          labels: data.daily_labels,
          datasets: [{
            label: 'Items Scanned',
            data: data.daily_data,
            backgroundColor: '#007bff'
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } }
        }
      });

      // === 2. Pie Chart: Scan Status Overview ===
      if (pieChart) pieChart.destroy();
      pieChart = new Chart(scanStatusChart, {
        type: 'pie',
        data: {
          labels: Object.keys(data.status_data),
          datasets: [{
            data: Object.values(data.status_data),
            backgroundColor: ['#28a745', '#ffc107', '#dc3545']
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'bottom' } }
        }
      });

      // === 3. Line Chart: My Scanning Trend ===
      if (lineChart) lineChart.destroy();
      lineChart = new Chart(scanningTrendChart, {
        type: 'line',
        data: {
          labels: data.daily_labels,
          datasets: [{
            label: 'Items Scanned Over Time',
            data: data.daily_data,
            borderColor: '#17a2b8',
            backgroundColor: 'rgba(23,162,184,0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top'
            }
          }
        }
      });

    } catch (error) {
      console.error("Failed to fetch dashboard stats:", error);
    }
  }

  fetchDashboardStats();
</script>

<!-------- date picker ----------------------->
<script>
$(function () {
    const $orderDateRange = $('#order-date-range');

    if ($orderDateRange.length) {
        $orderDateRange.daterangepicker({
            timePicker: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss',
                cancelLabel: 'Clear'
            }
        });

        $orderDateRange.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD HH:mm:ss') +
                ' - ' +
                picker.endDate.format('YYYY-MM-DD HH:mm:ss')
            );
        });

        $orderDateRange.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        // Pre-fill if there's a value
        @if(request('datetimes'))
            $orderDateRange.val("{{ request('datetimes') }}");
        @endif
    }
});
</script>



    <!--end::Script-->
    {{-- Vite JS --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
  </body>
</html>
