<?php

include '../components/connect.php';

session_start();

//     if(isset($_SESSION['admin_id'])){
//         $admin_id = $_SESSION['admin_id'];
//     } else {
//         header('location:../admin_login.php');
//     }
$blog_id=$_GET['blog_id'];
if(isset($_POST['btnXoa'])){
    $delete_content=$_POST['delete_id'];
    $delete_order = $conn->prepare("DELETE FROM `tbl_img_blog` WHERE id = ?");
    $delete_order->execute([$delete_content]);
    if($delete_order->rowCount()>0){
        echo '<script>
                    var currentBlogId = window.location.search.replace("?blog_id=", "");
                    alert("Xóa thành công!");window.location.reload();
                    window.location.href = "blog_details.php?blog_id=" + currentBlogId;
                    </script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <?php include ('../admin/component/admin_head.php'); ?>
    <style>
        table {
            max-width:1200px;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: center;
            
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        th:hover {
            background-color: #555;
        }

        td:hover {
            background-color: #f5f5f5;
        }

        td:first-child {
            border-radius: 5px 0 0 5px;
        }

        td:last-child {
            border-radius: 0 5px 5px 0;
        }
        #my-paragraph {
            display: -webkit-box;
            -webkit-line-clamp: 4; /* Hiển thị 4 dòng */
            -webkit-box-orient: vertical;
            line-height: 1.2em;
            max-height: 4.8em; /* 4 dòng * 1.2em */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }
        a.button-like {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db; /* Màu nền */
            color: #fff; /* Màu chữ */
            text-decoration: none; /* Loại bỏ gạch chân mặc định */
            border: none;
            border-radius: 5px; /* Góc bo tròn */
            cursor: pointer;
            text-align: center;
        }

        a.button-like:hover {
            background-color: #2980b9; /* Màu nền khi di chuột qua */
        }
    </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
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
                                            <li style="list-style-type: none;margin-left: -33px;"><?= $formatted_time_post ?></li>
                                        </ul>
                                        <p style="margin-top: 18px;"><?= $fetch_blog['content'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin: 10px 0;">
                            <a class="button-like" style="margin-left: 56px;" href="insert_content.php?blog_id=<?= $blog_id ?>">Thêm nội dung</a>
                        </div>
                        <div class="content-contact">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Ảnh</th>
                                        <th>Sản phẩm</td>
                                        <th style="width: 22%;">Tiêu đề</th>
                                        <th style="width: 60%;;">Nội dung ảnh</th>
                                        <th style="width: 7%;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $select_conetnt = $conn->prepare("SELECT * FROM tbl_img_blog WHERE id_blog =$blog_id"); 
                                    $select_conetnt->execute();
                                    while($fetch_content = $select_conetnt->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <tr>
                                        <td>
                                            <img style="width:100px;" src="../uploaded_img/<?= $fetch_content['image_blog'] ?>" alt="">
                                        </td>
                                        <td>
                                            <?php
                                            $product_id = $fetch_content['product_id']; // Thay thế 1 bằng giá trị product_id thích hợp
                                            if($product_id==null){
                                                echo'Chưa có sản phẩm quảng cáo';
                                            }else{
                                            $select_product = $conn->prepare("SELECT * FROM tbl_product WHERE id = :product_id");
                                            $select_product->bindParam(":product_id", $product_id, PDO::PARAM_INT);
                                            $select_product->execute();
                                            $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
                                            ?>
                                            <img style="width:100px;" src="../uploaded_img/<?= $fetch_product['image_1'] ?>" alt="">
                                            <label for=""><?= $fetch_product['name'] ?></label>
                                            <?php } ?>
                                        </td>
                                        <td><p><?= $fetch_content['title_img'] ?></p></td>
                                        <td><p id="my-paragraph"><?= $fetch_content['content_img'] ?></p></td>
                                        <td>
                                            <a href="../admin/insert_content.php?blog_id=<?=$blog_id?>&content_id=<?= $fetch_content['id'] ?>" style="display: inline; margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                            <form method='POST' style="display: inline;width:16px;height:16px;">
                                                <input type='hidden' name='delete_id' value='<?=$fetch_content['id']?>'>
                                                <button style="border:none;background: none;" type='submit' name='btnXoa' onclick='return confirm("Bạn có chắc chắn muốn xóa nội dung này?")'><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <!-- Blog Details Section End -->
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
        <script>
            function createTable() {
                var rowCount = parseInt(document.getElementById("rowCountInput").value);
                var table = document.getElementById("dataTable");
                var rowCountDisplay = document.getElementById("rowCountDisplay");
                var themnoidung=document.getElementById("themnoidung");
                var themnoidungtitle=document.getElementById("themnoidungtitle");
                var buttongui=document.getElementById("buttongui");
                // Tạo bảng mới với số dòng đã nhập
                for (var i = 0; i < rowCount; i++) {
                    themnoidung.innerHTML += `
                                <tr>
                                    <td><input required type="file" name="anh${i}"><br>
                                    <input type="hidden" name="sonoidunghidden" value="${rowCount}">
                                    </td>
                                    <td><textarea required rows="4" style="height: 64px; width: 100%; resize: none;" id="autoresize${i}" name="title_img${i}"></textarea></td>
                                    <td><textarea required rows="4" style="height: 64px; width: 100%; resize: none;" id="autoresize${i}" name="content${i}"></textarea></td>
                                </tr>`;
                }
                buttongui.innerHTML ='<input type="submit" value="Thêm" name="insertnoidung">';
            }
        </script>
    </body>
</html>

<?php
    if (isset($_POST['insertnoidung'])) {
        $sonoidung = $_POST['sonoidunghidden'];
        $title_img=array();
        $file_anh = array();
        $filetemp_anh = array();
        $content = array();
    
        // Thu thập thông tin ảnh và nội dung từ biểu mẫu
        for ($i = 0; $i < $sonoidung; $i++) {
            $file_anh[$i] = $_FILES["anh$i"]["name"];
            $content[$i] = $_POST["content$i"];
            $title_img[$i] = $_POST["title_img$i"];
            $filetemp_anh[$i] = $_FILES["anh$i"]["tmp_name"];
        }
    
        // Thực hiện lưu trữ ảnh và thêm thông tin vào cơ sở dữ liệu
        $target_directory = "../uploaded_img/";
        for ($i = 0; $i < $sonoidung; $i++) {
            $target_anh = $target_directory . $file_anh[$i];
            $luu = move_uploaded_file($filetemp_anh[$i], $target_anh);
    
            // Thêm dữ liệu vào cơ sở dữ liệu
            $sql = "INSERT INTO tbl_img_blog (id_blog,title_img ,image_blog, content_img) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$blog_id,$title_img[$i] ,$file_anh[$i], $content[$i]]);
        }
    
        if ($stmt) {
            echo '<script>
                    var currentBlogId = window.location.search.replace("?blog_id=", "");
                    alert("Thêm thành công tin tức!");window.location.reload();
                    window.location.href = "blog_details.php?blog_id=" + currentBlogId;
                    </script>';
        }
    }
?>
