<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
$product_id=$_GET['id'];
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

    $qty=$_POST['qty'];
    $qty=filter_var($qty, FILTER_SANITIZE_STRING);

    $price=$_POST['price'];
    $price=filter_var($price, FILTER_SANITIZE_STRING);
    
    $varify_cart=$conn->prepare("SELECT *FROM tbl_cart where user_id=? AND product_id=? ");
    $varify_cart->execute([$user_id,$product_id]);

    $max_cart_items=$conn->prepare("SELECT *FROM tbl_cart WHERE user_id=? ");
    $max_cart_items->execute([$user_id]);
    
    if($varify_cart->rowCount()>0){
        // cộng số lượng
        $qty_cart_item=$conn->prepare("SELECT quantity FROM tbl_cart where user_id=? AND product_id=? ");
        $qty_cart_item->execute([$user_id,$product_id]);
        foreach ($qty_cart_item as $row) {
                // echo $row['quantity'];
                $qty+=$row['quantity'];
        }
        $qty=filter_var($qty, FILTER_SANITIZE_STRING);
        $slkho=$_POST['soluong'];
        if($qty>$slkho){
            echo "<script> alert('Hiện còn $slkho sản phẩm');
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
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!-- <link rel="stylesheet" href="css/style_them.css" type="text/css"> -->
    <style>
        .product__details__pic__item{
            height: 200px; /* Đặt chiều cao cố định của thẻ cha */
            border: 1px solid #ccc; /* Để thấy rõ kích thước của thẻ cha */
            overflow: hidden; /* Ẩn nội dung ảnh nếu ảnh lớn hơn kích thước cha */
            position: relative;
        }
        .product__details__pic__item::before {
            content: ""; /* Tạo một phần tử giả trước thẻ cha */
            padding-top: 50%; /* Tỷ lệ khung hình (200/400) */
            display: block;
        }
        .product__details__pic__item img {
            position: absolute; /* Đặt ảnh ở vị trí tuyệt đối */
            top: 0;
            left: 0;
            width: 100%; /* Ảnh chiếm toàn bộ kích thước của thẻ cha */
            height: 100%; /* Ảnh chiếm toàn bộ kích thước của thẻ cha */
        }
        .product__details__pic .nav-tabs .nav-item{
            width: 33%;
            height: 122px;
        }
        .product__details__pic .nav-tabs{
            flex-wrap: nowrap;
            width: auto;
            justify-content: space-between;
        }
        .product__details__pic .nav-tabs .nav-item .nav-link .product__thumb__pic{
            height: 95px;
        }
        .product__details__pic .nav-tabs .nav-item .nav-link {
            justify-content: center;
            display: flex;
        }

        .product__details__text{
            text-align: left;
        }
        .product__details__text .rating .rating_review {
            padding: 0 20px;
            border-left: 1px solid;
            border-right: 1px solid;
            margin: 0 20px;
        }
        .product__details__text .rating i {
            margin:0 2px;
        }
        .product__details__pic {
            padding-bottom: 0;
            margin-bottom:0;
        }
        .item {
            display: none; /* Ẩn tất cả các mục ban đầu */
        }
        /* CSS cho nút "Xem thêm" */
        .button-see-more {
            display: inline-block;
            color: red; /* Màu chữ */
            border: none; /* Loại bỏ đường viền */
            border-radius: 5px; /* Góc bo tròn */
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s; /* Hiệu ứng khi di chuột vào */
        }

        /* Hover effect */
        .button-see-more:hover {
            color: black;
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
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Shop</a>
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <?php
                                $product_id=$_GET['id'];
                                $select_products = $conn->prepare("SELECT * FROM tbl_product WHERE id=$product_id"); 
                                $select_products->execute();
                                if($select_products->rowCount() > 0){
                                $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)
                            ?>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item" style="height: 400px;position: relative;">
                                    <img src="uploaded_img/<?= $fetch_product['image_1']; ?>" alt="">
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__pic__item" style="height: 400px;position: relative;">
                                    <img src="uploaded_img/<?= $fetch_product['image_2']; ?>" alt="">
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__pic__item" style="height: 400px;position: relative;">
                                    <img src="uploaded_img/<?= $fetch_product['image_3']; ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <div class="product__thumb__pic set-bg" data-setbg="uploaded_img/<?= $fetch_product['image_1']; ?>">
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">
                                    <div class="product__thumb__pic set-bg" data-setbg="uploaded_img/<?= $fetch_product['image_2']; ?>">
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">
                                    <div class="product__thumb__pic set-bg" data-setbg="uploaded_img/<?= $fetch_product['image_3']; ?>">
                                    </div>
                                </a>
                            </li>
                            <?php
                            }else{
                                echo '<p class="empty">no image!</p>';
                            }
                            ?>
                        </ul>
                    </div>
            <!-- ----- -->
            <form action="" method="POST" style="width: 55%;">
                    <div class="">
                        <div class="product__details__text">
                            <h4><?= $fetch_product['name']; ?></h4>
                            <?php
                                $dem=0;
                                $total_review=0;
                                $count=0;
                                $select_comment = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, time_post, NOW()) AS time_hour, USER.full_name, COMMENT.content,COMMENT.time_post,comment.review, product.name FROM tbl_user AS user, tbl_product AS product, tbl_comment AS comment WHERE comment.user_id= user.id and COMMENT.product_id = product.id AND product.id=$product_id;"); 
                                $select_comment->execute();
                                while($fetch_comment = $select_comment->fetch(PDO::FETCH_ASSOC)){
                                    $total_review+=$fetch_comment['review'];
                                    $count++;
                                    $dem++;
                                    
                                }
                            ?>
                            <div class="rating">
                                <?php
                                    if($count==0){
                                        $count=1;
                                        ?> <span><?= round($total_review/$count )?> </span> <?php
                                    }else{
                                        ?> <span><?= round($total_review/$count )?> </span> <?php
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
                                <span class="rating_review"><?= $dem ?> Đánh giá</span>
                                <?php
                                     $select_total_order_p = $conn->prepare('SELECT sex , p.quantity as p_quantity, p.name AS product_name, category.name AS category_name, p.price, p.id AS product_id, p.image_1, SUM(oder_p.quantity) AS total_sanpham
                                     FROM tbl_order AS oder
                                     JOIN tbl_order_product AS oder_p ON oder.id = oder_p.order_id
                                     JOIN tbl_product AS p ON oder_p.product_id = p.id
                                     JOIN tbl_category AS category ON category.id = p.category_id
                                     WHERE oder.status = 2 and p.id=?
                                     GROUP BY p.id;');
                                     $select_total_order_p->execute([$fetch_product['id']]);
                                     $fetch_total_order_p = $select_total_order_p->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <?php
                                if($select_total_order_p->rowCount() == 0){
                                    ?> <span>0 Đã bán</span> <?php
                                }else{
                                    ?> <span><?= $fetch_total_order_p['total_sanpham'] ?> Đã bán</span> <?php
                                }
                                $select_category = $conn->prepare("SELECT * FROM tbl_category WHERE id=?"); 
                                $select_category->execute([$fetch_product['category_id']]);
                                $fetch_category = $select_category->fetch(PDO::FETCH_ASSOC);
                                ?>
                                
                            </div>
                            <h3><?= number_format($fetch_product['price'], 0, ',', '.'); ?><sup>đ</sup></h3>
                            <h5 style="margin-bottom: 10px;display: inline-block;border-right: 1px solid;padding-right: 24px;">Hãng sản phẩm : <?=$fetch_category['name'] ?></h5>
                            <?php
                                $sex = '';
                                if ($fetch_product['sex'] == 1) {
                                    $sex = "Nam";
                                } elseif ($fetch_product['sex'] == 2) {
                                    $sex = "Nữ";
                                } else {
                                    $sex = "Unisex";
                                }
                            ?>
                            <h5 style="margin-bottom: 10px;display: inline-block;padding-left: 24px;">Giới tính : <?= $sex ?> </h5>
                            <p><?= $fetch_product['details']  ?></p>
                            <div class="product__details__cart__option">
                                <div class="quantity">
                                    <div class="pro-qty" style="border-radius: 20px;" >
                                        <input type="text"  name="qty" value="1" style="border-radius: 20px;" >
                                    </div>
                                </div>
                                <span><?= $fetch_product['quantity']  ?> sản phẩm có sẵn</span>
                                <br>
                                <!-- <a href="#" class="primary-btn" >add to cart</a> -->
                                <input type="submit" name="addcart" class="primary-btn" value="Add to cart">
                            </div>
                            <div class="product__details__btns__option">
                                <button style="border: none;font-family: math;" name="wishlist"><i class="fa fa-heart"></i>ADD TO WISHLIST</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <input type="hidden" name="id" value="<?= $fetch_product['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_product['image_1']; ?>">
                    <input type="hidden" name="soluong" value="<?= $fetch_product['quantity']; ?>">            
            </form>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <!-- comment -->
    <?php include 'components/user_comment.php'; ?>
    <!-- end-comment -->

    <!-- Related Section Begin -->
    <section class="related spad" style="padding-top:0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="related-title">Related Product</h3>
                </div>
            </div>
            <div class="row">
                <?php
                $select_type = $conn->prepare("SELECT * FROM tbl_product"); 
                $select_type->execute();
                while($fetch_type = $select_type->fetch(PDO::FETCH_ASSOC)){
                    if($fetch_type['category_id'] == $fetch_product['category_id']&& $fetch_type['name'] != $fetch_product['name']){
                        $price_product=number_format($fetch_type['price']);
                ?>
                <form action="" method="post" class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6">
                    <div>
                        <div class="product__item">
                            <!-- <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg"> -->
                            <div class="product__item__pic set-bg" data-setbg='uploaded_img/<?=$fetch_type["image_1"]?>'>
                                <ul class="product__hover">
                                    <li><a href="#"><img src="img/icon/search.png" alt=""><span>Xem chi tiết</span></a></li>
                                    <?php
                                    $select_wish_color=$conn->prepare("SELECT *FROM tbl_wishlist where user_id=? and product_id=?");
                                    $select_wish_color->execute([$user_id,$fetch_type['id']]);
                                    if($select_wish_color->rowCount()>0){
                                        echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img style="background: #ff0000;" src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                    }else{
                                        echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <a href="shop-details.php?id=<?= $fetch_type['id'] ?>"><h6><?= $fetch_type['name'] ?></h6></a>
                                <input type="submit" value="+ Add To Cart" class="add-cart" name="addcart">
                                <!-- <a href="shopping-cart.php" class="add-cart">+ Add To Cart</a> -->
                                <?php
                                    $product_id_new=$fetch_type['id'];
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
                        <input type='hidden' name='id' value="<?=$fetch_type['id']?>">
                        <input type='hidden' name='name' value="<?=$fetch_type['name']?>">
                        <input type='hidden' name='price' value="<?=$fetch_type['price']?>">
                        <input type='hidden' name='image' value="<?=$fetch_type['image_1']?>">
                        <input type='hidden' name='qty' value="1" >
                        <input type="hidden" name="soluong" value="<?= $fetch_type['quantity']; ?>" >
                </form>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Related Section End -->

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