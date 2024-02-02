<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


if(isset($_POST['danhgia'])){
    $product_id=$_POST['oder_product_id'];
    $content_comment=$_POST['content-comment'];
    $review_comment=$_POST['rating'];
    $sql = "INSERT INTO tbl_comment (content,product_id ,user_id, time_post,review) VALUES (?, ?, ?, null,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$content_comment,$product_id, $user_id,$review_comment]);
    if($stmt){
        echo '<script>alert("Đánh giá thành công!");window.location.href ="review-details.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* CSS cho khối sản phẩm */
        .product-container {
            border: 1px solid #ccc;
            padding: 20px;
        }

        /* CSS cho mỗi sản phẩm */
        .product {
            display: flex;
            justify-content: space-between; /* Canh đều giữa các thành phần bên trong */
            align-items: center;
            margin: 0px 10px; /* Khoảng cách giữa các sản phẩm */
            border-bottom: 1px solid black;
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
            margin-top: 20px;
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
        }

        .rating-container input[type="radio"]:checked ~ label {
            color: gold; /* Màu sắc khi ngôi sao được chọn */
        }

    </style>
</head>
<body>
    <!-- Khối sản phẩm chung -->
    <div class="menu">
        <ul>
            <li><input style="margin-top: 5px;" type="button" value="Chờ xử lí" id="updateButton0"></li>
            <li><input style="margin-top: 5px;" type="button" value="Những sản phẩm đã mua" id="updateButton1"></li>
        </ul>
    </div>
    <div id="htmlContainer" style="display: block;">
        <?php
                $select_oder = $conn->prepare("SELECT * FROM tbl_oder where status = 0"); 
                $select_oder->execute();
                if($select_oder->rowCount() > 0){
                    while($fetch_oder = $select_oder->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="product-container">
                <!-- Sản phẩm 1 -->
                <?php
                        $order_id=$fetch_oder['id'];
                        $select_oder_product = $conn->prepare("SELECT * FROM tbl_oder,tbl_oder_product where status = 0 and user_id=4 and tbl_oder.id=$order_id and tbl_oder.id=tbl_oder_product.oder_id;"); 
                        $select_oder_product->execute();
                        while($fetch_oder_product = $select_oder_product->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="product">
                    <!-- Ảnh sản phẩm 1 -->
                    <div class="product-image">
                        <img src="uploads/blog-1.jpg" alt="Sản phẩm 1">
                    </div>
                    <!-- Thông tin sản phẩm 1 -->
                    <div class="product-info">
                        <h2 class="product-title">Sản phẩm 1</h2>
                        <p class="product-category">Phân loại đơn hàng: Dior</p>
                        <p class="product-quantity">x1</p>
                    </div>
                    <!-- Số tiền sản phẩm 1 -->
                    <div class="product-price">
                        <p>X1</p>
                    </div>
                </div>

                <!-- Khối tổng tiền -->
                <div class="total-container">
                    <div class="total">
                        Tổng tiền: $X.XX <!-- Thay X.XX bằng tổng tiền thực tế -->
                    </div>
                </div>
                <?php
                        }
                ?>
                <!-- Khối nút -->
                <div class="buttons">
                    <button class="button">Nút 1</button>
                    <button class="button">Nút 2</button>
                    <button class="button">Nút 3</button>
                </div>
            </div>
            <?php
                    }
                }
            ?>
    </div> 

    <!-- sản phẩm mua rồi -->

    <div id="htmlContainer1" style="display: none;">
        <?php
            $select_oder_product = $conn->prepare("SELECT p.name AS product_name, category.name AS category_name,p.price,p.id as product_id ,p.image_1 FROM tbl_oder AS oder,tbl_oder_product AS oder_p,tbl_product as p,tbl_category AS category where status = 1 and user_id=4 and oder.id=oder_p.oder_id and oder_p.product_id=p.id and category.id=p.category_id ;"); 
            $select_oder_product->execute();
            while($fetch_oder_product = $select_oder_product->fetch(PDO::FETCH_ASSOC)){
        ?>
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
                    <p><?= $fetch_oder_product['price']  ?></p>
                </div>
            </div>

            <!-- Khối tổng tiền -->
            <div class="total-container">
                <div class="total">
                    Tổng tiền: <?= $fetch_oder_product['price']  ?> <!-- Thay X.XX bằng tổng tiền thực tế -->
                </div>
            </div>
            <div class="buttons">
                <button class="button-product"><a href="shop-details.php?id=<?=$fetch_oder_product['product_id']?>">Mua lại</a></button>
                <button class="button-product" data-product-name="<?= $fetch_oder_product['product_name'] ?>" 
                data-product-category="<?= $fetch_oder_product['category_name'] ?>"
                data-product-img="uploaded_img/<?= $fetch_oder_product['image_1'] ?>"
                data-product-id="<?= $fetch_oder_product['product_id'] ?>"
                id="updateButton2">Đánh giá</button>
                <button class="button-product">Nút 3</button>
            </div>
        </div>
        <?php
                }
        ?>

    </div>
    <div class="overlay" id="htmlContainer2" style="display: none;">
        <div class="container-review" style="width: 730px;margin-top: -200px;">
            <div class="content">
                <form action="" method="post">
                    <div class="product-container">
                        <div style="display: flex;align-items: center;">
                            <div id="productInfoImg" style="width:56px;height:56px;">
                                <input type="hidden" name="oder_product_id" value="">
                                <img style="width: 100%;height: 100%;" class="product-image" src="uploaded_img/product-1.jpg" alt="ảnh sản phẩm">
                            </div>
                            <div id="productInfo">
                                <h4 style="margin: -20px 0 0px 10px;">Tên sản phẩm</h4>
                                <p style="margin: 5px 0 0 10px;">Phân loại sản phẩm</p>
                            </div>
                        </div>
                        
                        <label for="">Chất lượng sản phẩm</label>
                        <div class="rating-container">
                            <input required type="radio" id="star1" name="rating" value="1" />
                            <label for="star1">★</label>
                            <input required type="radio" id="star2" name="rating" value="2" />
                            <label for="star2">★</label>
                            <input required type="radio" id="star3" name="rating" value="3" />
                            <label for="star3">★</label>
                            <input required type="radio" id="star4" name="rating" value="4" />
                            <label for="star4">★</label>
                            <input required type="radio" id="star5" name="rating" value="5" />
                            <label for="star5">★</label>
                        </div>

                        <div class="comment-box" id="contentValue">
                            <textarea style="height: 64px;width: 670px;resize: none;overflow: hidden;" id="myTextarea" rows="4" cols="50" name="content-comment" placeholder="Nhận xét về sản phẩm..."></textarea>
                        </div>
                        <input type="hidden" name="oder_product_user" value="<?= $user_id ?>">
                        <button class="back-button" id="updateButton3">Trở lại</button>
                        <button class="review-button" name="danhgia">Đánh giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- sản phẩm mua rồi -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var updateButton0 = document.getElementById("updateButton0");
            var updateButton1 = document.getElementById("updateButton1");
            var htmlContainer = document.getElementById("htmlContainer");
            var htmlContainer1 = document.getElementById("htmlContainer1");
            const productImage = document.getElementById('overlayImage');
            updateButton0.addEventListener("click", function () {
                // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
                // Hiển thị phần tử htmlContainer
                htmlContainer.style.display = "block";
                htmlContainer1.style.display = "none";
            });
            updateButton1.addEventListener("click", function () {
                // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
                // Hiển thị phần tử htmlContainer
                htmlContainer.style.display = "none";
                htmlContainer1.style.display = "block";
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var updateButton2 = document.getElementById("updateButton2");
            var updateButton3 = document.getElementById("updateButton3");
            var htmlContainer2 = document.getElementById("htmlContainer2");
            var myTextarea = document.getElementById("myTextarea");
            const showOverlayButtons = document.querySelectorAll('.button-product');
            const productInfo =document.getElementById('productInfo');
            const productInfoImg =document.getElementById('productInfoImg');
            const contentValues = document.getElementById('contentValue');

            showOverlayButtons.forEach((button, index) => {
                button.addEventListener('click', () => {
                    const productNameValue = button.getAttribute('data-product-name');
                    const productNameImg = button.getAttribute('data-product-img');
                    const categoryNameValue = button.getAttribute('data-product-category');
                    const productIdValue = button.getAttribute('data-product-id');
                    htmlContainer2.style.display = "block";
                    htmlContainer2.style.display = "flex";
                    myTextarea.style.height = '64px';
                    productInfo.innerHTML = `<h4 style="margin: -20px 0 0px 10px;">Tên sản phẩm : ${productNameValue}</h4>
                    <p style="margin: 5px 0 0 10px;">Phân loại sản phẩm : ${categoryNameValue} </p>`;
                    productInfoImg.innerHTML = `<img style="width: 100%;height: 100%;" class="product-image" src="${productNameImg}" alt="ảnh sản phẩm">
                                                <input type="text" name="oder_product_id" value="${productIdValue}">
                    `;
                    contentValues.innerHTML =`<textarea style="height: 64px;width: 670px;resize: none;overflow: hidden;" id="myTextarea" rows="4" cols="50" name="content-comment" placeholder="Nhận xét về sản phẩm..."></textarea>`

                });
            });

            // updateButton2.addEventListener("click", function () {
            //     // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
            //     // Hiển thị phần tử htmlContainer
            //     htmlContainer2.style.display = "block";
            //     htmlContainer2.style.display = "flex";
            //     myTextarea.style.height = '64px';
            // });
            updateButton3.addEventListener("click", function () {
                // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                
                // Hiển thị phần tử htmlContainer
                htmlContainer2.style.display = "none";
            });
        });
        

        // ngôi sao 
        const radioButtons = document.querySelectorAll('.rating-container input[type="radio"]');
        
        // Thêm sự kiện click cho từng input radio
        radioButtons.forEach(radioButton => {
            radioButton.addEventListener('click', function() {
                const selectedRating = parseInt(this.value); // Lấy số sao đã chọn
                // Lặp qua tất cả các label và thiết lập màu sắc dựa trên số sao đã chọn
                for (let i = 0; i < radioButtons.length; i++) {
                    const label = document.querySelectorAll('.rating-container label')[i];
                    if (i < selectedRating) {
                        label.style.color = 'gold';
                    } else {
                        label.style.color = '#ccc';
                    }
                }
            });
        });
        var textarea = document.getElementById('myTextarea');

        // Tạo hàm để tự động điều chỉnh chiều cao
        function autoAdjustHeight() {
            textarea.style.height = 'auto'; // Đặt chiều cao về auto trước để tính lại chiều cao thực tế
            textarea.style.height = (textarea.scrollHeight) + 'px'; // Đặt chiều cao mới dựa trên chiều cao thực tế
        }

        // Gọi hàm khi nội dung thay đổi
        textarea.addEventListener('input', autoAdjustHeight);

        // Gọi hàm một lần khi trang được tải
        autoAdjustHeight();
    </script>
</body>
</html>
