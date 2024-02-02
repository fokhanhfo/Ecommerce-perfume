<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
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
    <title>Tin tức</title>

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
    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Blog Details Hero Begin -->
    <?php
        $blog_id=$_GET['blog_id'];
        $select_blog = $conn->prepare("SELECT * FROM tbl_blog where id=$blog_id"); 
        $select_blog->execute();
        $fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC);
        $formatted_time_post = date("d F Y", strtotime($fetch_blog["time_post"]));
    ?>
    <section class="blog-hero spad" style="padding: 50px 0 50px 0 ;">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 text-center">
                    <div class="blog__hero__text">
                        <h2><?= $fetch_blog['title'] ?></h2>
                        <ul>
                            <li><?= $formatted_time_post ?></li>
                        </ul>
                        <p style="margin-top: 18px;"><?= $fetch_blog['content'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad" style="margin-top: 0;">
        <?php
        $select_content = $conn->prepare("SELECT * FROM tbl_img_blog where id_blog=$blog_id"); 
        $select_content->execute();
        ?>
        <div class="container">
            <div class="news-container">
                <?php
                $count_content=0;
                while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                    if($count_content%2==0){
                        $product_id=$fetch_content['product_id'];
                ?>
                <h4 style="margin: 10px 150px;font-weight: bold;"><?= $fetch_content['title_img'] ?></h4>
                <div class="news-item" style="display: flex;margin: 0px 150px;">
                    <div class="news-image" style="width:50%;">
                        <img style="width: 100%;" src="uploaded_img/<?= $fetch_content['image_blog'] ?>" alt="Hình ảnh 1">
                    </div>
                    <div class="news-content" style="width:50%;height:auto;margin: 10px 0 0 25px;">
                        <?php
                            if($product_id != null){
                            $select_type = $conn->prepare("SELECT * FROM tbl_product where id=$product_id"); 
                            $select_type->execute();
                            while($fetch_type = $select_type->fetch(PDO::FETCH_ASSOC)){
                                    $price_product=number_format($fetch_type['price']);
                            ?>
                            <form action="" method="post" style="width: 235px;border-radius: 20px;border: 1px solid;float: right;padding: 10px;margin-left: 8px;">
                                <div>
                                    <div class="product__item" style="display:flex;margin-bottom: 0;">
                                        <!-- <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg"> -->
                                        <img style="width: 100px;height: 100px;border-radius: 20px;" src="uploaded_img/<?= $fetch_type['image_1'] ?>" alt="">
                                        <div class="product__item__text" style="padding-top:0;margin-left: 5px;">
                                            <a href="shop-details.php?id=<?= $fetch_type['id'] ?>"><h6><?= $fetch_type['name'] ?></h6></a>
                                            <input style="width: 100px;font-size: 14px;" type="submit" value="+ Add To Cart" class="add-cart" name="addcart">
                                            <!-- <a href="shopping-cart.php" class="add-cart">+ Add To Cart</a> -->
                                            <?php
                                                $product_id_new=$fetch_type['id'];
                                                $total_review=0;
                                                $count=0;
                                                $select_comment = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, time_post, NOW()) AS time_hour, USER.full_name, COMMENT.content,COMMENT.time_post,comment.review, product.name FROM tbl_user AS user, tbl_product AS product, tbl_comment AS comment WHERE comment.user_id= user.id and COMMENT.product_id = product.id AND product.id=$product_id;"); 
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
                            <p style="text-align: justify;white-space: pre-line;font-size: 18px;"><?= $fetch_content['content_img'] ?></p>
                    </div>
                </div>
                <?php
                    }else{
                        ?>
                        <h4 style="margin: 10px 150px;font-weight: bold;"><?= $fetch_content['title_img'] ?></h4>
                        <div class="news-item" style="display: flex;margin: 0px 150px;">
                            <div class="news-content" style="width:50%;height:auto;text-align:right;margin: 10px 25px 0 0;">
                                <?php
                            $product_id=$fetch_content['product_id'];
                            if($product_id != null){
                            $select_type = $conn->prepare("SELECT * FROM tbl_product where id=$product_id"); 
                            $select_type->execute();
                            while($fetch_type = $select_type->fetch(PDO::FETCH_ASSOC)){
                                    $price_product=number_format($fetch_type['price']);
                            ?>
                            <form action="" method="post" style="width: 235px;border-radius: 20px;border: 1px solid;float: left;padding: 10px;margin-right: 8px;">
                                <div>
                                    <div class="product__item" style="display:flex;margin-bottom: 0;">
                                        <!-- <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg"> -->
                                        <img style="width: 100px;height: 100px;border-radius: 20px;" src="uploaded_img/<?= $fetch_type['image_1'] ?>" alt="">
                                        <div class="product__item__text" style="padding-top:0;margin-left: 5px;">
                                            <a href="shop-details.php?id=<?= $fetch_type['id'] ?>"><h6><?= $fetch_type['name'] ?></h6></a>
                                            <input style="width: 100px;font-size: 14px;" type="submit" value="+ Add To Cart" class="add-cart" name="addcart">
                                            <!-- <a href="shopping-cart.php" class="add-cart">+ Add To Cart</a> -->
                                            <?php
                                                $product_id_new=$fetch_type['id'];
                                                $total_review=0;
                                                $count=0;
                                                $select_comment = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, time_post, NOW()) AS time_hour, USER.full_name, COMMENT.content,COMMENT.time_post,comment.review, product.name FROM tbl_user AS user, tbl_product AS product, tbl_comment AS comment WHERE comment.user_id= user.id and COMMENT.product_id = product.id AND product.id=$product_id;"); 
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
                            <p style="text-align: justify;font-size: 18px;white-space: pre-line;"><?= $fetch_content['content_img'] ?></p>
                            </div>
                            <div class="news-image" style="width:50%;">
                                <img style="width: 100%;" src="uploaded_img/<?= $fetch_content['image_blog'] ?>" alt="Hình ảnh 1">
                            </div>
                        </div>
                        <?php
                    }
                    $count_content+=1;
                }
                ?>
            </div>
            <!-- <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <div class="blog__details__pic">
                        <img src="img/blog/details/blog-details.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog__details__content">
                        <div class="blog__details__share">
                            <span>share</span>
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#" class="twitter"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#" class="youtube"><i class="fa fa-youtube-play"></i></a></li>
                                <li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                        <div class="blog__details__text">
                            <p>Hydroderm is the highly desired anti-aging cream on the block. This serum restricts the
                                occurrence of early aging sings on the skin and keeps the skin younger, tighter and
                                healthier. It reduces the wrinkles and loosening of skin. This cream nourishes the skin
                                and brings back the glow that had lost in the run of hectic years.</p>
                            <p>The most essential ingredient that makes hydroderm so effective is Vyo-Serum, which is a
                                product of natural selected proteins. This concentrate works actively in bringing about
                                the natural youthful glow of the skin. It tightens the skin along with its moisturizing
                                effect on the skin. The other important ingredient, making hydroderm so effective is
                                “marine collagen” which along with Vyo-Serum helps revitalize the skin.</p>
                        </div>
                        <div class="blog__details__quote">
                            <i class="fa fa-quote-left"></i>
                            <p>“When designing an advertisement for a particular product many things should be
                                researched like where it should be displayed.”</p>
                            <h6>_ John Smith _</h6>
                        </div>
                        <div class="blog__details__text">
                            <p>Vyo-Serum along with tightening the skin also reduces the fine lines indicating aging of
                                skin. Problems like dark circles, puffiness, and crow’s feet can be control from the
                                strong effects of this serum.</p>
                            <p>Hydroderm is a multi-functional product that helps in reducing the cellulite and giving
                                the body a toned shape, also helps in cleansing the skin from the root and not letting
                                the pores clog, nevertheless also let’s sweeps out the wrinkles and all signs of aging
                                from the sensitive near the eyes.</p>
                        </div>
                        <div class="blog__details__option">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="blog__details__author">
                                        <div class="blog__details__author__pic">
                                            <img src="img/blog/details/blog-author.jpg" alt="">
                                        </div>
                                        <div class="blog__details__author__text">
                                            <h5>Aiden Blair</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="blog__details__tags">
                                        <a href="#">#Fashion</a>
                                        <a href="#">#Trending</a>
                                        <a href="#">#2020</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="blog__details__btns">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="" class="blog__details__btns__item">
                                        <p><span class="arrow_left"></span> Previous Pod</p>
                                        <h5>It S Classified How To Utilize Free Classified Ad Sites</h5>
                                    </a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="" class="blog__details__btns__item blog__details__btns__item--next">
                                        <p>Next Pod <span class="arrow_right"></span></p>
                                        <h5>Tips For Choosing The Perfect Gloss For Your Lips</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="blog__details__comment">
                            <h4>Leave A Comment</h4>
                            <form action="#">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Name">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Email">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Phone">
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <textarea placeholder="Comment"></textarea>
                                        <button type="submit" class="site-btn">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
    <!-- Blog Details Section End -->

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