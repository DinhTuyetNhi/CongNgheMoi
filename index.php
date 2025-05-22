<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Trang chủ</title>
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
    <style>
        #chatbot-body {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-message {
            align-self: flex-end; /* Đẩy sang phải */
            background: #0084ff;
            color: #fff;
            padding: 8px 14px;
            border-radius: 18px 18px 4px 18px;
            margin: 6px 0 6px 40px;
            max-width: 80%;
            font-size: 15px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.07);
            word-break: break-word;
            display: block;
        }
</style>
</head>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-2 px-xl-5">
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
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Tất cả sản phẩm</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        <a href="shop.php" class="nav-item nav-link">Giày thể thao</a>
                        <a href="shop.php" class="nav-item nav-link">Giày Sandal</a>
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
                            <a href="index.php" class="nav-item nav-link active">Trang chủ</a>
                            <a href="shop.php" class="nav-item nav-link">Sản phẩm</a>
                            <a href="introduce.php" class="nav-item nav-link">Tin tức</a>
                            <a href="contact.php" class="nav-item nav-link">Cửa hàng</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            <a href="login.php" class="nav-item nav-link">Đăng nhập</a>
                            <a href="register.php" class="nav-item nav-link">Đăng kí</a>
                        </div>
                    </div>
                </nav>
                <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="assets\imgs\mau1.jpg" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Giảm giá 10% cho đơn hàng đầu tiên của bạn</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Thời trang </h3>
                                    <a href="" class="btn btn-light py-2 px-3">Mua Ngay</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" style="height: 410px;">
                            <img class="img-fluid" src="assets\imgs\mau2.jpg" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Giảm giá 10% cho đơn hàng đầu tiên của bạn</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Giá cả hợp lý</h3>
                                    <a href="" class="btn btn-light py-2 px-3">Mua Ngay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


      <!--Brand-->
      <section id="brand" class="container">
        <div class="row">
          <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand1.png"/>
          <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand2.png"/>
          <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand3.png"/>
          <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand4.png"/>
        </div>
      </section>


      <!--New-->
      <section id="new" class="w-100">
        <div class="row p-0 m-0">
          <!--ONE-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/1.png"/>
            <div class="details">
              <h2>Giày cực kỳ tuyệt vời</h2>
              <button class="text-uppercase">Mua Ngay</button>
            </div>
          </div>

          <!--TWO-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/cao1.png"/>
            <div class="details">
              <h2>Giày cao gót tuyệt vời</h2>
              <button class="text-uppercase">Mua Ngay</button>
            </div>
          </div>

          <!--THREE-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/sport3.png"/>
            <div class="details">
              <h2>50% OFF</h2>
              <button class="text-uppercase">GIẢM GIÁ 50%</button>
            </div>
          </div>
        </div>
      </section>



      <!--FEATURE sport shoes-->
      <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
          <h3>Giày thể thao</h3>
          <hr class="mx-auto">
          <p>Tại đây bạn có thể kiểm tra sản phẩm nổi bật của chúng tôi</p>
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
            <h4 class="p-price"><?php echo $row['product_price']; ?> đ</h4>
            <a href="<?php echo "detail.php?product_id=".$row['product_id'];?>"><button class="buy-btn">Mua Ngay</button></a>
          </div>

          <?php } ?>
        </div>
      </section>


      <!--Sandal-->
      <section id="featured" class="my-5">
        <div class="container text-center mt-5 py-5">
          <h3>Giày Sandal</h3>
          <hr class="mx-auto">
          <p>Ở đây bạn có thể xem những đôi Sandals tuyệt vời của chúng tôi</p>
        </div>
        <div class="row mx-auto container-fluid">
          <?php include('server/get_sandals.php');?>
          <?php while($row= $sandals->fetch_assoc()){ ?>
          <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="p-price"><?php echo $row['product_price']; ?> đ</h4>
            <a href="<?php echo "detail.php?product_id=".$row['product_id'];?>"><button class="buy-btn">Mua Ngay</button></a>
          </div>

         <?php } ?>
        </div>
      </section>

      <!--BOOTS-->
      <section id="featured" class="my-5">
        <div class="container text-center mt-5 py-5">
          <h3>Giày Boot</h3>
          <hr class="mx-auto">
          <p>Ở đây bạn có thể xem những đôi bốt tuyệt vời của chúng tôi</p>
        </div>
        <div class="row mx-auto container-fluid">
          <?php include('server/get_boots.php');?>
          <?php while($row= $boots->fetch_assoc()){ ?>
          <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="p-price"><?php echo $row['product_price']; ?> đ</h4>
            <a href="<?php echo "detail.php?product_id=".$row['product_id'];?>"><button class="buy-btn">Mua Ngay</button></a>
          </div>

         <?php } ?>
        </div>
      </section>


      <!--High Heels-->
      <section id="featured" class="my-5">
        <div class="container text-center mt-5 py-5">
          <h3>Giày Cao Gót</h3>
          <hr class="mx-auto">
          <p>Ở đây bạn có thể xem những đôi giày cao gót tuyệt vời của chúng tôi</p>
        </div>
        <div class="row mx-auto container-fluid">
          <?php include('server/get_highheels.php');?>
          <?php while($row= $highheels->fetch_assoc()){ ?>
          <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="p-price"><?php echo $row['product_price']; ?> đ</h4>
            <a href="<?php echo "detail.php?product_id=".$row['product_id'];?>"><button class="buy-btn">Mua Ngay</button></a>
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
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Sản phẩm</a>
                            <a class="text-dark mb-2" href="introduce.php"><i class="fa fa-angle-right mr-2"></i>Tin tức</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ hàng</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Thanh toán</a>
                            <a class="text-dark" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Cửa hàng</a>
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
                        <h5 class="font-weight-bold text-dark mb-4">Bản tin mới</h5>
                        <form action="">
                            <div class="form-group">
                                <input type="text" class="form-control border-0 py-4" placeholder="Tên của bạn" required="required" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control border-0 py-4" placeholder="Email của bạn"
                                    required="required" />
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block border-0 py-3" type="submit">Đăng ký ngay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">Liceria & Co Shop</a>. Đã đăng ký Bản quyền.
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

    <!-- Chatbot Icon -->
    <div id="chatbot-icon" onclick="toggleChat()">
        <i class='far fa-comment-dots'></i>
    </div>

    <!-- Chatbot Window -->
    <div id="chatbot-window">
    <div id="chatbot-header">
        <span>Trợ lý giày ảo 👟</span>
        <button onclick="toggleChat()">✖</button>
    </div>
    <div id="chatbot-body">
        <div class="bot-message">Chào bạn! Mình là Trợ lý giày ảo 👟.<br>Bạn cần tư vấn mua giày gì hôm nay?</div>
        <!-- Tin nhắn sẽ thêm ở đây bằng JS -->
    </div>
    <div id="chatbot-input">
    <input type="text" id="user-input" onkeypress="handleKeyPress(event)" placeholder="Nhập tin nhắn...">
        <button onclick="sendMessage()">Gửi</button>
    </div>
    </div>
    
    <script>
        
    let sessionId = localStorage.getItem('chat_session_id');
    const userId = <?php echo $user_id !== null ? $user_id : 'null'; ?>;


    function ensureSessionId(callback, errorCallback) {
        if (sessionId) {
            callback();
        } else {
            fetch('http://localhost/project3/project/api/create_session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.session_id) {
                    sessionId = data.session_id;
                    localStorage.setItem('chat_session_id', sessionId);
                    callback();
                } else {
                    if (errorCallback) errorCallback();
                }
            })
            .catch(() => {
                if (errorCallback) errorCallback();
            });
        }
    }

    function toggleChat() {
        const chatWindow = document.getElementById("chatbot-window");
        chatWindow.style.display = chatWindow.style.display === "none" ? "flex" : "none";
    }

    function handleKeyPress(event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    }

    let lastMessageId = 0;

    function sendMessage() {
        const input = document.getElementById("user-input");
        const sendBtn = document.querySelector("#chatbot-input button");
        const message = input.value.trim();
        if (message === "") return;

        // Xóa input NGAY khi nhấn gửi
        input.value = "";

        // KHÔNG disable input và nút gửi nữa

        ensureSessionId(() => {
            const chatBody = document.getElementById("chatbot-body");

            // Hiển thị tin nhắn người dùng ngay lập tức
            const userMsg = document.createElement("div");
            userMsg.textContent = message;
            userMsg.className = "user-message";
            chatBody.appendChild(userMsg);

            // Thêm hiệu ứng loading
            const loadingMsg = document.createElement("div");
            loadingMsg.className = "bot-message";
            loadingMsg.id = "loading-msg";
            loadingMsg.textContent = "Đang trả lời...";
            chatBody.appendChild(loadingMsg);

            chatBody.scrollTop = chatBody.scrollHeight;

            fetch('http://localhost/project3/project/api/chatbot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: message,
                    session_id: sessionId,
                    user_id: userId
                })
            })
            .then(async response => {
                const text = await response.text();
                try {
                    const data = JSON.parse(text);
                    if (data.error) throw new Error(data.error);
                    return data;
                } catch (e) {
                    throw new Error("Phản hồi không hợp lệ từ server: " + text);
                }
            })
            .then(data => {
                // Xóa loading
                const loading = document.getElementById("loading-msg");
                if (loading) loading.remove();

                // CHỈ hiển thị bot-message nếu có nội dung trả lời
                if (data.reply && data.reply.trim() !== "") {
                    const botMsg = document.createElement("div");
                    botMsg.className = "bot-message";
                    botMsg.textContent = data.reply;
                    chatBody.appendChild(botMsg);
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            })
            .catch(error => {
                // Xóa loading
                const loading = document.getElementById("loading-msg");
                if (loading) loading.remove();

                console.error("Lỗi fetch: ", error);
                const errorMsg = document.createElement("div");
                errorMsg.className = "bot-message";
                errorMsg.textContent = "Lỗi: " + error.message;
                chatBody.appendChild(errorMsg);
            })
            .finally(() => {
                // KHÔNG enable lại input/nút gửi vì không disable nữa
                input.focus();
            });
        }, () => {
            alert("Không thể tạo phiên chat. Vui lòng thử lại!");
        });
    }

    // Check định kỳ mỗi 2 giây để lấy tin nhắn mới từ agent
    // setInterval(() => {
    //     fetch(`http://localhost/project3/project/api/check_new_messages.php?session_id=${sessionId}&last_id=${lastMessageId}`)
    //         .then(res => res.json())
    //         .then(messages => {
    //             // ...append các tin nhắn mới vào giao diện...
    //         })
    //         .catch(err => {
    //             console.error("Lỗi khi check tin nhắn mới:", err);
    //         });
    // }, 2000);
