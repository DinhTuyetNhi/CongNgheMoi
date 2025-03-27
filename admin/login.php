<?php
include('../server/connection.php');
session_start();

if(isset($_SESSION['admin_logged_in'])){
  header('location: dashboard.php');
  exit();
}

if(isset($_POST['login_btn'])){
  $email = $_POST['email'];
  $password = md5($_POST['password']); // Nên thay bằng password_hash khi đăng ký và password_verify khi đăng nhập

  $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password FROM admins WHERE admin_email=? AND admin_password=? LIMIT 1");
  $stmt->bind_param('ss', $email, $password);

  if($stmt->execute()){
    $stmt->bind_result($admin_id, $admin_name, $admin_email, $admin_password);
    $stmt->store_result();

    if ($stmt->num_rows() == 1) {
      // Lấy kết quả đã bind
      $stmt->fetch();
      
      // Thiết lập session khi admin đăng nhập thành công
      $_SESSION['admin_id'] = $admin_id;
      $_SESSION['admin_name'] = $admin_name;
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['admin_logged_in'] = true;
  
      header('location: dashboard.php?login_success=logged in successfully');
      exit();
    } else {
      header('location: login.php?error=could not verify your account');
      exit();
    }
  } else {
    header('location: login.php?error=something went wrong');
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Nhi & Thanh Company</a>
        
    </nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="text-center mb-4">Login</h3>
                    <form action="login.php" method="POST" enctype="multipart/form-data" id="login-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block" name="login_btn">Login</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>