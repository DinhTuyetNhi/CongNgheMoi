<?php
include('connection.php');


$stmt = $conn->prepare("SELECT * FROM products where product_category = 'Sport Shoes'");
$stmt->execute();
$featured_products = $stmt->get_result();
?>