</script>




    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
     
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script>
    // Kết nối tới WebSocket server (đảm bảo server.js đang chạy ở port 3001)
    const socket = io('http://localhost:3001');

    // Khi đã có sessionId, join vào phòng chat riêng
    function joinChatRoom() {
        if (sessionId) {
            socket.emit('join', sessionId);
        }
    }
    // Gọi khi vừa load trang hoặc vừa tạo session mới
    ensureSessionId(joinChatRoom);

    // Nếu sessionId thay đổi (tạo mới), join lại phòng
    // Bạn có thể gọi joinChatRoom() ở nơi khác nếu cần

    // Khi gửi tin nhắn, gửi qua WebSocket để realtime cho nhân viên
    // (KHÔNG thay đổi hàm sendMessage cũ, chỉ bổ sung gửi qua socket)
    function sendMessage() {
        const input = document.getElementById("user-input");
        const message = input.value.trim();
        if (message === "") return;

        input.value = "";

        ensureSessionId(() => {
            const chatBody = document.getElementById("chatbot-body");

            // Hiển thị tin nhắn người dùng ngay lập tức
            const userMsg = document.createElement("div");
            userMsg.textContent = message;
            userMsg.className = "user-message";
            chatBody.appendChild(userMsg);

            // Thêm hiệu ứng loading
            const loadingMsg = document.createElement("div");
            loadingMsg.className = "bot-message";
            loadingMsg.id = "loading-msg";
            loadingMsg.textContent = "Đang trả lời...";
            chatBody.appendChild(loadingMsg);

            chatBody.scrollTop = chatBody.scrollHeight;

            // Gửi lên server PHP như cũ để lưu DB và nhận phản hồi bot
            fetch('http://localhost/project3/project/api/chatbot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: message,
                    session_id: sessionId,
                    user_id: userId
                })
            })
            .then(async response => {
                const text = await response.text();
                try {
                    const data = JSON.parse(text);
                    if (data.error) throw new Error(data.error);
                    return data;
                } catch (e) {
                    throw new Error("Phản hồi không hợp lệ từ server: " + text);
                }
            })
            .then(data => {
                // Xóa loading
                const loading = document.getElementById("loading-msg");
                if (loading) loading.remove();

                // CHỈ hiển thị bot-message nếu có nội dung trả lời
                if (data.reply && data.reply.trim() !== "") {
                    const botMsg = document.createElement("div");
                    botMsg.className = "bot-message";
                    botMsg.textContent = data.reply;
                    chatBody.appendChild(botMsg);
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            })
            .catch(error => {
                // Xóa loading
                const loading = document.getElementById("loading-msg");
                if (loading) loading.remove();

                console.error("Lỗi fetch: ", error);
                const errorMsg = document.createElement("div");
                errorMsg.className = "bot-message";
                errorMsg.textContent = "Lỗi: " + error.message;
                chatBody.appendChild(errorMsg);
            })
            .finally(() => {
                input.focus();
            });

            // Gửi qua WebSocket để nhân viên nhận realtime
            socket.emit('chat_message', {
                session_id: sessionId,
                sender: 'user',
                message: message
            });
        }, () => {
            alert("Không thể tạo phiên chat. Vui lòng thử lại!");
        });
    }

    // Lắng nghe tin nhắn realtime từ nhân viên (agent)
    socket.on('chat_message', function(data) {
        if (data.session_id == sessionId && data.sender === 'agent') {
            const chatBody = document.getElementById("chatbot-body");
            const agentMsg = document.createElement("div");
            agentMsg.className = "bot-message";
            agentMsg.textContent = data.message;
            chatBody.appendChild(agentMsg);
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    });

    // Nếu muốn tối ưu, sau khi test WebSocket ổn định, bạn có thể bỏ polling cũ (setInterval)
    // Hiện tại, giữ nguyên polling để dự phòng, không ảnh hưởng gì

    // Nếu sessionId được tạo mới (ví dụ sau khi đăng nhập), hãy gọi lại joinChatRoom()
    // Ví dụ, sau khi set sessionId mới:
    // sessionId = ...;
    // joinChatRoom();

</script>
</body>
</html>