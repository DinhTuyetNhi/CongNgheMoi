<?php
$conn = mysqli_connect("localhost","nhidinh","123","shoesproject")
or die("Khong ket noi duoc db");

// Thiết lập charset để hỗ trợ tiếng Việt + emoji
$conn->set_charset("utf8mb4")
?>