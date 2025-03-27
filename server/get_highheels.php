<?php
include('connection.php');


$stmt = $conn->prepare("SELECT * FROM products  where product_category = 'High Heels'");
$stmt->execute();
$highheels = $stmt->get_result();
?>