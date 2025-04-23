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
      margin-top: 70px;
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
    .conversation-item {
    display: flex;
    padding: 12px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.2s;
    }

    .conversation-item:hover {
        background-color: #f8f8f8;
    }

    .avatar {
        width: 40px;
        height: 40px;
        background-color: #6c63ff;
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .conversation-details {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-width: 0; /* Thêm dòng này để flexbox hiểu đúng giới hạn chiều rộng */
    }

    .name-time {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .user-name {
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }

    .time {
        font-size: 12px;
        color: #999;
    }

    .last-message {
    font-size: 13px;
    color: #555;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%; /* Đảm bảo không bị tràn ra ngoài */
    }

    .conversation-item {
      max-height: 72px;
      overflow: hidden;
    }

      .conversation-item.active {
    background-color: #e8f0ff;
    border-left: 4px solid #0d6efd;
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
      <a class="nav-link" href="">Dashboard</a>
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
        <ul class="list-group" id="conversation-list">
          <!-- Danh sách hội thoại sẽ được load tự động -->

        </ul>

      </div>
    </div>

    <!-- Khung chat -->
    <div class="col-md-6 d-flex flex-column">
      <div class="chat-column flex-grow-1 mb-2" id="chat-body">
        <div class="text-center text-muted mb-3"><small>Hôm nay, 10:30</small></div>
      </div>

      <!-- Nhập phản hồi -->
      <form class="d-flex" id="staff-reply-form">
        <input type="text" class="form-control me-2" id="staff-input" placeholder="Nhập tin nhắn...">
        <button class="btn btn-primary" type="submit">Gửi</button>
      </form>
    </div>

    <!-- Thông tin khách -->
<div class="col-md-3">
  <div class="chat-column">
    <h5 class="mb-3">👤 Thông tin khách hàng</h5>

    <div id="customer-info">
      <p><strong id="customer-name">Chưa chọn khách</strong></p>
      <p id="customer-phone">📞 Số điện thoại: --</p>
      <p id="customer-email">✉️ Email: --</p>
      <span id="customer-tag" class="badge bg-secondary">Khách thường</span>
    </div>

    <hr>

    <h6 class="mt-4">📦 Lịch sử đơn hàng</h6>
    <ul id="order-history" class="list-group list-group-flush">
      <li class="list-group-item text-muted">Chưa có dữ liệu</li>
    </ul>
  </div>
</div>


  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>

  // Tải danh sách hội thoại
  function loadConversations() {
    fetch('../api/load_conversations.php')
      .then(response => response.text())
      .then(html => {
        document.getElementById('conversation-list').innerHTML = html;
        attachConversationEvents();
      });
  }

  // Gắn sự kiện khi click vào hội thoại
  function attachConversationEvents() {
  const items = document.querySelectorAll('.conversation-item');
  items.forEach(item => {
    item.addEventListener('click', function () {
      const sessionId = this.dataset.sessionId;

      // Gán sessionId hiện tại để biết đang phản hồi hội thoại nào
      currentSessionId = sessionId;

      // Làm nổi bật hội thoại đang chọn
      items.forEach(i => i.classList.remove('active'));
      this.classList.add('active');

      // Gọi AJAX để load nội dung chat
      loadChat(sessionId);

      // Gọi loadUserInfo(sessionId)
      loadUserInfo(sessionId);
    });
  });
}


  // Gọi khi trang load
  document.addEventListener('DOMContentLoaded', loadConversations);

  // Hàm tải nội dung chat
  function loadChat(sessionId) {
    fetch(`../api/load_chat.php?session_id=${sessionId}`)
      .then(response => response.text())
      .then(html => {
        document.getElementById('chat-body').innerHTML = html;
      });
  }

  function updateCustomerInfo(data) {
  document.getElementById("customer-name").innerText = data.name || "Không rõ tên";
  document.getElementById("customer-phone").innerText = "📞 " + (data.phone || "--");
  document.getElementById("customer-email").innerText = "✉️ " + (data.email || "--");
  document.getElementById("customer-tag").innerText = data.tag || "Khách thường";
  document.getElementById("customer-tag").className = "badge bg-" + (data.tagColor || "secondary");

  const orderList = document.getElementById("order-history");
  orderList.innerHTML = "";
  if (data.orders && data.orders.length > 0) {
    data.orders.forEach(item => {
      const li = document.createElement("li");
      li.className = "list-group-item";
      li.innerText = item;
      orderList.appendChild(li);
    });
  } else {
    orderList.innerHTML = '<li class="list-group-item text-muted">Không có đơn hàng</li>';
  }
}

  // Hàm tải thông tin KH
  function loadUserInfo(sessionId) {
  fetch(`../api/get_user_info.php?session_id=${sessionId}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        document.getElementById('customer-info').innerHTML = '<p class="text-danger">Không tìm thấy thông tin khách hàng</p>';
        return;
      }

      // Cập nhật UI bằng hàm riêng
      updateCustomerInfo({
        name: data.user_name,
        phone: data.user_phone,
        email: data.user_email,
        tag: data.user_type || "Khách thường",
        tagColor: data.user_type === "VIP" ? "warning" : "secondary",
        orders: data.orders || []
      });
    })
    .catch(error => {
      console.error('Lỗi:', error);
    });
}

</script>

<script>
let currentSessionId = null; // lưu session hiện tại

document.getElementById("staff-reply-form").addEventListener("submit", function (e) {
  e.preventDefault();
  const message = document.getElementById("staff-input").value.trim();
  if (message === "" || !currentSessionId) return;

  fetch("../api/send_staff_reply.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      session_id: currentSessionId,
      message: message
    })
  })
  .then(res => res.json())
  .then(data => {
  console.log("Phản hồi từ server:", data); // Thêm dòng này để debug
  if (data.success) {
    loadChat(currentSessionId);
    document.getElementById("staff-input").value = "";
  } else {
    alert("Lỗi gửi tin nhắn: " + data.error);
  }
});

});
</script>


</body>
</html>