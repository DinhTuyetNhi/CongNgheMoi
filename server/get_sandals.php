<?php
include('connection.php');


$stmt = $conn->prepare("SELECT * FROM products  where product_category = 'Sandals'");
$stmt->execute();
$sandals = $stmt->get_result();
?>