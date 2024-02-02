<?php
    include 'components/connect.php'; 
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
     }else{
        $user_id = '';
        //header('location:user_login.php');
     }
     if(isset($_POST['logout'])){
        session_destroy();
        header("location: user_login.php");
     }
     if(isset($_POST['place_order'])){
        $name=$_POST['name'];
        $name=filter_var($name, FILTER_SANITIZE_STRING);

        $phone=$_POST['phone'];
        $phone=filter_var($phone, FILTER_SANITIZE_STRING);

        $email=$_POST['email'];
        $email=filter_var($email, FILTER_SANITIZE_STRING);

        $note=$_POST['note'];
        $note=filter_var($note, FILTER_SANITIZE_STRING);
        
        $address=$_POST['address'].','.$_POST['city'].','.$_POST['country'];
        $address=filter_var($address, FILTER_SANITIZE_STRING);

        $total_priceAll=$_POST['total_price'];

        // $method=$_POST['method'];
        // $method=filter_var($method, FILTER_SANITIZE_STRING);
        $time=date("Y-m-d");
        // $time=date("Y-m-d G:i:s");

        $status=$_POST['status'];

            $insert_order=$conn->prepare("INSERT INTO tbl_order(address,booking_date,order_name,phone,email,status,note,total_price,user_id) values(?,?,?,?,?,?,?,?,?) ");
            $insert_order->execute([$address,$time,$name,$phone,$email,$status,$note,$total_priceAll,$user_id]);
            header('location:order.php');
            if($insert_order){
                $order_id=$conn->lastInsertId();
                $varify_cart=$conn->prepare("SELECT *FROM tbl_cart where user_id=? ");
                $varify_cart->execute([$user_id]);
                if($varify_cart->rowCount()>0){
                    while($f_cart=$varify_cart->fetch(PDO::FETCH_ASSOC)){
                // echo $order_id;
                $insert_order_detail=$conn->prepare("INSERT INTO tbl_order_product(order_id,product_id,quantity,price) values(?,?,?,?) ");
                $insert_order_detail->execute([$order_id,$f_cart['product_id'],$f_cart['quantity'],$f_cart['price']]);
                $select_product_id=$conn->prepare("SELECT * FROM `tbl_product` WHERE id=?");
                $select_product_id->execute([$f_cart['product_id']]);
                $f_select_product_id=$select_product_id->fetch(PDO::FETCH_ASSOC);
                $quantity=$f_select_product_id['quantity']-$f_cart['quantity'];
                $insert_product_id = $conn->prepare("UPDATE `tbl_product` SET `quantity`=? WHERE id=?");
                $insert_product_id->execute([$quantity, $f_cart['product_id']]);

                    }
                }
            }
            // add vào order và xóa giỏ hàng
            if($insert_order){
                $delete_cart_id=$conn->prepare("DELETE FROM tbl_cart where user_id=?");
                $delete_cart_id->execute([$user_id]);
                header('location:order.php');
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
</head>
<style>
    input:invalid + span::after {
    content: "✖";
    border-color: red;
    }

    input:valid + span::after {
    content: "✓";
    /* padding-left: 3px; */
    }
</style>
<body>
    <?php include 'components/user_header.php'; ?>
    <!-- Page Preloder -->
    <!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Check Out</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.html">Home</a>
                            <a href="./shop.html">Shop</a>
                            <span>Check Out</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="coupon__code" style="color: orange;"><span class="icon_tag_alt"></span><b>Freeship for all orders</b></h5>
                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="checkout__input">
                                <p>Your Name<span>*</span></p>
                                <input type="text" name="name" required maxlength="50">
                            </div>
                            <div>
                                <div class="checkout__input">
                                    <p>Country<span>*</span></p>
                                    <input type="text" name="country" required maxlength="50">
                                </div>
                                <div class="checkout__input">
                                    <p>Town/City<span>*</span></p>
                                    <input type="text" name="city"  required maxlength="50">
                                </div>
                                <div class="checkout__input">
                                    <p>Address<span>*</span></p>
                                    <input type="text" placeholder="Street, District, City" class="checkout__input__add" name="address" required maxlength="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        <input type="text" name="phone" pattern="[0-9]{10}"  required/><span class="validity"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span></span></p>
                                        <input type="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="checkout__input">
                                <p>Order notes</p>
                                <input type="text"
                                placeholder="Notes about your order, e.g. special notes for delivery." name="note">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="checkout__order">
                                <h4 class="order__title" style="text-align:center;">Your order</h4>
                                    <!-- <div class="checkout__order__products">Product <span>Total</span></div> -->
                                    <div class="">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                    <?php
                                        $total_priceAll=0;
                                        $total_product=0;

                                        $select_cart=$conn->prepare("SELECT *FROM tbl_cart where user_id=? ");
                                        $select_cart->execute([$user_id]);

                                        if($select_cart->rowCount()>0){
                                            while($fetch_cart=$select_cart->fetch(PDO::FETCH_ASSOC)){
                                                $select_product=$conn->prepare("SELECT * FROM tbl_product where id=?");
                                                $select_product->execute([$fetch_cart['product_id']]);
                                                    $fetch_product=$select_product->fetch(PDO::FETCH_ASSOC);
                                                    $total_price=($fetch_cart['quantity']*$fetch_product['price']);
                                                    $total_priceAll+=$total_price;
                                                    $total_product+=$fetch_cart['quantity'];
                                        ?>
                                                <!-- <form action="" method="post" class="box"> -->
                                                    <tr>
                                                        <td class="product__cart__item">
                                                            <div class="product__cart__item__pic">
                                                                <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                                                                <input type="hidden" name="total_price" value="<?= $total_priceAll ?>">
                                                                <input type="hidden" name="status" value="0">
                                                                <img src="uploaded_img/<?= $fetch_product['image_1']; ?>" width=100 alt="ảnh nước hoa">
                                                            </div>
                                                            <div class="product__cart__item__text">
                                                                <h6><?= $fetch_product['name']; ?></h6>
                                                                <h5><?= number_format($fetch_product['price']); ?> <span>x<?= $fetch_cart['quantity']; ?> </span></h5>
                                                            </div>
                                                        </td>
                                                        
                                                        <td class="cart__price"><?= number_format(($fetch_cart['quantity']*$fetch_product['price'])); ?> </td>
                                                    </tr>
                                                <!-- </form> -->
                                            <?php
                                            }
                                        } else{
                                           echo ' <p class="empty"> your cart is empty</p>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                <div class="checkout__total__all">
                                    <div>Tổng sản phẩm (<?=$total_product ?> sản phẩm)</div>
                                    <div>
                                        <h5><b>Total 
                                        <span style="margin-left: 200px;"><?= number_format($total_priceAll); ?></span></b></h5>
                                        
                                    </div>
                                </div>
                               
                                <div class="checkout__input">
                                    <p><span>*</span>Thanh toán khi nhận hàng</p>
                                </div>
                                    <button type="submit" class="site-btn" name="place_order">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

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