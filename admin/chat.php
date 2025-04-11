<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  header('location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Chat Support</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 56px;
      left: 0;
      width: 220px;
      background-color: #f8f9fa;
      border-right: 1px solid #ddd;
      padding-top: 1rem;
    }
    .content {
    margin-left: 220px;
    margin-top: 70px; /* Thêm dòng này để tránh bị navbar che */
    padding: 1.5rem;
    }

    .chat-column {
      height: calc(100vh - 120px);
      overflow-y: auto;
      background: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 1rem;
    }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark fixed-top">
  <a class="navbar-brand px-3" href="#">Nhi & Thanh Company</a>
  <a class="btn btn-outline-light me-3" href="logout.php?logout=1">Sign out</a>
</nav>

<!-- Sidebar -->
<div class="sidebar">
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">Dashboard</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">Orders</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="products.php">Products</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="chat.php">Chăm sóc khách hàng</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="add_product.php">Add New Product</a>
    </li>
  </ul>
</div>

<!-- Nội dung chính -->
<div class="content">
  <h2 class="mb-4">Chăm sóc khách hàng - Hộp thoại</h2>

  <!-- Bộ lọc -->
  <div class="btn-group mb-3" role="group">
    <button type="button" class="btn btn-outline-primary active">Mới nhận</button>
    <button type="button" class="btn btn-outline-primary">Đang xử lý</button>
    <button type="button" class="btn btn-outline-primary">Đã xong</button>
  </div>

  <!-- Giao diện chat 3 cột -->
  <div class="row g-3">
    <!-- Danh sách hội thoại -->
    <div class="col-md-3">
      <div class="chat-column">
        <input class="form-control mb-3" type="text" placeholder="Tìm kiếm khách hàng...">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-start active">
            <div>
              <div><strong>Nguyễn Thanh</strong></div>
              <small>Tôi muốn hỏi về giày búp bê size 36...</small>
            </div>
            <span class="badge bg-danger rounded-pill">1</span>
          </li>
          <li class="list-group-item">
            <strong>Lê Hương</strong><br>
            <small>Cảm ơn bạn đã tư vấn rất nhiệt tình...</small>
          </li>
          <li class="list-group-item">
            <strong>Trần Huy</strong><br>
            <small>Tôi cần biết mẫu giày Sneaker trắng...</small>
          </li>
        </ul>
      </div>
    </div>

    <!-- Khung chat -->
    <div class="col-md-6 d-flex flex-column">
      <div class="chat-column flex-grow-1 mb-2">
        <div class="text-center text-muted mb-3"><small>Hôm nay, 10:30</small></div>

        <!-- Tin nhắn khách -->
        <div class="mb-3">
          <span class="badge bg-secondary">Khách</span>
          <div class="p-2 bg-light rounded w-75">Xin chào, tôi muốn hỏi về giày búp bê nữ size 36 còn hàng không ạ?</div>
        </div>

        <!-- Tin nhắn chatbot -->
        <div class="mb-3">
          <span class="badge bg-info text-dark">Chatbot</span>
          <div class="p-2 bg-white border rounded w-75">Chào bạn! Sản phẩm bạn hỏi hiện vẫn còn hàng. Bạn có muốn đặt luôn không ạ?</div>
        </div>

        <!-- Tin nhắn nhân viên -->
        <div class="mb-3 text-end">
          <span class="badge bg-primary">Nhân viên</span>
          <div class="p-2 bg-primary text-white rounded w-75 float-end">Dạ chào chị! Giày búp bê nữ size 36 hiện có 3 mẫu mới về. Em gửi link cho chị xem nha!</div>
        </div>

        <!-- Tin nhắn khách -->
        <div class="mb-3">
          <span class="badge bg-secondary">Khách</span>
          <div class="p-2 bg-light rounded w-75">Cảm ơn em nha! Gửi giúp chị nha.</div>
        </div>
      </div>

      <!-- Nhập phản hồi -->
      <form class="d-flex">
        <input type="text" class="form-control me-2" placeholder="Nhập tin nhắn...">
        <button class="btn btn-primary" type="submit">Gửi</button>
      </form>
    </div>

    <!-- Thông tin khách -->
    <div class="col-md-3">
      <div class="chat-column">
        <h5>Thông tin khách</h5>
        <p><strong>Nguyễn Thanh</strong></p>
        <p>📞 0912 345 678</p>
        <p>✉️ nguyenthanh@email.com</p>
        <span class="badge bg-warning text-dark">Khách VIP</span>

        <hr>

        <h6>Lịch sử đơn hàng</h6>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">👟 Giày Sneaker - Đã giao</li>
          <li class="list-group-item">👠 Giày cao gót - Đang xử lý</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
