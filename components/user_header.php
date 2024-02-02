<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                        <?php          
                            $select_profile = $conn->prepare("SELECT REVERSE(SUBSTRING(REVERSE(full_name), 1, POSITION(' ' IN REVERSE(full_name)))) AS tu_cuoi_cung FROM tbl_user WHERE id = ?");
                            $select_profile->execute([$user_id]);
                            if($select_profile->rowCount() > 0){
                            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <div class="header__top__links">
                                <div class="header__top__hover">
                                    <span><?= $fetch_profile["tu_cuoi_cung"]; ?></i></span>
                                    <div class="header__top__user">
                                        <ul>
                                            <li><a style="color: black;"  href="profile_with_data.php">Trang cá nhân</a></li>
                                            <li><a style="color: black;"  href="components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a></li>
                                            <li><a style="color: black;" href="order.php">Đơn mua</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }else{
                        ?>
                            <div class="header__top__links">
                                <a href="http://localhost/qlkh/user_login.php">Sign in</a>
                                <a href="user_register.php">Register</a>
                            </div>
                        <?php
                            }
                            ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo" style="padding: 0;" >
                        <a href="./index.php"><img src="img/WS.png" width="240" style="padding: 0;" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="active"><a href="./index.php">Home</a></li>
                            <li><a href="./shop.php">Shop</a></li>
                            <li><a href="./about.php">About Us</a></li>
                            <li><a href="./blog.php">Blog</a></li>
                            <li><a href="./contact.php">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- có tài khoản -->
                <?php
                    if(isset($_SESSION['user_id'])){
                    $count_cart_items=$conn->prepare("SELECT * FROM tbl_cart where user_id=?");
                    $count_cart_items->execute([$user_id]);
                    $total_cart_items=$count_cart_items->rowCount();?>
                    <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="wisshlist.php"><img src="img/icon/heart.png" alt=""></a>
                        <a href="shopping-cart.php"><img src="img/icon/cart.png" alt=""> 
                        <span>
                            <?= $total_cart_items?>
                        </span></a>
                        
                    </div>
                </div>
                <?php
                // người xem
                    }else{
                ?>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
                        <a href="#"><img src="img/icon/heart.png" alt=""></a>
                        <a href="shopping-cart.php"><img src="img/icon/cart.png" alt=""> <span>0</span></a>
                        <div class="price">$0.00</div>
                    </div>
                </div>
                <?php
                    }
                    ?>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>