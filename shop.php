
<?php 
    include 'components/connect.php';
    session_start();

    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }else{
        $user_id = '';
    };
    //--------------
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
    //----------------
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop</title>

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
        h5.out-of-stock {
            background-color: red;
            color: white;
            border: 2px solid red;
            border-radius: 5px;
            padding: 5px 10px;
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            cursor: not-allowed;
            width: 85px;
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
        .col-lg-4 .col-md-6 .col-sm-6 {
            -ms-flex: 0 0 25%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    </style>
</head>

<body>
    <?php 
        $sql = "SELECT * FROM tbl_product WHERE (1=1) ";
        $whereClasue = " ";
        $result;
        $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        function hasGetParameters() {
            return count($_GET) > 0;
        }

        function modifyQueryParam($url, $paramName1, $paramValue1, $paramName2, $paramValue2) {
            $parsedUrl = parse_url($url);
            $queryParams = [];
        
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
            }
        
            $queryParams[$paramName1] = $paramValue1;
            $queryParams[$paramName2] = $paramValue2;
            $modifiedQuery = http_build_query($queryParams);
            $modifiedUrl = $parsedUrl['scheme'] . '://' . $_SERVER['HTTP_HOST'] . $parsedUrl['path'];
        
            if (!empty($modifiedQuery)) {
                $modifiedUrl .= '?' . $modifiedQuery;
            }
        
            return $modifiedUrl;
        }
    ?>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form method="GET">
                                <?php 
                                $searchValue = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';  
                                ?>
                                <input type="text" id="searchInput" name="search" placeholder="Search..." value="<?php echo $searchValue; ?>">
                                
                                <!-- Giữ nguyên các tham số GET khác -->
                                <?php
                                foreach ($_GET as $key => $value) {
                                    if ($key !== 'search') {
                                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                    }
                                }
                                ?>
                                
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>

                        <?php
                            if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['search']) && !empty($_GET['search'])) {
                                $value = trim($_GET['search']);
                                if (preg_match('/^[\p{L}0-9\s]+$/u', $value)) {
                                    $whereClasue .= " AND (name LIKE '%$value%' OR details LIKE '%$value%') ";
                                } else {
                                    echo "<p style='color: red;'>Dữ liệu không hợp lệ. Vui lòng chỉ nhập chữ và số và không được để trống.</p>";
                                }
                            }
                        ?>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body" style="height: 120px">
                                            <div class="shop__sidebar__categories">
                                            <ul>
                                                <li><a <?= (isset($_GET['sex']) && $_GET['sex'] == '1') ? 'style="color: red;"' : '' ?> href="<?= hasGetParameters() && !isset($_GET['sex']) ? $currentURL . "&sex=1" : (isset($_GET['sex']) ? str_replace('sex=' . $_GET['sex'], 'sex=1', $currentURL) : $currentURL . "?sex=1") ?>">Men</a></li>
                                                <li><a <?= (isset($_GET['sex']) && $_GET['sex'] == '2') ? 'style="color: red;"' : '' ?> href="<?= hasGetParameters() && !isset($_GET['sex']) ? $currentURL . "&sex=2" : (isset($_GET['sex']) ? str_replace('sex=' . $_GET['sex'], 'sex=2', $currentURL) : $currentURL . "?sex=2") ?>">Women</a></li>
                                                <li><a <?= (isset($_GET['sex']) && $_GET['sex'] == '3') ? 'style="color: red;"' : '' ?> href="<?= hasGetParameters() && !isset($_GET['sex']) ? $currentURL . "&sex=3" : (isset($_GET['sex']) ? str_replace('sex=' . $_GET['sex'], 'sex=3', $currentURL) : $currentURL . "?sex=3") ?>">Unisex</a></li>
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    if ($_SERVER['REQUEST_METHOD'] == "GET" && (isset($_GET['sex']) && !empty($_GET['sex']))) {
                                        $sex = $_GET['sex'];
                                        $whereClasue .= " AND sex = " . $sex;
                                    }
                                ?>
                                <?php
                                    if ($conn) {
                                        try {
                                            $sql_category = "SELECT * FROM tbl_category";
                                            $result_category = $conn->query($sql_category);
                                            echo "<div class='card'>
                                                    <div class='card-heading'>
                                                        <a data-toggle='collapse' data-target='#collapseTwo'>Branding</a>
                                                    </div>
                                                    <div id='collapseTwo' class='collapse show' data-parent='#accordionExample'>
                                                        <div class='card-body'>
                                                            <div class='shop__sidebar__brand'>
                                                                <ul>";
                                            if ($result_category) {
                                                while ($row = $result_category->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<li><a " . (isset($_GET['categoryId']) && $_GET['categoryId'] == $row['id'] ? 'style="color: red;"' : '') . " href='" . (isset($_GET['categoryId']) ? str_replace('categoryId=' . $_GET['categoryId'], 'categoryId=' . $row["id"], $currentURL) : $currentURL . (hasGetParameters() ? "&" : "?") . "categoryId=" . $row["id"]) . "'>" . $row["name"] . "</a></li>";
                                                }                                                
                                            } else {
                                                echo "Lỗi truy vấn: " . $conn->errorInfo()[2];
                                            }
                                            echo "</ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                        } catch (PDOException $e) {
                                            echo "Lỗi truy vấn: " . $e->getMessage();
                                        }
                                    } else {
                                        echo "Kết nối không thành công.";
                                    }
                                ?>
                                <?php
                                    if ($_SERVER['REQUEST_METHOD'] == "GET") {
                                        if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                                            $categoryId = $_GET['categoryId'];
                                            $whereClasue .= " AND category_id = " . $categoryId;
                                        }
                                    }
                                ?>
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                                    </div>
                                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__price">
                                                <?php
                                                    $max_price = $conn->query("SELECT MAX(price) as max_price FROM tbl_product")->fetch(PDO::FETCH_ASSOC)["max_price"];
                                                ?>
                                                <ul>
                                                    <li><a <?= (isset($_GET['min_price']) && $_GET['min_price'] == '0' && isset($_GET['max_price']) && $_GET['max_price'] == urlencode(round($max_price / 4, -1))) ? 'style="color: red;"' : '' ?> href="<?= modifyQueryParam($currentURL, 'min_price', '0', 'max_price', urlencode(round($max_price / 4, -1))) ?>">0.00đ - <?= number_format(round($max_price / 4, -1), 0, ',', '.') ?> đ</a></li>
                                                    <li><a <?= (isset($_GET['min_price']) && $_GET['min_price'] == round($max_price / 4, -1) && isset($_GET['max_price']) && $_GET['max_price'] == urlencode(round($max_price / 3, -1))) ? 'style="color: red;"' : '' ?> href="<?= modifyQueryParam($currentURL, 'min_price', round($max_price / 4, -1), 'max_price', urlencode(round($max_price / 3, -1))) ?>"> <?= number_format(round($max_price / 4, -1), 0, ',', '.') ?>đ - <?= number_format(round($max_price / 3, -1), 0, ',', '.') ?> đ</a></li>
                                                    <li><a <?= (isset($_GET['min_price']) && $_GET['min_price'] == round($max_price / 2, -1) && isset($_GET['max_price']) && $_GET['max_price'] == urlencode(round($max_price, -1))) ? 'style="color: red;"' : '' ?> href="<?= modifyQueryParam($currentURL, 'min_price', round($max_price / 2, -1), 'max_price', urlencode(round($max_price, -1))) ?>"> <?= number_format(round($max_price / 2, -1), 0, ',', '.') ?>đ - <?= number_format(round($max_price, -1), 0, ',', '.') ?> đ</a></li>
                                                </ul>
                                                <?php
                                                if ($_SERVER['REQUEST_METHOD'] == "GET") {
                                                    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                                                        $min_price = $_GET['min_price'];
                                                        $max_price = $_GET['max_price'];
                                                        $whereClasue .= " AND (price >= $min_price AND price <= $max_price)";
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    $item_per_page = !empty($_GET['per_page'])?$_GET['per_page']:8;
                    $current_page = !empty($_GET['page'])?$_GET['page']:1;
                    $offset = ($current_page -  1) * $item_per_page;
                    $totalRecords;
                    $totalPages;
                    $totalPages = ceil(($conn->query("SELECT COUNT(*) FROM tbl_product WHERE (1=1) " . $whereClasue)->fetchColumn()) / $item_per_page);
                ?> 
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing <?=$current_page?>/<?=$totalPages?> of <?=($conn->query("SELECT COUNT(*) FROM tbl_product WHERE (1=1) " . $whereClasue)->fetchColumn())?> results</p>
                                    <?php
                                        $sex = isset($_GET['sex']) ? $_GET['sex'] : null;
                                        $categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : null;
                                        $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : null;
                                        $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : null;
                                        $search = isset($_GET['search']) ? $_GET['search'] : null;

                                        if ($sex !== null || $categoryId !== null || $min_price !== null || $max_price !== null || $search !== null) {
                                            echo '<p>Filter by: </p>';
                                            if ($sex !== null) {
                                                $mes = "Nước hoa dành cho ";
                                                if ($sex == 1) {
                                                    $mes .= "Nam<br>";
                                                } elseif ($sex == 2) {
                                                    $mes .= "Nữ<br>";
                                                } elseif ($sex == 3) {
                                                    $mes .= "Cả nam và nữ<br>";
                                                } else {
                                                    echo '<p>Giá trị không hợp lệ cho giới tính</p>';
                                                }
                                                echo $mes;
                                            }
                                            if ($categoryId !== null) {
                                                echo '<p>Thương hiệu: ' . $conn->query("SELECT name FROM tbl_category WHERE id = " . $categoryId)->fetchColumn() . '</p>';
                                            }
                                            if ($min_price !== null && $max_price !== null) {
                                                echo "<p>Price: " . number_format($min_price, 0, ',', '.') . "đ - " . number_format($max_price, 0, ',', '.') ."đ</p>";
                                            }
                                            if ($search !== null) {
                                                echo "<p>Search: " . $search . "</p>";
                                            }
                                            echo "<a href='/qlkh/shop.php' style='text-decoration: none; color: red;'>Remove filter</a>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sort by Price:</p>
                                    <select id="select-filter">
                                        <option value="">-Select-</option>
                                        <option value="<?= hasGetParameters() && !isset($_GET['sort']) ? $currentURL . "&sort=asc" : (isset($_GET['sort']) && $_GET['sort'] !== 'asc' ? str_replace('sort=' . $_GET['sort'], 'sort=asc', $currentURL) : $currentURL . "?sort=asc") ?>" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'asc') echo 'selected'; ?>>Low To High</option>
                                        <option value="<?= hasGetParameters() && isset($_GET['sort']) && $_GET['sort'] !== 'desc' ? str_replace('sort=' . $_GET['sort'], 'sort=desc', $currentURL) : $currentURL . "?sort=desc" ?>" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'desc') echo 'selected'; ?>>High To Low</option>
                                    </select>
                                    <?php
                                    if (isset($_GET['sort'])) {
                                        $sort_order = $_GET['sort'];
                                        if ($sort_order == 'asc') {
                                            $whereClasue .= " ORDER BY price ASC";
                                        } else if ($sort_order == 'desc') {
                                            $whereClasue .= " ORDER BY price DESC";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($conn) {
                        try {
                            $sql .= $whereClasue;
                            $sql .= " LIMIT " . $item_per_page . " OFFSET " . $offset;
                            $result = $conn->query($sql);
                            $totalRecords = $result->rowCount();
                            $productCount = 0;
                            echo "<div class='row'>";
                            if ($result) {
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                                    $productCount++;?>
                                        <div class='col-lg-4 col-md-6 col-sm-6'>
                                            <form action="" method='POST'>
                                            <div class='product__item'>
                                                <div class='product__item__pic set-bg' data-setbg='uploaded_img/<?=$row["image_1"]?>'>
                                                    <ul class='product__hover'>
                                                        <li><a href='shop-details.php?id=<?=$row['id']?>'><img src='img/icon/search.png' alt=''> <span>Xem chi tiết</span></a>
                                                        </li> 
                                                        <?php
                                                        $select_wish_color=$conn->prepare("SELECT *FROM tbl_wishlist where user_id=? and product_id=?");
                                                        $select_wish_color->execute([$user_id,$row['id']]);
                                                        if($select_wish_color->rowCount()>0){
                                                            echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img style="background: #ff0000;" src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                                        }else{
                                                            echo '<li><button type="submit" name="wishlist" style="border: none;padding: 0;width: 36px;height: 37px;"><img src="img/icon/heart.png" alt=""><span style="margin-right: 42px;">Thêm yêu thích</span></button></li>';
                                                        }
                                                        ?>                                                       </ul>
                                                </div>
                                                <div class='product__item__text'>
                                                    <a href='shop-details.php?id=<?=$row['id']?>'><?php echo $row["name"]; ?></a>
                                                        <input type='hidden' name='pid' value="<?=$row['id']?>">
                                                        <?php
                                                        if (($conn->query("SELECT quantity FROM tbl_product WHERE (id = $row[id]) ")->fetchColumn()) > 0) {
                                                        ?>
                                                            <input type='submit' name='addcart' class='add-cart' value='Add to cart'>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <h5 class="out-of-stock">Hết hàng</h5>
                                                        <?php
                                                        }
                                                        ?>      
                                                    <?php
                                                        $product_id_new=$row['id'];
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
                                                    <?php
                                                    $price = $row["price"];
                                                    $formatted_price = number_format($price, 0, ',', '.'); // Định dạng số tiền

                                                    echo "<h5 style='font-style: oblique; color: red;'> " . $formatted_price . "đ</h5>"; // Hiển thị số tiền định dạng
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                            <input type='hidden' name='id' value="<?=$row['id']?>">
                                            <input type='hidden' name='name' value="<?=$row['name']?>">
                                            <input type='hidden' name='price' value="<?=$row['price']?>">
                                            <input type='hidden' name='image' value="<?=$row['image_1']?>">
                                            <input type='hidden' name='qty' value="1" >
                                            <input type="hidden" name="soluong" value="<?= $row['quantity']; ?>" >

                                        </form>
                            <?php      
                                }
                                if ($productCount === 0) {
                                    echo "<h4 style='text-align: center; margin: auto;'>Không có sản phẩm nào.</h4>";
                                }
                            } else {
                                echo "Lỗi truy vấn: " . $conn->errorInfo()[2];
                            }
                            echo "</div>";
                        } catch (PDOException $e) {
                            echo "Lỗi truy vấn: " . $e->getMessage();
                        }
                    } else {
                        echo "Kết nối không thành công.";
                    }
                    ?>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__pagination">
                                <?php if ($current_page > 3) { 
                                    $first_page = 1;
                                    $link = "?per_page=$item_per_page&page=$first_page";
                                    if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                                        $categoryId = $_GET['categoryId'];
                                        $link .= "&categoryId=$categoryId";
                                    }
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $value = $_GET['search'];
                                        $link .= "&search=$value";
                                    }
                                    if (isset($_GET['sex']) && !empty($_GET['sex'])) {
                                        $sex = $_GET['sex'];
                                        $link .= "&sex=$sex";
                                    }
                                    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                                        $min_price = $_GET['min_price'];
                                        $max_price = $_GET['max_price'];
                                        $link .= "&min_price=$min_price&max_price=$max_price";
                                    }
                                    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                                        $sort = $_GET['sort'];
                                        $link .= "&sort=$sort";
                                    }
                                ?>
                                <a class="active" href="<?= $link ?>">F</a>
                                <?php } ?>
                                <?php if ($current_page > 1) { 
                                    $prev_page = $current_page - 1;
                                    $link = "?per_page=$item_per_page&page=$prev_page";
                                    if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                                        $categoryId = $_GET['categoryId'];
                                        $link .= "&categoryId=$categoryId";
                                    }
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $value = $_GET['search'];
                                        $link .= "&search=$value";
                                    }
                                    if (isset($_GET['sex']) && !empty($_GET['sex'])) {
                                        $sex = $_GET['sex'];
                                        $link .= "&sex=$sex";
                                    }
                                    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                                        $min_price = $_GET['min_price'];
                                        $max_price = $_GET['max_price'];
                                        $link .= "&min_price=$min_price&max_price=$max_price";
                                    }
                                    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                                        $sort = $_GET['sort'];
                                        $link .= "&sort=$sort";
                                    }
                                ?>
                                <a class="active" href="<?= $link ?>"><</a>
                                <?php } ?>
                                <?php
                                for ($num = 1; $num <= $totalPages; $num++) {
                                    $link = "?per_page=$item_per_page&page=$num";
                                    if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                                        $categoryId = $_GET['categoryId'];
                                        $link .= "&categoryId=$categoryId";
                                    }
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $value = $_GET['search'];
                                        $link .= "&search=$value";
                                    }
                                    if (isset($_GET['sex']) && !empty($_GET['sex'])) {
                                        $sex = $_GET['sex'];
                                        $link .= "&sex=$sex";
                                    }
                                    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                                        $min_price = $_GET['min_price'];
                                        $max_price = $_GET['max_price'];
                                        $link .= "&min_price=$min_price&max_price=$max_price";
                                    }
                                    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                                        $sort = $_GET['sort'];
                                        $link .= "&sort=$sort";
                                    }
                                    if ($num != $current_page) {
                                        if ($num > $current_page - 2 && $num < $current_page + 2) {
                                            echo "<a class='active' href='$link'>$num</a>";
                                        }
                                    } else {
                                        echo "<a class='active' href='$link' style='background-color: #000000; color: #ffffff; border-color: #111111;'>$num</a>";
                                    }
                                }
                                ?>
                                <?php if ($current_page < $totalPages - 1) { 
                                    $next_page = $current_page + 1;
                                    $link = "?per_page=$item_per_page&page=$next_page";
                                    if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                                        $categoryId = $_GET['categoryId'];
                                        $link .= "&categoryId=$categoryId";
                                    }
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $value = $_GET['search'];
                                        $link .= "&search=$value";
                                    }
                                    if (isset($_GET['sex']) && !empty($_GET['sex'])) {
                                        $sex = $_GET['sex'];
                                        $link .= "&sex=$sex";
                                    }
                                    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                                        $min_price = $_GET['min_price'];
                                        $max_price = $_GET['max_price'];
                                        $link .= "&min_price=$min_price&max_price=$max_price";
                                    }
                                    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                                        $sort = $_GET['sort'];
                                        $link .= "&sort=$sort";
                                    }
                                ?>
                                <a class="active" href="<?= $link ?>">></a>
                                <?php } ?>
                                <?php if ($current_page < $totalPages - 3) { 
                                    $end_page = $totalPages;
                                    $link = "?per_page=$item_per_page&page=$end_page";
                                    if (isset($_GET['categoryId']) && !empty($_GET['categoryId'])) {
                                        $categoryId = $_GET['categoryId'];
                                        $link .= "&categoryId=$categoryId";
                                    }
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $value = $_GET['search'];
                                        $link .= "&search=$value";
                                    }
                                    if (isset($_GET['sex']) && !empty($_GET['sex'])) {
                                        $sex = $_GET['sex'];
                                        $link .= "&sex=$sex";
                                    }
                                    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
                                        $min_price = $_GET['min_price'];
                                        $max_price = $_GET['max_price'];
                                        $link .= "&min_price=$min_price&max_price=$max_price";
                                    }
                                    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                                        $sort = $_GET['sort'];
                                        $link .= "&sort=$sort";
                                    }
                                ?>
                                <a class="active" href="<?= $link ?>">L</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    <script>
        $('#select-filter').change(function(){
            var value = $(this).val();
            window.location.replace(value);
        });
    </script>
</body>
</html>