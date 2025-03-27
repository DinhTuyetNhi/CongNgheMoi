<?php
include('connection.php');


$stmt = $conn->prepare("SELECT * FROM products  where product_category = 'Boots'");
$stmt->execute();
$boots = $stmt->get_result();
?>