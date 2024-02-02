<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
$select_user = $conn->prepare("SELECT * FROM `tbl_user` WHERE id = ?");
$select_user->execute([$user_id,]);


if($select_user->rowCount() > 0){
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
    }
    if(isset($_POST['insert_danhgia'])){
        $product_id=$_POST['oder_product_id'];
        $content_comment=$_POST['content-comment'];
        $review_comment=$_POST['rating'];
        $sql = "INSERT INTO tbl_comment (content,product_id ,user_id, time_post,review) VALUES (?, ?, ?, null,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$content_comment,$product_id, $user_id,$review_comment]);
        if($stmt){
            echo '<script>alert("Đánh giá thành công!");window.location.href ="profile_with_data.php";</script>';
        }
    }
if(isset($_POST['update_danhgia'])){
    $oder_comment_id=$_POST['oder_comment_id'];
    $product_id=$_POST['oder_product_id'];
    $content_comment=$_POST['content-comment'];
    $review_comment=$_POST['rating'];
    $sql = "update tbl_comment set content=? , product_id=?, user_id=?,time_post=null, review=? where id=?;";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$content_comment,$product_id, $user_id,$review_comment,$oder_comment_id]);
    if($stmt){
        echo '<script>alert("Đánh giá thành công!");window.location.href ="profile_with_data.php";</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">


<title>profile with data and skills - Bootdey.com</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../qlkh/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/style.css" type="text/css">
<style type="text/css">
    	body{
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;    
}
.main-body {
    padding: 15px;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}


.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}
        .product-container {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            width: 100%;
            margin-bottom: 10px;
            background-color: #f9f7f7;
        }

        /* CSS cho mỗi sản phẩm */
        .product {
            display: flex;
            justify-content: space-between; /* Canh đều giữa các thành phần bên trong */
            align-items: center;
            margin: 0px 10px; /* Khoảng cách giữa các sản phẩm */
            border-bottom: 1px solid #ccc;
            padding-bottom: 0px;
        }

        .product-info {
            flex-grow: 1; /* Để thông tin sản phẩm tự căn giữa */
            padding: 10px; /* Tạo khoảng cách xung quanh thông tin sản phẩm */
        }

        /* CSS cho khối ảnh sản phẩm */
        .product-image {
            width: 80px;
            height: 80px;
            margin-right: 10px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
        }

        /* CSS cho tiêu đề sản phẩm */
        .product-title {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }

        /* CSS cho phân loại đơn hàng */
        .product-category {
            font-size: 14px;
            color: #888;
            margin: 5px 0;
        }

        .product-quantity{
            margin: 5px 0;
        }

        /* CSS cho giá tiền */
        .product-price {
            font-size: 16px;
            color: #e74c3c; /* Màu đỏ */
        }

        /* CSS cho khối tổng tiền */
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold; /* Làm nổi bật tổng tiền */
        }

        /* CSS cho khối nút */
        .buttons {
            text-align: right;
            margin-top: 10px;
            margin-bottom: 10px;
            display:flex;
            justify-content: flex-end;
        }

        .button-product{
            display: inline-block;
            padding: 0px 10px;
            background-color: white; /* Màu nền */
            color: black; /* Màu chữ */
            border: none; /* Loại bỏ đường viền */
            border-radius: 5px; /* Góc bo tròn */
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            border: 1px solid black;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .button-product:hover {
            background-color: #ededed;
        }

        /* CSS cho nút */
        .button {
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #3498db; /* Màu xanh */
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Hiệu ứng khi hover */
        }
        .button:hover {
            background-color: #2980b9; /* Màu xanh nhạt khi hover */
        }
        .total-container {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
        }
        .menu {
            width: 100%;
            background-color: #333;
            overflow: hidden;
        }

        .menu ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .menu ul li {
            float: left;

        }

        .menu ul li a {
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .menu ul li a:hover {
            background-color: #555;
        }

        .container-review{
            background-color: white; /* Màu nền của khối div chứa nội dung */
            padding: 20px; /* Padding cho nội dung bên trong */
            border-radius: 10px; /* Bo tròn góc */
            position: relative; /* Đặt vị trí là tương đối để xác định thứ tự hiển thị */
            z-index: 1000; /* Đảm bảo nằm phía trên cùng của overlay */
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Màu nền tối với độ mờ 50% (sử dụng rgba) */
            z-index: 999; /* Đảm bảo nằm phía trên */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: auto;
        }

        /* ngôi sao */
        .rating-container {
            display: inline-block;
            font-size: 0; /* Để loại bỏ khoảng trống giữa các ngôi sao */
        }

        .rating-container input[type="radio"] {
            display: none;
        }

        .rating-container label {
            font-size: 30px; /* Kích thước ngôi sao */
            color: #ccc;
            cursor: pointer;
            user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
        }
        .star.checked {
            color: #fb2e00;
        }
        .select_menu{
            background-color: transparent;
            border: none;
            color:white;
            padding: 0;
            margin: 0 15px;
        }
        #htmlContainer1{
            width: 100%;
        }
        #htmlContainer{
            width: 100%;
        }

    </style>
