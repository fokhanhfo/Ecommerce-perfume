<?php
    include 'components/connect.php'; 
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
     }else{
        // $user_id = '';
        header("location: user_login.php");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


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
    /* .disabled-link {
  pointer-events: none; 
        dung cho <a></a>
    }*/
    .product_text{
        display: inline-block;
    }
    .product_pic{
        display: inline-block;
    }
    .trangthai{
        display: inline-block;
    }
    .status{
        display: inline-block;
    }
</style>
<body>
    <!-- Page Preloder -->
    <!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <div class="breadcrumb__links">
                            <!-- <a href="./index.php">Home</a> -->
                            <h3 style="color:green; text-align: center; ";>My orders</h3>
                            <!-- chia nửa: 1 bên để thông tin người mua hàng -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
              
    <!-- bắt đầu -->
    <div class="box" >
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    
                </thead>
                <tbody >
                <form action="" method="post" class="box">
                    <?php
                        $select_order = $conn->prepare("SELECT * FROM tbl_order where user_id=? ORDER BY id DESC"); 
                            $select_order->execute([$user_id]);
                            if($select_order->rowCount() > 0){
                                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){ ?>
                            
                            <?php
                                    $select_order_products = $conn->prepare("SELECT * FROM tbl_order_product where order_id=? "); 
                                    $select_order_products->execute([$fetch_order['id']]);
                                    if($select_order_products->rowCount() > 0){
                                        while($fetch_order_product = $select_order_products->fetch(PDO::FETCH_ASSOC)){

                                            $totalprice =($fetch_order_product['quantity'] * $fetch_order_product['price']);
                                            $select_products = $conn->prepare("SELECT * FROM tbl_product where id=?"); 
                                            $select_products->execute([$fetch_order_product['product_id']]);
                                            if($select_products->rowCount() > 0){
                                                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                                                    
                                                    
                            ?>
                                    <td class="col-sm-6">
                                        <div class="product_pic" > 
                                        <a href="shop-details.php?id=<?php echo $fetch_product['id']; ?>">
                                                <img src="uploaded_img/<?= $fetch_product['image_1']; ?> " width=100>
                                        </div>
                                        <div class="product_text" >
                                            <h6><?= $fetch_product['name']; ?></h6>
                                            <h5><?= number_format($fetch_product['price']); ?></h5></a>
                                        </div>
                                    </td>
                                    <td class="col-sm-3">
                                        <div class="quantity">
                                            <div class="flex">
                                                <h5 style="justify-content: center";><?=$fetch_order_product['quantity']; ?> </h5></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-sm-3" ><?= number_format($totalprice);?></td>
                                </tr>                     
                            <?php
                                                }
                                                }
                                            }
                                        }?>  
                                        <!-- order_product -->
                                <tr>
                                    <td>
                                        <div >
                                            <h5><b>Mã đơn hàng: </b><span><?=$fetch_order['id'] ?> </span></h5>
                                        </div>
                                        <div >
                                            <h5><b>Total All: <span><?= number_format($fetch_order['total_price']); ?> </span></b></h5>
                                        </div>
                                        
                                    </td>
                                    
                                    <td>
                                        <div>
                                            <b class="trangthai">Trạng thái:</b></div>
                                            <p class="status" style="color: <?php 
                                                if($fetch_order['status']=='1'){echo'green';} 
                                                elseif($fetch_order['status']=='3'){echo'red';}
                                                else{echo'orange';} ?> ">
                                                <?php
                                                if($fetch_order['status']=="0") {echo 'In processing';}elseif($fetch_order['status']=="1") {echo 'Delivering';}
                                                elseif($fetch_order['status']=="2") {echo 'Received';} else echo 'Canceled';
                                                ?>
                                            </p>
                                            
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-right:10px;">
                                            <a href="order-details.php?order_id=<?=$fetch_order['id']?>" style="">Chi tiết</a>
                                        </div>
                                        
                                    </td>
                                </tr> 
                                    <?php
                                            }
                                        }?>
                            
                            </tbody>
                        </table> 
                </form> 

                </div>
            </div>
            <script>
    function del(){
        return confirm("Bạn có muốn hủy đơn hàng không");
    }
</script>
    <!-- Shop Section End -->

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