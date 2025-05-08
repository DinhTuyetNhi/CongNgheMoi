<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đánh giá của khách hàng</title>
    <link rel="stylesheet" href="css/style5.css">
</head>
<body>
<div class="review-container">
            <h2 class="section-title">Viết đánh giá của bạn</h2>
            
            <div class="product-info">
                <img src="/api/placeholder/100/100" alt="iPhone 15 Pro Max" class="product-image">
                <div class="product-details">
                    <h3>iPhone 15 Pro Max 256GB</h3>
                    <div class="product-meta">Màu sắc: Titan Tự Nhiên</div>
                    <div class="product-meta">Dung lượng: 256GB</div>
                    <div class="product-meta">Ngày mua: 06/05/2025</div>
                </div>
            </div>
            
            <div class="rating-section">
                <div class="rating-title">Đánh giá của bạn</div>
                <div class="star-rating">
                    <div class="star" onclick="setRating(1)">★</div>
                    <div class="star" onclick="setRating(2)">★</div>
                    <div class="star" onclick="setRating(3)">★</div>
                    <div class="star" onclick="setRating(4)">★</div>
                    <div class="star" onclick="setRating(5)">★</div>
                </div>
                <div class="rating-labels">
                    <span>Rất tệ</span>
                    <span>Tệ</span>
                    <span>Bình thường</span>
                    <span>Tốt</span>
                    <span>Rất tốt</span>
                </div>
            </div>
            
            <form class="review-form">
                <div>
                    <label for="review-title">Tiêu đề đánh giá</label>
                    <input type="text" id="review-title" placeholder="Nhập tiêu đề ngắn gọn cho đánh giá của bạn">
                </div>
                
                <div>
                    <label for="review-content">Chi tiết đánh giá</label>
                    <textarea id="review-content" placeholder="Hãy chia sẻ trải nghiệm của bạn về sản phẩm này"></textarea>
                </div>
                
                <div class="photo-upload">
                    <label>Thêm hình ảnh (tùy chọn)</label>
                    <div class="upload-btn">
                        <i>+</i> Tải ảnh lên
                    </div>
                    <span>Tối đa 5 ảnh</span>
                    
                    <div class="preview-images">
                        <div style="position: relative;">
                            <img src="/api/placeholder/80/80" alt="Preview" class="preview-image">
                            <span class="remove-img">×</span>
                        </div>
                        <div style="position: relative;">
                            <img src="/api/placeholder/80/80" alt="Preview" class="preview-image">
                            <span class="remove-img">×</span>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">Gửi đánh giá</button>
            </form>
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
    function setRating(rating) {
        // Reset all stars
        const stars = document.querySelectorAll('.rating-section .star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    // Add event listeners when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent form submission for demo
        const form = document.querySelector('.review-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Cảm ơn bạn đã gửi đánh giá!');
        });
        
        // Add click handlers for helpful buttons
        const helpfulBtns = document.querySelectorAll('.helpful-btn');
        helpfulBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentText = btn.innerText;
                const count = parseInt(currentText.match(/\d+/)[0]);
                btn.innerText = `👍 Có (${count + 1})`;
            });
        });
        
        // Add click handlers for remove image buttons
        const removeButtons = document.querySelectorAll('.remove-img');
        removeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                btn.parentElement.remove();
            });
        });
    });
</script>
</body>
</html>