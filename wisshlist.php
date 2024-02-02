<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['delete_all'])){
    $prodcut_id=$_POST['id'];
    $delete_wishlist = $conn->prepare("DELETE FROM `tbl_wishlist`");
    $delete_wishlist->execute();
    if($delete_wishlist->rowCount()>0){
        echo '<script>
                    alert("Xóa thành công!");window.location.reload();
                    window.location.href = "wisshlist.php";
                    </script>';
    }
}

if(isset($_POST['delete_one'])){
    $prodcut_id=$_POST['id'];
    $delete_wishlist = $conn->prepare("DELETE FROM `tbl_wishlist` WHERE product_id = ? and user_id=?;");
    $delete_wishlist->execute([$prodcut_id,$user_id]);
    if($delete_wishlist->rowCount()>0){
        echo '<script>
                    alert("Xóa thành công!");window.location.reload();
                    window.location.href = "wisshlist.php";
                    </script>';
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

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <div class="breadcrumb__links">
                            <!-- <a href="./index.php">Home</a> -->
                            <h3 style="color:green; text-align: center; ">YOUR WISHLIST</h3>
                            <!-- chia nửa: 1 bên để thông tin người mua hàng -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
              
    <!-- bắt đầu -->
    <div class="container">
        <div class="row product__filter">
            <?php
                $select_wish=$conn->prepare("SELECT * FROM tbl_wishlist where user_id=?");
                $select_wish->execute([$user_id]);
                if($select_wish->rowCount() > 0){
                while($fetch_wishlist = $select_wish->fetch(PDO::FETCH_ASSOC)){
                    $wishlist_id=$fetch_wishlist['product_id'];
                $select_products = $conn->prepare("SELECT *FROM tbl_product where id=$wishlist_id"); 
                $select_products->execute();
                if($select_products->rowCount() > 0){
                $fetch_product_seller = $select_products->fetch(PDO::FETCH_ASSOC);
                    $price_product=number_format($fetch_product_seller['price']);
            ?>
            <form action="" method="post" class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals product_sellers">
                <div>
                    <div class="product__item" style="margin-bottom:0px;">
                        <!-- <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg"> -->
                        <div class="product__item__pic set-bg" data-setbg='uploaded_img/<?=$fetch_product_seller["image_1"]?>'>
                            <span class="label">New</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/search.png" alt=""><span>Xem chi tiết</span></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <a href=""><h6><?= $fetch_product_seller['name'] ?></h6></a>
                            <input type="submit" value="+ Add To Cart" class="add-cart" name="addcart">
                            <!-- <a href="shopping-cart.php" class="add-cart">+ Add To Cart</a> -->
                            <?php
                                $product_id_best=$fetch_product_seller['id'];
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
                <div class="button-container" style="justify-content: end;">
                    <button name="delete_one" type="submit" style="background-color:red;padding: 0;" class="button" onclick='return confirm("Bạn có chắc chắn muốn xóa?")'>DELETE</button>
                </div>
                    <input type='hidden' name='id' value="<?=$fetch_product_seller['id']?>">
                    <input type='hidden' name='name' value="<?=$fetch_product_seller['name']?>">
                    <input type='hidden' name='price' value="<?=$fetch_product_seller['price']?>">
                    <input type='hidden' name='image' value="<?=$fetch_product_seller['image_1']?>">
                    <input type='hidden' name='qty' value="1" >
                    <input type="hidden" name="soluong" value="<?= $fetch_product_seller['quantity']; ?>" >
            </form>
            <?php
            }else{
                /*echo '<p class="empty">no products added yet!</p>';*/
            }
        }
    }else{
        ?>
        <div style="height: 24vh;">
            <h5>Chưa có sản phẩm yêu thích nào !</h5>
        </div>
        <?php
    }
            ?>
        </div>
        <div class="button-container">
            <form action="" method="post">
            <button class="button" ><a style="color:white;" href="shop.php">Continue Shopping</a></button>
            <?php
                if($select_wish->rowCount() > 0){
            ?>
            <button name="delete_all" type="submit" class="button" onclick='return confirm("Bạn có chắc chắn muốn xóa hết ?")'>Delete All Item</button>
            <?php
                }
            ?>
            </form>
        </div>
    </div>

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