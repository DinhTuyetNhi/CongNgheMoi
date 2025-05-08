<?php
include('server/connection.php');
if(isset($_GET['product_id'])){
      $product_id = $_GET['product_id'];
      $stmt = $conn->prepare("SELECT * FROM products where product_id = ?");
      $stmt->bind_param("i",$product_id);
      $stmt->execute();
      $product = $stmt->get_result();
}
else{
  header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Trang Chi Tiết Sản Phẩm</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style2.css" rel="stylesheet">
    <link href="css/style5.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div id="nav" class="row bg-secondary py-2 px-xl-5 " >
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="">Câu hỏi thường gặp</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Giúp đỡ</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Hỗ trợ</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="" class="text-decoration-none">
                    <h4 class="m-0 display-5 font-weight-semi-bold" style="color: #d19c97;"><span class="text-primary font-weight-bold px-3 mr-1"><img src="assets/imgs/Logo.png" alt="" width="30%"></span>Liceria & Co</h4>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
                <a href="" class="btn border">
                    <i class="fas fa-heart text-primary"></i>
                </a>
                <a href="cart.php" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Tất cả sản phẩm</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        <a href="shop.php" class="nav-item nav-link">Giày Thể Thao</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Sandals</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Boot</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Cao Gót</a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.php" class="nav-item nav-link">Trang Chủ</a>
                            <a href="shop.php" class="nav-item nav-link">Sản Phẩm</a>
                            <a href="introduce.php" class="nav-item nav-link">Tin Tức</a>
                            <a href="contact.php" class="nav-item nav-link">Cửa Hàng</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            <a href="login.php" class="nav-item nav-link">Đăng Nhập</a>
                            <a href="register.php" class="nav-item nav-link">Đăng Kí</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Chi Tiết Sản Phẩm</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Chi Tiết Sản Phẩm</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
    <div class="row px-xl-5">
        <?php while($row = $product->fetch_assoc()){?>
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="assets/imgs/<?php echo $row['product_image']; ?>" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="assets/imgs/<?php echo $row['product_image2']; ?>" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="assets/imgs/<?php echo $row['product_image3']; ?>" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="assets/imgs/<?php echo $row['product_image4']; ?>" alt="Image">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold"><?php echo $row['product_name']; ?></h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">(50 Đánh giá)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">$<?php echo $row['product_price'];?></h3>
                <p class="mb-4"><?php echo $row['product_description']; ?></p>
                
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
                    <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>"/>
                    <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>"/>
                    <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>"/>
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-minus">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control bg-secondary text-center" id="product_quantity" name="product_quantity" value="1" readonly>
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-plus">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-primary px-3" name="add_to_cart" type="submit">
                            <i class="fa fa-shopping-cart mr-1"></i> Thêm vào giỏ hàng
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>



    <!--Phần mô tả sản phẩm và xem đánh giá -->
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <!--Phần mô tả sản phẩm -->
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Mô tả sản phẩm</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Đánh giá</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Mô tả sản phẩm</h4>
                        <p>Eos no lorem eirmod diam diam, eos elitr et gubergren diam sea. Consetetur vero aliquyam invidunt duo dolores et duo sit. Vero diam ea vero et dolore rebum, dolor rebum eirmod consetetur invidunt sed sed et, lorem duo et eos elitr, sadipscing kasd ipsum rebum diam. Dolore diam stet rebum sed tempor kasd eirmod. Takimata kasd ipsum accusam sadipscing, eos dolores sit no ut diam consetetur duo justo est, sit sanctus diam tempor aliquyam eirmod nonumy rebum dolor accusam, ipsum kasd eos consetetur at sit rebum, diam kasd invidunt tempor lorem, ipsum lorem elitr sanctus eirmod takimata dolor ea invidunt.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0">
                                        Sit erat duo lorem duo ea consetetur, et eirmod takimata.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Amet kasd gubergren sit sanctus et lorem eos sadipscing at.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Duo amet accusam eirmod nonumy stet et et stet eirmod.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Takimata ea clita labore amet ipsum erat justo voluptua. Nonumy.
                                    </li>
                                  </ul> 
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0">
                                        Sit erat duo lorem duo ea consetetur, et eirmod takimata.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Amet kasd gubergren sit sanctus et lorem eos sadipscing at.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Duo amet accusam eirmod nonumy stet et et stet eirmod.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Takimata ea clita labore amet ipsum erat justo voluptua. Nonumy.
                                    </li>
                                  </ul> 
                            </div>
                        </div>
                        <p><?php echo $row['product_description']; ?></p>   
                    </div>
                    <?php }?>
                    <!--Phần mô tả sản phẩm -->
                    <div class="tab-pane fade" id="tab-pane-2">
                        <div class="row">
                                <h4 class="mb-4">Đánh giá sản phẩm</h4>
                                <div class="rating-summary">
                                    <div class="average-rating">
                                        <div class="big-rating">4.8</div>
                                        <div class="star-rating">
                                            <div class="star active">★</div>
                                            <div class="star active">★</div>
                                            <div class="star active">★</div>
                                            <div class="star active">★</div>
                                            <div class="star active">★</div>
                                        </div>
                                        <div class="total-reviews">124 đánh giá</div>
                                    </div>
                                    <div class="rating-bars">
                                        <div class="rating-bar">
                                            <div class="rating-label">5★</div>
                                            <div class="progress-bar">
                                                <div class="progress" style="width: 85%;"></div>
                                            </div>
                                            <div class="rating-count">103</div>
                                        </div>
                                        <div class="rating-bar">
                                            <div class="rating-label">4★</div>
                                            <div class="progress-bar">
                                                <div class="progress" style="width: 12%;"></div>
                                            </div>
                                            <div class="rating-count">15</div>
                                        </div>
                                        <div class="rating-bar">
                                            <div class="rating-label">3★</div>
                                            <div class="progress-bar">
                                                <div class="progress" style="width: 3%;"></div>
                                            </div>
                                            <div class="rating-count">4</div>
                                        </div>
                                        <div class="rating-bar">
                                            <div class="rating-label">2★</div>
                                            <div class="progress-bar">
                                                <div class="progress" style="width: 0%;"></div>
                                            </div>
                                            <div class="rating-count">0</div>
                                        </div>
                                        <div class="rating-bar">
                                            <div class="rating-label">1★</div>
                                            <div class="progress-bar">
                                                <div class="progress" style="width: 2%;"></div>
                                            </div>
                                            <div class="rating-count">2</div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="other-reviews">
                            <h2 class="section-title">Đánh giá từ khách hàng khác</h2>
            
                            <div class="filter-bar">
                                <div class="filter-options">
                                    <select>
                                        <option>Mới nhất</option>
                                        <option>Cũ nhất</option>
                                        <option>Đánh giá cao nhất</option>
                                        <option>Đánh giá thấp nhất</option>
                                    </select>
                                    
                                    <select>
                                        <option>Tất cả đánh giá</option>
                                        <option>5 sao</option>
                                        <option>4 sao</option>
                                        <option>3 sao</option>
                                        <option>2 sao</option>
                                        <option>1 sao</option>
                                    </select>
                                    
                                    <select>
                                        <option>Tất cả</option>
                                        <option>Có hình ảnh</option>
                                        <option>Có video</option>
                                    </select>
                                </div>
                                
                                <div class="search-box">
                                    <span class="search-icon">🔍</span>
                                    <input type="text" placeholder="Tìm kiếm đánh giá">
                                </div>
                            </div>
                            
                            <!-- Review Item 1 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="/api/placeholder/40/40" alt="Reviewer" class="reviewer-avatar">
                                        <div>
                                            <div class="reviewer-name">Trần Thị B</div>
                                            <div class="review-date">15/04/2025</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="review-rating">
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                </div>
                                
                                <h4>Sản phẩm tuyệt vời, đáng mua!</h4>
                                
                                <div class="review-text">
                                    iPhone 15 Pro Max là chiếc điện thoại tốt nhất mà tôi từng sử dụng. Hiệu năng mạnh mẽ, pin trâu, camera chụp đẹp trong mọi điều kiện ánh sáng. Màn hình hiển thị rõ nét, màu sắc chân thực. Thời lượng pin đủ dùng trong 1-2 ngày với nhu cầu sử dụng bình thường. Rất hài lòng với sản phẩm!
                                </div>
                                
                                <div class="review-images">
                                    <img src="/api/placeholder/80/80" alt="Review Image" class="review-image">
                                    <img src="/api/placeholder/80/80" alt="Review Image" class="review-image">
                                    <img src="/api/placeholder/80/80" alt="Review Image" class="review-image">
                                </div>
                                
                                <div class="review-footer">
                                    <div>Đánh giá này có hữu ích không?</div>
                                    <button class="helpful-btn">👍 Có (23)</button>
                                </div>
                            </div>
                            
                            <!-- Review Item 2 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="/api/placeholder/40/40" alt="Reviewer" class="reviewer-avatar">
                                        <div>
                                            <div class="reviewer-name">Lê Văn C</div>
                                            <div class="review-date">10/04/2025</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="review-rating">
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star">★</div>
                                </div>
                                
                                <h4>Pin trâu, camera đẹp nhưng giá cao</h4>
                                
                                <div class="review-text">
                                    Sản phẩm tốt nhưng giá khá cao so với các đối thủ cạnh tranh. Camera chụp đẹp, đặc biệt là chế độ chụp đêm rất ấn tượng. Pin dùng được khoảng 1.5 ngày với nhu cầu sử dụng thông thường. Điểm trừ duy nhất là giá thành và việc sạc pin hơi chậm.
                                </div>
                                
                                <div class="review-footer">
                                    <div>Đánh giá này có hữu ích không?</div>
                                    <button class="helpful-btn">👍 Có (11)</button>
                                </div>
                            </div>
                            
                            <!-- Review Item 3 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <img src="/api/placeholder/40/40" alt="Reviewer" class="reviewer-avatar">
                                        <div>
                                            <div class="reviewer-name">Phạm Thị D</div>
                                            <div class="review-date">05/04/2025</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="review-rating">
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                    <div class="star active">★</div>
                                </div>
                                
                                <h4>Quá xuất sắc, đáng đồng tiền</h4>
                                
                                <div class="review-text">
                                    Quá hài lòng với chiếc iPhone 15 Pro Max này. Thiết kế sang trọng, cầm nắm vừa tay. Hiệu năng mạnh mẽ xử lý mọi tác vụ nặng như game, chỉnh sửa video đều mượt mà. Camera chụp cực kỳ sắc nét, màu sắc tự nhiên. Pin dùng thoải mái cả ngày dài. Tính năng mới nhất của iOS cũng rất thú vị và hữu ích.
                                </div>
                                
                                <div class="review-images">
                                    <img src="/api/placeholder/80/80" alt="Review Image" class="review-image">
                                    <img src="/api/placeholder/80/80" alt="Review Image" class="review-image">
                                </div>
                                
                                <div class="review-footer">
                                    <div>Đánh giá này có hữu ích không?</div>
                                    <button class="helpful-btn">👍 Có (19)</button>
                                </div>
                            </div>
                            
                            <div class="pagination">
                                <div class="page-item active">1</div>
                                <div class="page-item">2</div>
                                <div class="page-item">3</div>
                                <div class="page-item">4</div>
                                <div class="page-item">5</div>
                                <div class="page-item">...</div>
                                <div class="page-item">10</div>
                            </div>
                        </div>
                    </div>
                        
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


      <!--FEATURE sport shoes-->
      <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
          <h3>CÁC SẢN PHẨM TƯƠNG TỰ</h3>
          <hr class="mx-auto">
        </div>
        <div class="row mx-auto container-fluid">

        <?php include('server/get_featured_products.php')?>
        
        <?php while($row= $featured_products->fetch_assoc()){ ?>
          <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image'] ?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name'];?></h5>
            <h4 class="p-price">$ <?php echo $row['product_price']; ?></h4>
            <a href="<?php echo "detail.php?product_id=".$row['product_id'];?>"><button class="buy-btn">Buy Now</button></a>
          </div>

          <?php } ?>
        </div>
      </section>


    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold px-3 mr-1"><img src="assets/imgs/Logo.png" alt="" width="30%"></span></h1>
                </a>
                <p>Chúng tôi cung cấp những sản phẩm tốt nhất với mức giá phải chăng nhất.</p>

            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Liên kết nhanh</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang Chủ</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Sản Phẩm</a>
                            <a class="text-dark mb-2" href="introduce.php"><i class="fa fa-angle-right mr-2"></i>Tin tức</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ Hàng</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Thanh Toán</a>
                            <a class="text-dark" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Cửa Hàng</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Liên hệ với chúng tôi</h5>
                        <div class="d-flex flex-column justify-content-start">
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>12 Nguyen Van Bao, quan Go Vap, thanh pho Ho Chi Minh</p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>091 234 5678</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Hệ thống cửa hàng</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 1</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 2</a>
                            <a class="text-dark mb-2" href="introduce.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 3</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 4</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng 5</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">Bản quyền thuộc về Liceria & Co Shop</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const btnPlus = document.querySelector('.btn-plus');
    const btnMinus = document.querySelector('.btn-minus');
    const quantityInput = document.querySelector('#product_quantity');

    btnPlus.addEventListener('click', function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của nút
        let currentValue = parseInt(quantityInput.value);
        if (!isNaN(currentValue)) {
            quantityInput.value = currentValue + 1; // Tăng 1 đơn vị
        }
    });

    btnMinus.addEventListener('click', function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của nút
        let currentValue = parseInt(quantityInput.value);
        if (!isNaN(currentValue) && currentValue > 1) {
            quantityInput.value = currentValue - 1; // Giảm 1 đơn vị
        }
    });
});

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