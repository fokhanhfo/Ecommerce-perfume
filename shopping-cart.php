<?php
    include 'components/connect.php'; 
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
     }else{
        $user_id = '';
        header('location:user_login.php');
     }
     if(isset($_POST['logout'])){
        session_destroy();
        header("location: user_login.php");
     }
     // delete item cart
     if(isset($_POST['delete_item'])){
        $cart_id=$_POST['cart_id'];
        $cart_id=filter_var($cart_id, FILTER_SANITIZE_STRING);
        $varify_delete_cart=$conn->prepare("SELECT *FROM tbl_cart where id=? ");
        $varify_delete_cart->execute([$cart_id]);
        if($varify_delete_cart->rowCount()>0){
            $varify_delete_cartid=$conn->prepare("DELETE FROM tbl_cart where id=? ");
            $varify_delete_cartid->execute([$cart_id]); 
            // thông báo thành công
            
        }else {
            echo '<p>Xóa không thành công</p>';
        }

     }
     // empty cart
     if(isset($_POST['empty_cart'])){
        
        $varify_empty_cart=$conn->prepare("SELECT *FROM tbl_cart where user_id=? ");
        $varify_empty_cart->execute([$user_id]);
        if($varify_empty_cart->rowCount()>0){
            $delete_cart=$conn->prepare("DELETE FROM tbl_cart where user_id=? ");
            $delete_cart->execute([$user_id]); 
            // tb xóa thành công
        }else {
            echo '<p>Xóa không thành công</p>';
        }
     }
     // update cart
     if(isset($_POST['update_cart'])){
        $cart_id=$_POST['cart_id'];
        $cart_id=filter_var($cart_id, FILTER_SANITIZE_STRING);
        $qty=$_POST['qty'];
        $qty=filter_var($qty, FILTER_SANITIZE_STRING);

        $slkho=$_POST['soluong'];
        if($qty>$slkho){
            echo "<script> alert('Hiện còn $slkho sản phẩm');
                </script>";
        }else{
        $update_qty=$conn->prepare(" UPDATE tbl_cart SET quantity=? where id=? ");
        $update_qty->execute([$qty,$cart_id]);
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<style>
    .icon_btn{
        border-radius:10px;
        outline: none;
        border:none;
    }
    .btn_continue{
        text-align: center;
        padding: 14px 15px;
        width:180px;
        background: black;
		margin-top: 60px;
		margin-left: 50px;
    }
    .btn_empty{
        text-align: center;
		margin-top: 60px;
    }
    .container-fluid{
        /* cố định ko bị di chuyển */
        position: fixed;
        bottom:0;
    }
    
</style>

<body>

    <?php include 'components/user_header.php'; ?>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu End -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shopping Cart</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead >
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="nice-scroll">
                                <?php
                                    $totalpriceAll=0;
                                    $select_cart = $conn->prepare("SELECT * FROM tbl_cart where user_id=?"); 
                                                $select_cart->execute([$user_id]);
                                                if($select_cart->rowCount() > 0){
                                                    while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                                                        $select_products = $conn->prepare("SELECT * FROM tbl_product where id=?"); 
                                                        $select_products->execute([$fetch_cart['product_id']]);
                                                        if($select_products->rowCount() > 0){
                                                            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                                                            
                                ?>
                                <form action="" method="post" class="box">
                                    <tr>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__pic">
                                                <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                                                <input type="hidden" name="soluong" value="<?= $fetch_product['quantity']; ?>" >

                                                <!-- ấn ảnh ra chi tiết sp -->
                                                <a href="shop-details.php?id=<?php echo $fetch_product['id']; ?> "style="text-decoration:none;"> <img src="uploaded_img/<?= $fetch_product['image_1']; ?> " width=100>
                                            </div>
                                            <div class="product__cart__item__text">
                                                <h6><?= $fetch_product['name']; ?></h6>
                                                <h5><?= number_format($fetch_product['price']) ?></h5></a>
                                                <!-- định dạng số tiền -->
                                            </div>
                                        </td>
                                        <td class="quantity__item">
                                            <div class="quantity">
                                                <div class="flex">
                                                    <input type="number" name="qty" id="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?=$fetch_cart['quantity']; ?>">
                                                </div>
                                            </div>
                                        </td>
                                
                                        <td class="cart__price"><?= number_format($totalprice =($fetch_cart['quantity'] * $fetch_cart['price'] ));?> </td>
                                        <!-- <td class="cart__close" ><i class="fa fa-close" name="delete_item" onclick="return confirm('delete this cart item')"></i></td> -->
                                        <td  >
                                            <button type="submit" class="icon_btn" name="update_cart"><i class="fa-sharp fa-solid fa-pen-to-square" style="color: #288ae6;"></i></button>
                                        </td>
                                        <td  >
                                            <button type="submit" class="icon_btn" name="delete_item" onclick="return confirm('delete this cart item')"><i class="fa-solid fa-trash" style="color: #ed7c12;"></i></button>
                                        </td>
                                    </tr> 
                                </form>  
                                <?php
                                    $totalpriceAll+=$totalprice;
                                            }
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            
               
            </div>
        </div>
        <div class="container-fluid" >
            <div class="box_footer">
            <div class="row" style="background-color: #ffffff;">
            <!-- #f3f2ee; -->
                <div class="col-lg-4" >
                    <div class="btn_continue" >
                        <a href="shop.php" style="text-decoration: none; color: #ffffff;" ><b>Continue Shopping</b></a>
                    </div>
                </div>
                <div class="col-lg-4"  >
                    <div class="btn_empty">
                        <form action="" method="POST">
                            <button type="submit" name="empty_cart" class="primary-btn" onclick="return confirm('bạn có chắc chắn muốn xóa hết ko?')">Empty cart</button>
                        </form>
                        
                    </div>
                </div>
                <div class="col-lg-4" style=" padding: 20px 30px;background-color: #f3f2ee;">
                    <div class="cart_total" >
                        <h5><b>Cart total</b></h5>
                        <ul>
                            <b>Total: <span><?= number_format($totalpriceAll);?></span></b>
                        </ul>
                        <a href="checkout.php" class="primary-btn" style="text-decoration: none;">Proceed to checkout</a>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

    <!-- Footer Section Begin -->
   <!-- footer -->

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