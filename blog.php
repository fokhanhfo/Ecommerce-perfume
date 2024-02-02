<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        .paragraph-container {
            overflow: hidden; /* Ẩn phần tử nếu nội dung vượt quá kích thước */
        }

        #my-paragraph {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Chỉ hiển thị 2 dòng */
            -webkit-box-orient: vertical;
            line-height: 1.2em; /* Điều chỉnh khoảng cách giữa các dòng */
            max-height: 2.4em; /* Khoảng cách giữa các dòng (2 dòng) x line-height (1.2em) */
            overflow: hidden; /* Ẩn phần tử nếu nội dung vượt quá kích thước */
            text-overflow: ellipsis; /* Hiển thị dấu "..." khi nội dung bị ẩn đi */
            white-space: normal; /* Cho phép phần tử xuống dòng tự động nếu cần */
        }
        .pagination {
            display: flex;
            list-style: none;
            justify-content: center;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a, .pagination span {
            padding: 5px 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }

        .pagination .current {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-blog set-bg" data-setbg="img/anhbia.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Our Blog</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">
            <div class="row">
                <?php
                    $select_blog = $conn->prepare("SELECT * FROM tbl_blog ORDER BY id DESC"); 
                    $select_blog->execute();
                    $fetch_blog = $select_blog->fetchAll(PDO::FETCH_ASSOC);
                    if($select_blog->rowCount() > 0){
                        // Số comment trên mỗi trang
                        $commentsPerPage = 6;
                        
                        // Số trang hiện tại
                        if (isset($_GET['page'])) {
                            $currentPage = $_GET['page'];
                        } else {
                            $currentPage = 1;
                        }
                        
                        // Tổng số comment
                        $totalComments = count($fetch_blog);
                        
                        // Tính tổng số trang
                        $totalPages = ceil($totalComments / $commentsPerPage);
                        
                        // Tính chỉ số bắt đầu và kết thúc của comment mà bạn muốn hiển thị trên trang hiện tại
                        $startIndex = ($currentPage - 1) * $commentsPerPage;
                        $endIndex = min($startIndex + $commentsPerPage - 1, $totalComments - 1);
                        
                        // Lấy danh sách comment cho trang hiện tại
                        $commentsOnCurrentPage = array_slice($fetch_blog, $startIndex, $commentsPerPage);
                    foreach($commentsOnCurrentPage as $fetch_blog){
                        $formatted_time_post = date("d F Y", strtotime($fetch_blog["time_post"]));
                ?>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="uploaded_img/<?= $fetch_blog['blog_image'] ?>"></div>
                        <div class="blog__item__text paragraph-container">
                            <span><img src="img/icon/calendar.png" alt=""><?= $formatted_time_post ?></span>
                            <h5 id="my-paragraph"><?= $fetch_blog['title'] ?></h5>
                            <a href="blog-details.php?blog_id=<?= $fetch_blog['id']; ?>">READ MORE</a>
                        </div>
                    </div>
                </div>
                <?php
                    }
                    }else{
                        echo '<p class="empty">no image!</p>';
                    }
                ?>
            </div>
            <div class="pagination">
                <ul class="pagination">
                    <?php if ($currentPage > 1) : ?>
                        <li><a href="?page=1">&lt;&lt;</a></li>
                        <li><a href="?page=<?php echo $currentPage - 1; ?>">&lt;</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <?php if ($i == $currentPage) : ?>
                            <li><span class="current"><?php echo $i; ?></span></li>
                        <?php else : ?>
                            <?php if (abs($i - $currentPage) <= 3 || $i == 1 || $i == $totalPages) : ?>
                                <li><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php elseif ($i == 2 || $i == $totalPages - 1) : ?>
                                <li><span class="ellipsis">...</span></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages) : ?>
                        <li><a href="?page=<?php echo $currentPage + 1; ?>">&gt;</a></li>
                        <li><a href="?page=<?php echo $totalPages; ?>">&gt;&gt;</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/footer-logo.png" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="img/payment.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Trending Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Payment Methods</a></li>
                            <li><a href="#">Delivary</a></li>
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
                                <button type="submit"><span class="icon_mail_alt"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                                aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>