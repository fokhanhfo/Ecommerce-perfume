<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
if(isset($_POST['wishlist'])){
    if(isset($_SESSION['user_id'])){
        $product_id=$_POST['id'];
        $select_wish=$conn->prepare("SELECT *FROM tbl_wishlist where user_id=? AND product_id=? ");
        $select_wish->execute([$user_id,$product_id]);
        if($select_wish->rowCount()>0){
            echo '<script>
                alert("Sản phẩm này đã thêm");
            </script>';
        }else{
        $select_wishlist=$conn->prepare("INSERT INTO tbl_wishlist(product_id,user_id) values(?,?) ");
        $select_wishlist->execute([$product_id,$user_id]);
        $fetch_price=$select_wishlist->fetch(PDO::FETCH_ASSOC);
        echo '<script>
            alert("Sản phẩm đã được thêm vào yêu thích.");
        </script>';
        }
    }else{
    header('location:user_login.php');
    }
}
if(isset($_POST['addcart'])){
    if(isset($_SESSION['user_id'])){
        $product_id=$_POST['id'];
        // echo $product_id;
        $qty=$_POST['qty'];
        $qty=filter_var($qty, FILTER_SANITIZE_STRING);
        
        $price=$_POST['price'];
        $price=filter_var($price, FILTER_SANITIZE_STRING);
        
        $varify_cart=$conn->prepare("SELECT *FROM tbl_cart where user_id=? AND product_id=? ");
        $varify_cart->execute([$user_id,$product_id]);

        $max_cart_items=$conn->prepare("SELECT *FROM tbl_cart WHERE user_id=? ");
        $max_cart_items->execute([$user_id]);
        
        if($varify_cart->rowCount()>0){
            // cộng số lượng sl mới= sl post(=1) + sl trong gio
            $qty_cart_item=$conn->prepare("SELECT quantity FROM tbl_cart where user_id=? AND product_id=? ");
            $qty_cart_item->execute([$user_id,$product_id]);
            foreach ($qty_cart_item as $row) {
                    // echo $row['quantity'];
                    $qty+=$row['quantity'];
            }
            $qty=filter_var($qty, FILTER_SANITIZE_STRING);
            $slkho=$_POST['soluong'];
            
            if($qty>$slkho){
                echo "<script> alert('Số lượng không đủ');
                    </script>";
            }else{
                $update_qty=$conn->prepare(" UPDATE tbl_cart SET quantity=? where product_id=? ");
                $update_qty->execute([$qty,$product_id]);
            }
        }else if($max_cart_items->rowCount()>100){
            echo "<script > alert('giỏ hàng đã đầy');
                    window.location.href='shopping-cart.php';
                    </script>";
        }else{
            $select_price=$conn->prepare("SELECT * FROM tbl_product where id=? LIMIT 1 ");
            $select_price->execute([$product_id]);
            $fetch_price=$select_price->fetch(PDO::FETCH_ASSOC);

            $insert_cart=$conn->prepare("INSERT INTO tbl_cart(price,user_id,quantity,product_id) values(?,?,?,?) ");
            $insert_cart->execute([$price,$user_id,$qty,$product_id]);
        }
    }
    else{
        header('location:user_login.php');
    }
}

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
    <link rel="stylesheet" href="../qlkh/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/style.css" type="text/css">
    <style>
        a:hover{
            color: red;
        }
        .product__item .product__item__text a {
            text-decoration: none;
            color: black;
            visibility: visible;
            opacity: 1;
            display: block;
            position:static ;
        }
        .product__item:hover .product__item__text h6 {
            opacity: 1;
        }
        .product__item:hover .product__item__text a {
            opacity:1;
            visibility: visible;
        }
        .product__item .product__item__text input {
            background-color:red;
            color:white;
            border:none;
            border-radius: 5px;
        }
        .hidden {
            display: none;
        }
        .fa-star {
            color: #dddd10;
            font-size: 14px; /* Điều chỉnh kích thước của sao */
        }
        .fa-star-o{
            color:black;
            font-size: 14px;
        }
        .product__item__text .rating i {
            color: #dddd10;
            margin-right:3px;
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links">
                <a href="#">Sign in</a>
                <a href="#">FAQs</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
            <a href="#"><img src="img/icon/heart.png" alt=""></a>
            <a href="shopping-cart.php"><img src="img/icon/cart.png" alt=""> <span>0</span></a>
            <div class="price">$0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="img/hero/banner.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                                                                                                     
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="img/hero/banner3.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="img/hero/banner4.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                                                                                                     
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
    
    <!-- Banner Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="filter__controls">

                        <li class="active" id="best-sellers-button">Best Sellers</li>
                        
                        <li id="new-arrivals-button">New Arrivals</li>
                        
                    </ul>
                </div>
            </div>
            <div class="row product__filter">
                <?php
                    $select_products = $conn->prepare("SELECT * FROM `tbl_product`ORDER BY id DESC LIMIT 4"); 
                    $select_products->execute();
                    if($select_products->rowCount() > 0){
                    while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                        $price_product=number_format($fetch_product['price']);
                ?>
                <form action="" method="post" class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals product_new hidden">
                    <div>
                        <div class="product__item">
                            <!-- <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg"> -->
                            <div class="product__item__pic set-bg" data-setbg='uploaded_img/<?=$fetch_product["image_1"]?>'>
                                <span class="label">New</span>
                                <ul class="product__hover">
                                    <li><a href="#"><img src="img/icon/search.png" alt=""><span>Xem chi tiết</span></a></li>
                                    <?php
                                    $select_wish_color=$conn->prepare("SELECT *FROM tbl_wishlist where user_id=? and product_id=?");
                                    $select_wish_color->execute([$user_id,$fetch_product['id']]);
                                    if($select_wish_color->rowCount()>0){
                                        echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img style="background: #ff0000;" src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                    }else{
                                        echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <a href=""><h6><?= $fetch_product['name'] ?></h6></a>
                                <input type="submit" value="+ Add To Cart" class="add-cart" name="addcart">
                                <!-- <a href="shopping-cart.php" class="add-cart">+ Add To Cart</a> -->
                                <?php
                                    $product_id_new=$fetch_product['id'];
                                    $total_review=0;
                                    $count=0;
                                    $select_comment = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, time_post, NOW()) AS time_hour, USER.full_name, COMMENT.content,COMMENT.time_post,comment.review, product.name FROM tbl_user AS user, tbl_product AS product, tbl_comment AS comment WHERE comment.user_id= user.id and COMMENT.product_id = product.id AND product.id=$product_id_new;"); 
                                    $select_comment->execute();
                                    while($fetch_comment = $select_comment->fetch(PDO::FETCH_ASSOC)){
                                        $total_review+=$fetch_comment['review'];
                                        $count++;
                                        
                                    }
                                ?>
                                <div class="rating">
                                    <?php
                                        if($count==0){
                                            $count=1;
                                        }
                                    ?>
                                    <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= round($total_review/$count )) {
                                                echo '<i class="fa fa-star"></i>';
                                            } else {
                                                echo '<i class="fa fa-star-o"></i>';
                                            }
                                        }
                                    ?>
                                </div>
                                <h5><?= $price_product ?><sup>đ</sup></h5>
                            </div>
                        </div>
                    </div>
                        <input type='hidden' name='id' value="<?=$fetch_product['id']?>">
                        <input type='hidden' name='name' value="<?=$fetch_product['name']?>">
                        <input type='hidden' name='price' value="<?=$fetch_product['price']?>">
                        <input type='hidden' name='image' value="<?=$fetch_product['image_1']?>">
                        <input type='hidden' name='qty' value="1" >
                        <input type="hidden" name="soluong" value="<?= $fetch_product['quantity']; ?>" >
                </form>
                <?php
                    }
                }else{
                    /*echo '<p class="empty">no products added yet!</p>';*/
                }
                ?>
                <?php
                    $select_products = $conn->prepare("SELECT p.quantity as p_quantity, p.name AS product_name, category.name AS category_name, p.price, p.id AS product_id, p.image_1, SUM(oder_p.quantity) AS total_sanpham
                    FROM tbl_order AS oder
                    JOIN tbl_order_product AS oder_p ON oder.id = oder_p.order_id
                    JOIN tbl_product AS p ON oder_p.product_id = p.id
                    JOIN tbl_category AS category ON category.id = p.category_id
                    WHERE oder.status = 2
                    GROUP BY p.id
                    ORDER BY total_sanpham DESC;"); 
                    $select_products->execute();
                    if($select_products->rowCount() > 0){
                    while($fetch_product_seller = $select_products->fetch(PDO::FETCH_ASSOC)){
                        $price_product=number_format($fetch_product_seller['price']);
                ?>
                <form action="" method="post" class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals product_sellers">
                    <div>
                        <div class="product__item">
                            <!-- <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg"> -->
                            <div class="product__item__pic set-bg" data-setbg='uploaded_img/<?=$fetch_product_seller["image_1"]?>'>
                                <span class="label">New</span>
                                <ul class="product__hover">
                                    <li><a href="#"><img src="img/icon/search.png" alt=""><span>Xem chi tiết</span></a></li>
                                    <?php
                                    $select_wish_color=$conn->prepare("SELECT *FROM tbl_wishlist where user_id=? and product_id=?");
                                    $select_wish_color->execute([$user_id,$fetch_product_seller['product_id']]);
                                    if($select_wish_color->rowCount()>0){
                                        echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img style="background: #ff0000;" src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                    }else{
                                        echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <a href=""><h6><?= $fetch_product_seller['product_name'] ?></h6></a>
                                <input type="submit" value="+ Add To Cart" class="add-cart" name="addcart">
                                <!-- <a href="shopping-cart.php" class="add-cart">+ Add To Cart</a> -->
                                <?php
                                    $product_id_best=$fetch_product_seller['product_id'];
                                    $total_review=0;
                                    $count=0;
                                    $select_comment = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, time_post, NOW()) AS time_hour, USER.full_name, COMMENT.content,COMMENT.time_post,comment.review, product.name FROM tbl_user AS user, tbl_product AS product, tbl_comment AS comment WHERE comment.user_id= user.id and COMMENT.product_id = product.id AND product.id=$product_id_best;"); 
                                    $select_comment->execute();
                                    while($fetch_comment = $select_comment->fetch(PDO::FETCH_ASSOC)){
                                        $total_review+=$fetch_comment['review'];
                                        $count++;
                                        
                                    }
                                ?>
                                <div class="rating">
                                    <?php
                                        if($count==0){
                                            $count=1;
                                        }
                                    ?>
                                    <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= round($total_review/$count )) {
                                                echo '<i class="fa fa-star"></i>';
                                            } else {
                                                echo '<i class="fa fa-star-o"></i>';
                                            }
                                        }
                                    ?>
                                </div>
                                <h5><?= $price_product ?><sup>đ</sup></h5>
                            </div>
                        </div>
                    </div>
                        <input type='hidden' name='id' value="<?=$fetch_product_seller['product_id']?>">
                        <input type='hidden' name='name' value="<?=$fetch_product_seller['product_name']?>">
                        <input type='hidden' name='price' value="<?=$fetch_product_seller['price']?>">
                        <input type='hidden' name='image' value="<?=$fetch_product_seller['image_1']?>">
                        <input type='hidden' name='qty' value="1" >
                        <input type="hidden" name="soluong" value="<?= $fetch_product_seller['p_quantity']; ?>" >
                </form>
                <?php
                    }
                }else{
                    /*echo '<p class="empty">no products added yet!</p>';*/
                }
                ?>
                <!-- chưa lấy từ sql -->
            </div>
        </div>
        <script>
            // Lắng nghe sự kiện click trên nút "Best Sellers"
        document.getElementById("best-sellers-button").addEventListener("click", function() {
            hideForm("product_new");
            showForm("product_sellers");
        });

        // Lắng nghe sự kiện click trên nút "New Arrivals"
        document.getElementById("new-arrivals-button").addEventListener("click", function() {
            hideForm("product_sellers");
            showForm("product_new");
        });

        // Ẩn form
        function hideForm(className) {
            var forms = document.getElementsByClassName(className);
            for (var i = 0; i < forms.length; i++) {
                forms[i].classList.add("hidden");
            }
        }

        // Hiện form
        function showForm(className) {
            var forms = document.getElementsByClassName(className);
            for (var i = 0; i < forms.length; i++) {
                forms[i].classList.remove("hidden");
            }
        }
        </script>
    </section>
    <!-- Product Section End -->

    <!-- Categories Section Begin -->
    
    <!-- Categories Section End -->

    <!-- Instagram Section Begin -->
    <section class="instagram spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="instagram__pic">
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/nuoc-hoa-ysl-00-768x768.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/nuochoa2.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/nuochoa3.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/nuochoa4.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/nuochoa7.jpg"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="img/instagram/nuochoa6.jpg"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="instagram__text">
                        <h2>Instagram</h2>
                        
                        <h3>#Unisex-Perfume</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instagram Section End -->

    <!-- Latest Blog Section Begin -->
    <section class="latest spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Latest News</span>
                        <h2>Perfume New Trends</h2>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php
                    $select_blog = $conn->prepare("SELECT * FROM tbl_blog ORDER BY id DESC LIMIT 3"); 
                    $select_blog->execute();
                    $fetch_blog1 = $select_blog->fetchAll(PDO::FETCH_ASSOC);
                    if($select_blog->rowCount() > 0){
                    foreach($fetch_blog1 as $fetch_blog){
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
        </div>
    </section>
    <!-- Latest Blog Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/black2WS.png" width="245" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="img/payment.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Perfume Store</a></li>
                            <li><a href="#">Trending</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Contact us</h6>
                        <ul>  
                            <style>
                                a {
                                    margin-right: 10px; 
                                }
                            </style>                                                   
                            <a href="https://www.facebook.com/buiquanghuy.huy.71"><i class="fa fa-facebook" style="font-size: 35px;"></i></a>
                            <a href="#"><i class="fa fa-twitter" style="font-size: 35px;"></i></a>
                            <a href="#"><i class="fa fa-youtube" style="font-size: 35px;"></i></a>
                            <a href="https://www.instagram.com/bqhuyfinding.aputa/"><i class="fa fa-instagram" style="font-size: 35px;"></i></a>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>Be the first to know about new arrivals, sales & promos!</p>
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