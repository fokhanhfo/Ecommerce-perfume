<?php
    include 'components/connect.php'; 
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
     }else{
        // $user_id = '';
        header("location: user_login.php");
     }
    
    $order_id=$_GET['order_id'];
    if(isset($_POST['cancel'])){

        $update_order = $conn->prepare("UPDATE tbl_order SET status=? where id=? "); 
        $update_order->execute(['3',$order_id]);
        header("location: order.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    .col-lg-6{
        display: inline-block;
    }
    .btn-huy{
        border:none;
        border-radius:5px;
        outline: none;
        background-color:red;
        color:#fff;
        padding:5px;
    }
</style>
<body>
  
    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <div style="text-align: center;">
                           <a href="order.php" style="color:green; font-size: 25px; text-decoration:none;"> My orders </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
              
    <!-- bắt đầu -->
   <div class="row">
    <div class="col-lg-6" >
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Product</th>
                        <th>Total</th>
                    </tr>
                    
                </thead>
                <tbody >
                <form action="" method="post" class="box">
                    <?php
                        $order_id=$_GET['order_id'];
                        $select_order = $conn->prepare("SELECT *, b.quantity as soluong FROM (tbl_order as a inner join tbl_order_product as b on a.id=b.order_id ) inner join tbl_product as c on b.product_id=c.id where a.id=$order_id"); 
                        $select_order->execute();
                        if($select_order->rowCount() > 0){
                            while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                                $total_all=$fetch_order['total_price'];
                                $totalprice =($fetch_order['soluong'] * $fetch_order['price']);
                                $status=$fetch_order['status'];
                                $date=$fetch_order['booking_date'];

                    ?>
                                <tr>
                                    <td class="col-sm-3">
                                        <div class="product_pic" > 
                                        <a href="shop-details.php?id=<?php echo $fetch_order['product_id']; ?>">
                                            <input type="hidden" name="order_id" value="<?= $fetch_order['order_id']; ?>">
                                            <input type="hidden" name="product_id" value="<?= $fetch_order['product_id']; ?>">
                                            <input type="hidden" name="tus" value="<?= $fetch_order['status'] ?>">
                                            <input type="hidden" name="quantity_new" value="<?=$quantity=$fetch_order['quantity']-$fetch_order['soluong'];?>">
                                                <img src="uploaded_img/<?= $fetch_order['image_1']; ?> " width=100>
                                        </div>
                                        <div class="product_text" >
                                            <h6><?= $fetch_order['name']; ?></h6>
                                            <h5><?= number_format($fetch_order['price']); ?><span>x <?=$fetch_order['soluong']; ?></span></h5></a>
                                        </div>
                                    </td>
                                    <td class="col-sm-3" ><?= number_format($totalprice);?></td>
                                </tr>                     
                                    <?php
                                        }
                                    }?>
                                    <tr>
                                    <td class="col-sm-3"><h5><b>Total All:  </b>
                                        <b><span> <?= number_format($total_all); ?> </span></b></h5>
                                    </td>
                                    <td>
                                        <p class="date" ><b>Ngày đặt hàng: </b> <i class="bi bi-calender-fill"></i><span> <?php echo date('d-m-Y', strtotime($date));?> </span></p>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="col-sm-3">
                                        <b>Trạng thái:</b>
                                        <p class="status" style="color: <?php 
                                            if($status=='1'){echo'green';} 
                                            elseif($status=='3'){echo'red';}
                                            else{echo'orange';} ?> ">
                                            <?php
                                            if($status=="0") {echo 'In processing';}elseif($status=="1") {echo 'Delivering';}
                                            elseif($status=="2") {echo 'Received';} else echo 'Canceled';
                                            ?>
                                        </p>
                                    </td>
                                    <td class="col-sm-3">
                                        <?php
                                        if($status=="0"){
                                            // echo "<a href='order.php?id=".$fetch_order['id']."' onclick='return del()' >Cancel orders</a>";
                                            echo "<button type='submit' name='cancel' onclick='return del()' class='btn-huy'>Cancel orders</button>";
                                        }
                                       ?>
                                    </td>
                                </tr>
                            
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

     <div class="col-lg-6">
        <form action="" method="post" class="box">
            <div>
                <h5 style="margin-top: 30px; text-align:center; "><b>Địa chỉ nhận hàng</b></h5>
            </div>
            <?php
                $select_addr=$conn->prepare("SELECT * FROM tbl_order where user_id=? and id=?");
                $select_addr->execute([$user_id,$order_id]);
                if($select_addr->rowCount() >0){
                    $fetch_addr=$select_addr->fetch(PDO::FETCH_ASSOC)
            ?>
            <div class="detail" style="margin-left: 220px;">
                <h6 style="margin-top: 30px;"><i class="fa-solid fa-user"></i> <?=$fetch_addr['order_name']; ?></h6>
                <h6 style="margin-top: 15px;"><i class="fa-solid fa-location-dot" style="color: #f00f47;"></i> <?=$fetch_addr['address']; ?></h6>
                <h6 style="margin-top: 15px;"><i class="fa-solid fa-phone" style="color: #2cdd08;"></i> <?=$fetch_addr['phone']; ?></h6>
            </div>
            <?php
                }
                ?>
        </form>
    </div>
</div>
            
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