</head>
<body>
    <?php include 'components/user_header.php'; ?>
<div class="container">
<div class="main-body">



<div class="row gutters-sm">
<div class="col-md-4 mb-3">
<div class="card">
<div class="card-body">
<div class="d-flex flex-column align-items-center text-center">
<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="" class="rounded-circle" width="150">
<div class="mt-3">
<p style="font-size: 20px;">
    <?php          
        $select_profile = $conn->prepare("SELECT REVERSE(SUBSTRING(REVERSE(full_name), 1, POSITION(' ' IN REVERSE(full_name)))) AS tu_cuoi_cung FROM tbl_user WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);}
    ?>
    <?= $fetch_profile["tu_cuoi_cung"]; ?>
</p>
<a class="btn btn-primary" href="change_user_pass.php">Change pass</a>
</div>
</div>
</div>
</div>

</div>
<div class="col-md-8">
<div class="card mb-3">
<div class="card-body">
<div class="row">
<div class="col-sm-3">
<h6 class="mb-0">Full Name</h6>
</div>
<div class="col-sm-9 text-secondary">
<input type="text" id="nameInput" name="full_name" readonly value="<?php echo $row["full_name"]; ?>" style=" display: block; width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
">
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
<h6 class="mb-0">Email</h6>
</div>
<div class="col-sm-9 text-secondary">
<input type="text" id="emailInput" name="email" readonly value="<?php echo $row["email"]; ?>" style=" display: block; width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
"> 
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
<h6 class="mb-0">Phone</h6>
</div>
<div class="col-sm-9 text-secondary">
<input type="text" id="phoneInput" name="phone_number" value="<?php echo $row["phone_number"]; ?>" style=" display: block; width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
</div>
</div>
<br>


<div class="row">
<div class="col-sm-12">
<a class="btn btn-info "  href="profile_edit_data.php">Edit</a>
</div>
</div>
</div>
</div>
<div class="row gutters-sm">
<div class="menu" style="height: 50px;">
        <ul style="align-items: center;display: flex;height: 100%;">
            <li><input class="select_menu" type="button" value="Những sản phẩm đã mua" id="updateButton0"></li>
            <li><input class="select_menu" type="button" value="Chờ xử lí" id="updateButton1"></li>
        </ul>
    </div>
    <div id="htmlContainer" style="display: none;">
        <?php
                $select_oder = $conn->prepare("SELECT * FROM tbl_order where status = 0 and user_id=$user_id"); 
                $select_oder->execute();
                if($select_oder->rowCount() > 0){
                    while($fetch_oder = $select_oder->fetch(PDO::FETCH_ASSOC)){
                        $total_order=0;

            ?>
            <div class="product-container">
                <!-- Sản phẩm 1 -->
                <?php
                        $order_id=$fetch_oder['id'];
                        $select_oder_product = $conn->prepare("SELECT pro.name as pro_name,pro.image_1 as pro_img,
                        cte.name as cte_name,orderp.quantity as op_quantity,pro.price as pro_price
                        FROM tbl_order AS oder
                        JOIN tbl_order_product AS orderp ON oder.id = orderp.order_id
                        JOIN tbl_product AS pro ON pro.id = orderp.product_id
                        JOIN tbl_category AS cte ON pro.category_id = cte.id
                        WHERE status = 0 and orderp.order_id=$order_id and oder.user_id=$user_id;"); 
                        $select_oder_product->execute();
                        while($fetch_oder_product = $select_oder_product->fetch(PDO::FETCH_ASSOC)){
                            $total_product=0;
                            $total_product+=$fetch_oder_product['pro_price'];
                            $total_product*=$fetch_oder_product['op_quantity'];
                            $total_order+=$total_product;
                ?>
                <div class="product">
                    <!-- Ảnh sản phẩm 1 -->
                    <div class="product-image">
                        <img src="uploaded_img/<?= $fetch_oder_product['pro_img'] ?>" alt="Sản phẩm 1">
                    </div>
                    <!-- Thông tin sản phẩm 1 -->
                    <div class="product-info">
                        <h2 class="product-title"><?= $fetch_oder_product['pro_name'] ?></h2>
                        <p class="product-category">Phân loại đơn hàng: <?= $fetch_oder_product['cte_name'] ?></p>
                        <p class="product-quantity">x<?= $fetch_oder_product['op_quantity'] ?></p>
                    </div>
                    <!-- Số tiền sản phẩm 1 -->
                    <div class="product-price">
                        <p><?= $fetch_oder_product['pro_price'] ?></p>
                    </div>
                </div>

                <!-- Khối tổng tiền -->
                <?php
                        }
                ?>
                <!-- Khối nút -->
                <div class="total-container">
                    <div class="total">
                        <?php
                        
                        ?>
                        Tổng tiền: <?= $total_order ?><!-- Thay X.XX bằng tổng tiền thực tế -->
                    </div>
                </div>
                <div class="buttons">
                <button class="button"><a style="color: white;" href="order-details.php?order_id=<?= $fetch_oder['id'] ?>">Chi tiết</a></button>
                </div>
            </div>
            <?php
                    }
                }else{
                    echo '<h4 style="width: 50%;margin:10px;float: left;">Không có đơn hàng nào</h4>';
                }
            ?>
    </div> 

    <!-- sản phẩm mua rồi -->

    <div id="htmlContainer1" style="display: block;">
        <?php
            $select_oder_product = $conn->prepare("SELECT DISTINCT p.name AS product_name, category.name AS category_name, p.price, p.id AS product_id, p.image_1
            FROM tbl_order AS oder
            JOIN tbl_order_product AS oder_p ON oder.id = oder_p.order_id
            JOIN tbl_product AS p ON oder_p.product_id = p.id
            JOIN tbl_category AS category ON category.id = p.category_id
            WHERE oder.status = 2 AND oder.user_id = $user_id;"); 
            $select_oder_product->execute();
            $fetch_oder_pro = $select_oder_product->fetchAll(PDO::FETCH_ASSOC);
            foreach($fetch_oder_pro as $fetch_oder_product){
                $select_oder_comment = $conn->prepare("SELECT p.name as product_name,p.id as product_id, c.name as category_name, comment.content, comment.id, comment.review
                FROM tbl_comment AS comment
                INNER JOIN tbl_product AS p ON comment.product_id = p.id
                INNER JOIN tbl_category AS c ON p.category_id = c.id
                WHERE comment.product_id = ? AND comment.user_id = ?;"); 
                $select_oder_comment->execute([$fetch_oder_product['product_id'],$user_id]);
                if($select_oder_comment->rowCount() == 0){
            ?>
                <div>
                    <div class="product-container">
                        <div class="product">
                            <!-- Ảnh sản phẩm 1 -->
                            <div class="product-image">
                                <img src="uploaded_img/<?= $fetch_oder_product['image_1'] ?>" alt="Sản phẩm 1">
                            </div>
                            <!-- Thông tin sản phẩm 1 -->
                            <div class="product-info">
                                <h2 class="product-title" id="productName"><?= $fetch_oder_product['product_name'] ?></h2>
                                <p class="product-category">Phân loại đơn hàng: <?= $fetch_oder_product['category_name'] ?></p>
                                <p class="product-quantity">x1</p>
                            </div>
                            <!-- Số tiền sản phẩm 1 -->
                            <div class="product-price">
                                <p style="color: red;margin-top: -30px;border-bottom: 1px solid;">HOÀN THÀNH</p>
                                <p style="float: right;"><?= $fetch_oder_product['price']  ?></p>
                            </div>
                        </div>

                        <!-- Khối tổng tiền -->
                        
                        <div class="buttons">
                            <form action="" method="post">
                                <input type="hidden" name="select_id_product" value="<?=$fetch_oder_product['product_id']?>">
                                <button style="color: white;background-color: #3498db;border:#3498db;" class="button-product" name="slectdanhgia" id="updateButton2">Đánh giá</button>
                            </form>
                            <button style="color:black;margin:0 15px;" class="button-product"><a style="color:black;" href="shop-details.php?id=<?=$fetch_oder_product['product_id']?>">Mua lại</a></button>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
            <?php
            foreach($fetch_oder_pro as $fetch_oder_product){
                $select_oder_comment = $conn->prepare("SELECT p.name as product_name,p.id as product_id, c.name as category_name, comment.content, comment.id, comment.review
                FROM tbl_comment AS comment
                INNER JOIN tbl_product AS p ON comment.product_id = p.id
                INNER JOIN tbl_category AS c ON p.category_id = c.id
                WHERE comment.product_id = ? AND comment.user_id = ?;"); 
                $select_oder_comment->execute([$fetch_oder_product['product_id'],$user_id]);
                if($select_oder_comment->rowCount() > 0){
            ?>
                <div>
                    <div class="product-container">
                        <div class="product">
                            <!-- Ảnh sản phẩm 1 -->
                            <div class="product-image">
                                <img src="uploaded_img/<?= $fetch_oder_product['image_1'] ?>" alt="Sản phẩm 1">
                            </div>
                            <!-- Thông tin sản phẩm 1 -->
                            <div class="product-info">
                                <h2 class="product-title" id="productName"><?= $fetch_oder_product['product_name'] ?></h2>
                                <p class="product-category">Phân loại đơn hàng: <?= $fetch_oder_product['category_name'] ?></p>
                                <p class="product-quantity">x1</p>
                            </div>
                            <!-- Số tiền sản phẩm 1 -->
                            <div class="product-price">
                                <p style="color: red;margin-top: -30px;border-bottom: 1px solid;">ĐÃ ĐÁNH GIÁ</p>
                                <p style="float: right;"><?= $fetch_oder_product['price']  ?></p>
                            </div>
                        </div>

                        <!-- Khối tổng tiền -->
                        
                        <div class="buttons">
                            <button style="color: white;background-color: #3498db;border:#3498db;" class="button-product"><a style="color:white;" href="shop-details.php?id=<?=$fetch_oder_product['product_id']?>">Mua lại</a></button>
                            <form action="" method="post" style="margin:0 15px;">
                                <input type="hidden" name="select_id_product" value="<?=$fetch_oder_product['product_id']?>">
                                <button class="button-product" name="slectdanhgia" id="updateButton2">Xem lại dánh giá</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
            

    </div>
    <?php
        if (isset($_POST['slectdanhgia'])) {
            $product_id = $_POST['select_id_product'];
            $select_oder_comment = $conn->prepare("SELECT p.name as product_name, c.name as category_name, comment.content, comment.id, comment.review
            FROM tbl_comment AS comment
            INNER JOIN tbl_product AS p ON comment.product_id = p.id
            INNER JOIN tbl_category AS c ON p.category_id = c.id
            WHERE comment.product_id = ? AND comment.user_id = ?;"); 
            $select_oder_comment->execute([$product_id,$user_id]);
            if($select_oder_comment->rowCount() > 0){
                $fetch_oder_comment = $select_oder_comment->fetch(PDO::FETCH_ASSOC)
            ?>
                <div class="overlay" id="htmlContainer2">
                    <div class="container-review" style="width: 730px;margin-top: -200px;">
                        <div class="content">
                            <form action="" method="post">
                                <div class="product-container" style="width: 100%;">
                                    <div style="display: flex;align-items: center;">
                                        <div id="productInfoImg" style="width:56px;height:56px;">
                                            <img style="width: 100%;height: 100%;" class="product-image" src="uploaded_img/product-1.jpg" alt="ảnh sản phẩm">
                                        </div>
                                        <div id="productInfo">
                                            <h4 style="margin: 3px 0 0px 10px;"><?= $fetch_oder_comment['product_name'] ?></h4>
                                            <p style="margin: 5px 0 0 10px;">Phân loại sản phẩm : <?= $fetch_oder_comment['category_name'] ?></p>
                                        </div>
                                    </div>
                                    
                                    <label for="">Chất lượng sản phẩm</label>
                                    <?php 
                                        
                                    ?>
                                    <div class="rating-container">
                                        <?php
                                            for($i=1;$i<=5;$i++){
                                                if($i==$fetch_oder_comment['review']){
                                                    ?>
                                                    <label class="star" for="star<?= $i ?>">★</label>
                                                    <input required type="radio" checked name="rating" id="star<?= $i ?>" value="<?= $i ?>">
                                                    <?php
                                                }else{
                                                    ?>
                                                    <input required  type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" />
                                                    <label class="star" for="star<?= $i ?>">★</label>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>

                                    <div class="comment-box" id="contentValue">
                                        <textarea style="height: 64px;width: 100%;resize: none;overflow: hidden;" id="autoresize" rows="4" cols="50" name="content-comment" placeholder="Nhận xét về sản phẩm..."><?= $fetch_oder_comment['content'] ?></textarea>
                                    </div>
                                    <input type="hidden" name="oder_comment_id" value="<?= $fetch_oder_comment['id'] ?>">
                                    <input type="hidden" name="oder_product_id" value="<?= $product_id ?>">
                                    <input type="hidden" name="oder_product_user" value="<?= $user_id ?>">
                                    <div style="margin-bottom: 6px;">
                                        <button style="background-color: #3498db;color:white;border:#3498db;"  class="back-button button-product" id="updateButton3"><a style="color: white;" href="profile_with_data.php">Trở lại</a></button>
                                        <button style="background-color: #3498db;color:white;border:#3498db;"  class="review-button button-product" name="update_danhgia" onclick="return confirm('Bạn có muốn đánh giá lại không?');">Đánh giá</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }else{
                $select_oder_addcomment = $conn->prepare("SELECT p.name as product_name , c.name as category_name from tbl_product AS p ,tbl_category as c WHERE p.category_id=c.id and p.id=$product_id;"); 
                $select_oder_addcomment->execute();
                $fetch_oder_addcomment = $select_oder_addcomment->fetch(PDO::FETCH_ASSOC)
                ?>
                <div class="overlay" id="htmlContainer2">
                    <div class="container-review" style="width: 730px;margin-top: -200px;">
                        <div class="content">
                            <form action="" method="post">
                                <div class="product-container" style="width: 100%;">
                                    <div style="display: flex;align-items: center;">
                                        <div id="productInfoImg" style="width:56px;height:56px;">
                                            <img style="width: 100%;height: 100%;" class="product-image" src="uploaded_img/product-1.jpg" alt="ảnh sản phẩm">
                                        </div>
                                        <div id="productInfo">
                                            <h4 style="margin: 3px 0 0px 10px;">Tên sản phẩm : <?= $fetch_oder_addcomment['product_name'] ?></h4>
                                            <p style="margin: 5px 0 0 10px;">Phân loại sản phẩm : <?= $fetch_oder_addcomment['category_name'] ?></p>
                                        </div>
                                    </div>
                                    
                                    <label for="">Chất lượng sản phẩm</label>
                                    <div class="rating-container">
                                        <input required type="radio" id="star1" name="rating" value="1" />
                                        <label class="star" for="star1">★</label>
                                        <input required type="radio" id="star2" name="rating" value="2" />
                                        <label class="star" for="star2">★</label>
                                        <input required type="radio" id="star3" name="rating" value="3" />
                                        <label class="star" for="star3">★</label>
                                        <input required type="radio" id="star4" name="rating" value="4" />
                                        <label class="star" for="star4">★</label>
                                        <input required type="radio" id="star5" name="rating" value="5" />
                                        <label class="star" for="star5">★</label>
                                    </div>

                                    <div class="comment-box" id="contentValue">
                                        <textarea style="height: 64px;width: 100%;resize: none;overflow: hidden;" id="autoresize" rows="4" cols="50" name="content-comment" placeholder="Nhận xét về sản phẩm..."></textarea>
                                    </div>
                                    <input type="hidden" name="oder_product_id" value="<?= $product_id ?>">
                                    <input type="hidden" name="oder_product_user" value="<?= $user_id ?>">
                                    <div style="margin-bottom: 6px;">
                                        <button style="background-color: #3498db;color:white;border:#3498db;"  class="back-button button-product" id="updateButton3"><a style="color: white;" href="commenttest.php">Trở lại</a></button>
                                        <button  style="background-color: #3498db;color:white;border:#3498db;" class="review-button button-product" name="insert_danhgia">Đánh giá</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
    ?>
    <!-- sản phẩm mua rồi -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var updateButton0 = document.getElementById("updateButton0");
            var updateButton1 = document.getElementById("updateButton1");
            var htmlContainer = document.getElementById("htmlContainer");
            var htmlContainer1 = document.getElementById("htmlContainer1");
            updateButton0.addEventListener("click", function () {
                // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
                // Hiển thị phần tử htmlContainer
                htmlContainer.style.display = "none";
                htmlContainer1.style.display = "block";
                updateButton0.style= "border-bottom:1px solid";
                updateButton1.style="null";
                
            });
            updateButton1.addEventListener("click", function () {
                // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
                // Hiển thị phần tử htmlContainer
                htmlContainer.style.display = "block";
                htmlContainer1.style.display = "none";
                updateButton0.style= "null";
                updateButton1.style="border-bottom:1px solid";
                
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var updateButton3 = document.getElementById("updateButton3");
            var htmlContainer2 = document.getElementById("htmlContainer2");
            updateButton3.addEventListener("click", function () {
                // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
                // Hiển thị phần tử htmlContainer
                htmlContainer2.style.display = "none";
            });
            // updateButton1.addEventListener("click", function () {
            //     // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
            //     // Hiển thị phần tử htmlContainer
            //     htmlContainer.style.display = "block";
            //     htmlContainer1.style.display = "none";
            // });
        });
        // ngôi sao 
        const stars = document.querySelectorAll('.star');
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        const checkedIndex = Array.from(radioButtons).findIndex(radio => radio.checked);

        for (let i = 0; i <= checkedIndex; i++) {
            stars[i].classList.add('checked');
        }

        for (let i = 0; i < stars.length; i++) {
            stars[i].addEventListener('click', () => {
                // Đặt tất cả các ngôi sao về trạng thái mặc định (không được chọn)
                for (let j = 0; j < stars.length; j++) {
                    stars[j].classList.remove('checked');
                }
                
                // Đặt các ngôi sao từ 1 đến ngôi sao được chọn thành trạng thái được chọn
                for (let j = 0; j <= i; j++) {
                    stars[j].classList.add('checked');
                }

                // Đặt trạng thái "checked" cho input radio tương ứng
                radioButtons[i].checked = true;
            });
        }
        const textarea = document.getElementById("autoresize");

        // Sử dụng sự kiện input để theo dõi thay đổi nội dung
        textarea.addEventListener("input", function () {
            // Đặt chiều cao của textarea dựa trên scrollHeight
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";
        });
    </script>
<!-- <div class="col-sm-6 mb-3">
<div class="card h-100">
<div class="card-body">
<h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>
<small>Web Design</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>Website Markup</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>One Page</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>Mobile Template</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>Backend API</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
</div>
</div> -->
</div>


<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	
</script>
</body>
</html>