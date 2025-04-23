<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  header('location: login.php');
  exit();
}

// Hiển thị dashboard nếu người dùng đã đăng nhập thành công
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Báo cáo doanh thu - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .dashboard-title {
      font-size: 2rem;
      font-weight: 600;
    }
    .metric-title {
      font-weight: 500;
      color: #555;
    }
    .metric-value {
      font-size: 1.8rem;
      font-weight: bold;
    }
    .revenue-chart, .orders-chart {
      min-height: 250px;
      background-color: #f8f9fa;
      border-radius: 15px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Nhi & Thanh Company</a>
      <a class="btn btn-outline-light" href="logout.php?logout=1">Sign out</a>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="report.html">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="chat.php">Chăm sóc khách hàng</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="add_product.php">Add New Product</a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="dashboard-title">Báo cáo doanh thu</h1>
        </div>

        <div class="row g-4 mb-4">
          <div class="col-md-4">
            <div class="card p-4">
              <div class="metric-title">Tổng doanh thu hôm nay</div>
              <div class="metric-value text-success">2.500.000₫</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-4">
              <div class="metric-title">Số đơn hàng hôm nay</div>
              <div class="metric-value">25</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-4">
              <div class="metric-title">Khách hàng mới</div>
              <div class="metric-value">7</div>
            </div>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-md-6">
            <div class="card p-4">
              <h5 class="mb-3">Biểu đồ doanh thu theo tuần</h5>
              <div class="revenue-chart d-flex align-items-center justify-content-center">
                <canvas id="revenueChart" width="400" height="300"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card p-4">
              <h5 class="mb-3">Biểu đồ đơn hàng theo tuần</h5>
              <div class="orders-chart d-flex align-items-center justify-content-center">
               <canvas id="ordersChart" width="400" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    fetch('get_chart_data.php')
        .then(response => response.json())
        .then(data => {
            const weeks = data.map(item => item.week);
            const orders = data.map(item => item.orders);
            const revenue = data.map(item => item.revenue);
    
            const ctx1 = document.getElementById('ordersChart').getContext('2d');
            const ctx2 = document.getElementById('revenueChart').getContext('2d');
    
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: weeks,
                    datasets: [{
                        label: 'Số đơn hàng',
                        data: orders,
                        backgroundColor: '#4CAF50'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Số đơn hàng theo tuần'
                        }
                    }
                }
            });
    
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: weeks,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: revenue,
                        borderColor: '#2196F3',
                        fill: false,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Doanh thu theo tuần'
                        }
                    }
                }
            });
        });
    </script>
    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>