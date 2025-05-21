<?php
include('server/connection.php');
session_start();

$user_id = $_SESSION['user_id'] ?? null;

// Lấy danh sách sản phẩm từ các đơn hàng đã shipped của user
$products = [];
if ($user_id) {
    $sql = "SELECT oi.product_id, oi.product_name, oi.product_image, o.order_date
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            WHERE o.user_id = ? AND o.order_status = 'shipped'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra đã đánh giá chưa
        $stmt2 = $conn->prepare("SELECT review_id FROM product_reviews WHERE user_id = ? AND product_id = ?");
        $stmt2->bind_param("ii", $user_id, $row['product_id']);
        $stmt2->execute();
        $stmt2->store_result();
        $row['reviewed'] = $stmt2->num_rows > 0 ? true : false;
        $stmt2->close();
        $products[] = $row;
    }
    $stmt->close();
}

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $product_id = $_POST['product_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $review_text = $_POST['review_text'] ?? '';

    if ($user_id && $product_id && $rating) {
        // Kiểm tra đã đánh giá chưa
        $stmt = $conn->prepare("SELECT review_id FROM product_reviews WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Bạn đã đánh giá sản phẩm này rồi!";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO product_reviews (user_id, product_id, rating, review_text) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $user_id, $product_id, $rating, $review_text);
            if ($stmt->execute()) {
                $success = "Đánh giá của bạn thành công!";
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại.";
            }
        }
        $stmt->close();
    } else {
        $error = "Bạn cần đăng nhập và điền đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đánh giá của khách hàng</title>
    <link rel="stylesheet" href="css/style5.css">
</head>
<body>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="review-container">
    <h2 class="section-title">Viết đánh giá của bạn</h2>
    <?php if (empty($products)): ?>
        <div>Bạn không có sản phẩm nào ở trạng thái đã giao để đánh giá.</div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-info">
                <img src="assets/imgs/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                <div class="product-details">
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <div class="product-meta">Ngày mua: <?php echo date('d/m/Y', strtotime($product['order_date'])); ?></div>
                </div>
            </div>
            <?php if ($product['reviewed']): ?>
                <div class="already-reviewed">Bạn đã đánh giá sản phẩm này.</div>
            <?php else: ?>
                <form class="review-form" method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="hidden" name="rating" id="rating-value-<?php echo $product['product_id']; ?>" value="0">
                    <div class="rating-section">
                        <div class="rating-title">Đánh giá của bạn</div>
                        <div class="star-rating" data-product="<?php echo $product['product_id']; ?>">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-value="<?php echo $i; ?>" data-product="<?php echo $product['product_id']; ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <div class="rating-labels">
                            <span>Rất tệ</span>
                            <span>Tệ</span>
                            <span>Bình thường</span>
                            <span>Tốt</span>
                            <span>Rất tốt</span>
                        </div>
                    </div>
                    <div>
                        <label for="review-content-<?php echo $product['product_id']; ?>">Chi tiết đánh giá</label>
                        <textarea id="review-content-<?php echo $product['product_id']; ?>" name="review_text" placeholder="Hãy chia sẻ trải nghiệm của bạn về sản phẩm này"></textarea>
                    </div>
                    <button type="submit" class="submit-btn" id="submit-btn-<?php echo $product['product_id']; ?>" disabled>Gửi đánh giá</button>
                </form>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div style="text-align:center; margin-top: 20px;">
    <a href="account.php#orders" class="button" style="background:#f0ad4e; color:#fff; padding:8px 20px; border-radius:4px; text-decoration:none;">Trở về trang đơn hàng của bạn</a>
</div>
</script>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Contact Javascript File -->
<script src="mail/jqBootstrapValidation.min.js"></script>
<script src="mail/contact.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
<script>
$(document).ready(function() {
    $('.star-rating .star').on('click', function() {
        var rating = $(this).data('value');
        var productId = $(this).data('product');
        // Cập nhật đánh giá cho sản phẩm tương ứng
        $('.star-rating[data-product="' + productId + '"] .star').removeClass('active');
        $('.star-rating[data-product="' + productId + '"] .star').each(function(index) {
            if (index < rating) {
                $(this).addClass('active');
            }
        });
        // Gán giá trị rating vào input ẩn
        $('#rating-value-' + productId).val(rating);
        // Enable nút gửi nếu đã chọn sao
        if (rating > 0) {
            $('#submit-btn-' + productId).prop('disabled', false);
        }
    });
    // Nếu chưa chọn sao thì disable nút gửi
    <?php foreach ($products as $product): ?>
        $('#submit-btn-<?php echo $product['product_id']; ?>').prop('disabled', true);
    <?php endforeach; ?>
});
</script>
<script>
document.querySelectorAll('.star-rating').forEach(function(starRating) {
    starRating.addEventListener('click', function(e) {
        if (e.target.classList.contains('star')) {
            const rating = parseInt(e.target.getAttribute('data-value'));
            const productId = e.target.getAttribute('data-product');
            // Đặt giá trị rating vào input ẩn
            document.getElementById('rating-value-' + productId).value = rating;
            // Tô màu các sao
            const stars = starRating.querySelectorAll('.star');
            stars.forEach(function(star, idx) {
                if (idx < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $('.review-form').on('submit', function(e) {
        var productId = $(this).find('input[name="product_id"]').val();
        var rating = $('#rating-value-' + productId).val();
        if (!rating || rating == "0") {
            alert('Vui lòng chọn số sao để đánh giá sản phẩm!');
            e.preventDefault();
            return false;
        }
    });
});
</script>
</body>
</html>