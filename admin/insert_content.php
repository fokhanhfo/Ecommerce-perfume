<?php 
    include '../components/connect.php';
    session_start();

    // if(isset($_SESSION['admin_id'])){
    //     $admin_id = $_SESSION['admin_id'];
    // } else {
    //     header('location:../admin_login.php');
    // }

    // if(isset($_SESSION['user_id'])){
    //     $user_id = $_SESSION['user_id'];
    // }else{
    //     $user_id = '';
    // };
    $blog_id=$_GET['blog_id'];
    if (isset($_POST['themtintuc'])) {
        $sonoidung = $_POST['sonoidunghidden'];
        $product_qc=array();
        $title_img=array();
        $file_anh = array();
        $filetemp_anh = array();
        $content = array();

        // Thu thập thông tin ảnh và nội dung từ biểu mẫu
        for ($i = 0; $i < $sonoidung; $i++) {
            $file_anh[$i] = $_FILES["anh$i"]["name"];
            $content[$i] = $_POST["content$i"];
            $product_qc[$i]=$_POST["selectOption$i"];
            $title_img[$i] = $_POST["title_img$i"];
            $filetemp_anh[$i] = $_FILES["anh$i"]["tmp_name"];
        }

        // Thực hiện lưu trữ ảnh và thêm thông tin vào cơ sở dữ liệu
        $target_directory = "../uploaded_img/";
        for ($i = 0; $i < $sonoidung; $i++) {
            $target_anh = $target_directory . $file_anh[$i];
            $luu = move_uploaded_file($filetemp_anh[$i], $target_anh);

            // Thêm dữ liệu vào cơ sở dữ liệu
            if($product_qc[$i]==null){
            $sql = "INSERT INTO tbl_img_blog (id_blog,title_img ,image_blog, content_img,product_id) VALUES (?, ?, ?, ?,null)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$blog_id,$title_img[$i] ,$file_anh[$i], $content[$i]]);
            }else{
                $sql = "INSERT INTO tbl_img_blog (id_blog,title_img ,image_blog, content_img,product_id) VALUES (?, ?, ?, ?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$blog_id,$title_img[$i] ,$file_anh[$i], $content[$i],$product_qc[$i]]);
            }
        }
        if ($stmt) {
            echo '<script>
                    var url = new URL(window.location.href);
                    var blogId = url.searchParams.get("blog_id");
                    alert("Thêm thành công!");window.location.reload();
                    window.location.href = "../admin/blog_details.php?blog_id=" + blogId;
                    </script>';
        }
    }
    $content_id=$_GET['content_id'];
    if (isset($_POST['updatetintuc'])) {
        $file_anh = $_FILES["anh"]["name"];
        $content= $_POST["content"];
        $selectOption=$_POST["selectOption"];
        $title_img= $_POST["title_img"];
        $filetemp_anh = $_FILES["anh"]["tmp_name"];
        $target_directory = "../uploaded_img/"; // Thư mục lưu trữ ảnh
        $target_file1 = $target_directory . $file_anh;

        // Di chuyển và lưu trữ tệp tải lên
        $luu1 = move_uploaded_file($filetemp_anh, $target_file1);

        // Sử dụng Prepared Statements để thêm thông tin vào cơ sở dữ liệu
        if($file_anh==null){
            if($selectOption==null){
                $sql = "update tbl_img_blog set title_img=? ,content_img=?,product_id=null where id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$title_img, $content, $content_id]);
            }else{
                $sql = "update tbl_img_blog set title_img=? ,content_img=?,product_id=? where id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$title_img, $content,$selectOption, $content_id]);
            }
        }else{
            $target_directory = "../uploaded_img/";
            $target_file1 = $target_directory . $file_anh;
            $luu1 = move_uploaded_file($filetemp_anh, $target_file1);
            $sql = "update tbl_img_blog set title_img=? , image_blog=?,content_img=?,product_id=? where id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title_img, $file_anh, $content, $selectOption ,$content_id]);
        }

        if ($stmt) {
            echo '<script>
                    var url = new URL(window.location.href);
                    var blogId = url.searchParams.get("blog_id");
                    alert("Sửa thành công!");window.location.reload();
                    window.location.href = "../admin/blog_details.php?blog_id=" + blogId;
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
                width: 100%;
                border-collapse: collapse;
            }

            table, th, td {
                
            }

            th, td {
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #473b3b;
                color:white;
                text-align: center;
            }
            .input-container {
                display: flex;
                flex-direction: column;
                margin:30px 0 ;
            }

            label {
                order: -1; /* Đặt label trước input */
            }

            input {
                width: 100%;
            }
            input:focus {
                border-color: #00f; /* Màu viền khi focus (xanh) */
            }
            .nut-bam {
                width: 100px;
                padding: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                transition: background-color 0.3s;
            }

            .nut-bam:hover {
                background-color: #0056b3;
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
                if(!isset($_GET['content_id'])){
                        $blog_id=$_GET['blog_id'];
                        $select_blog = $conn->prepare("SELECT * FROM tbl_blog where id=$blog_id"); 
                        $select_blog->execute();
                        $fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC);
                        $formatted_time_post = date("d F Y", strtotime($fetch_blog["time_post"]));
                    ?>
                    <section class="blog-hero spad" style="padding: 50px 0 0px 0 ;">
                        <div class="container">
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-9 text-center">
                                    <div class="blog__hero__text">
                                        <h2><?= $fetch_blog['title'] ?></h2>
                                        <ul style="margin-left:-33px;">
                                            <li style="list-style-type: none;"><?= $formatted_time_post ?></li>
                                        </ul>
                                        <p style="margin-top: 18px;"><?= $fetch_blog['content'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="content-contact container">
                        <label for="rowCountInput">Nhập số nội dung muốn thêm</label>
                        <input oninput="handleInput(this)"; class="form-control" required type="number" id="rowCountInput" min="1" max="10" value="1">
                    </div>
                    <div class="content-contact container">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div>
                                <table id="myTable" >
                                    <thead>
                                        <tr>
                                            <th style="width: 290px;">Ảnh</th>
                                            <th style="width: 280px;">Tiêu đề</th>
                                            <th>Nội dung ảnh</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <input class="nut-bam" style="width:100px;" type="submit" name="themtintuc" value="Thêm">
                            </div>
                            <div id="buttongui"></div>
                        </form>
                    </div>
                    <script>
                        var rowCountInput = document.getElementById("rowCountInput");
                        var tbody = document.querySelector("#myTable tbody");

                        rowCountInput.addEventListener("input", hienThiBang);
                        function hienThiBang() {
                            var rowCount = parseInt(rowCountInput.value);
                            
                            // Xóa nội dung hiện tại của tbody
                            tbody.innerHTML = "";

                            // Tạo các dòng mới dựa trên số được nhập
                            for (var i = 0; i < rowCount; i++) {
                                var row = document.createElement("tr");
                                row.innerHTML = `<td><input required type="file" name="anh${i}"><br>
                                                <input required type="hidden" name="sonoidunghidden" value="${rowCount}">
                                                <?php
                                                    $select_category = $conn->prepare("SELECT * FROM tbl_category"); 
                                                    $select_category->execute();
                                                ?>
                                                <select name="selectOption${i}" id="selectOption">
                                                    <option value="">Sản phẩm quảng cáo</option>
                                                    <?php
                                                    while ($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)) {
                                                        $category_id = $fetch_category['id'];
                                                    ?>
                                                        <optgroup label="<?= $fetch_category['name'] ?>">
                                                            <?php
                                                            $select_type = $conn->prepare("SELECT * FROM tbl_product where category_id=$category_id");
                                                            $select_type->execute();
                                                            while ($fetch_type = $select_type->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                <option value="<?= $fetch_type['id'] ?>">
                                                                    <?= $fetch_type['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td><textarea class="form-control" required rows="5" style=" width: 100%;" id="autoresize${i}" name="title_img${i}"></textarea></td>
                                                <td><textarea class="form-control" required rows="5" style="width: 100%;" id="autoresize${i}" name="content${i}"></textarea></td>`;
                                tbody.appendChild(row);
                            }
                        }

                        // Gọi hienThiBang lần đầu khi trang tải
                        hienThiBang();
                        function handleInput(inputElement) {
                            // Giữ nội dung gốc của thẻ input
                            var originalValue = inputElement.getAttribute("value");

                            // Nếu người dùng xóa hết nội dung, đặt lại giá trị thành nội dung gốc
                            if (inputElement.value === "") {
                                inputElement.value = originalValue;
                            }
                        }
                    </script>
                    <?php 
                    }else{
                        $blog_id=$_GET['blog_id'];
                        $select_blog = $conn->prepare("SELECT * FROM tbl_blog where id=$blog_id"); 
                        $select_blog->execute();
                        $fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC);
                        $formatted_time_post = date("d F Y", strtotime($fetch_blog["time_post"]));
                        ?>
                        <section class="blog-hero spad" style="padding: 50px 0 0px 0 ;">
                            <div class="container">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-9 text-center">
                                        <div class="blog__hero__text">
                                            <h2><?= $fetch_blog['title'] ?></h2>
                                            <ul style="margin-left:-33px;">
                                                <li style="list-style-type: none;"><?= $formatted_time_post ?></li>
                                            </ul>
                                            <p style="margin-top: 18px;"><?= $fetch_blog['content'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="content-contact container">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div>
                                    <table id="myTable" >
                                        <thead>
                                            <tr>
                                                <th style="width: 290px;">Ảnh</th>
                                                <th style="width: 280px;">Tiêu đề</th>
                                                <th>Nội dung ảnh</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $content_id=$_GET['content_id'];
                                            $update_content = $conn->prepare("SELECT * FROM tbl_img_blog where id=$content_id"); 
                                            $update_content->execute();
                                            $fetch_content = $update_content->fetch(PDO::FETCH_ASSOC);
                                            $product_id=$fetch_content['product_id'];
                                        ?>
                                        <tbody>
                                            <td>
                                                <input type="hidden" name="blog_id" value="<?= $blog_id ?>">
                                                <input type="file" name="anh"><br>
                                                <img style="width:100px; "src="../uploaded_img/<?= $fetch_content['image_blog'] ?>" alt="">
                                                <br><label for="">Sản phẩm</label>
                                                <?php
                                                    $select_category = $conn->prepare("SELECT * FROM tbl_category"); 
                                                    $select_category->execute();
                                                    if($product_id!=null){
                                                        $select_value = $conn->prepare("SELECT * FROM tbl_product where id=$product_id");
                                                        $select_value->execute();
                                                        $fetch_value = $select_value->fetch(PDO::FETCH_ASSOC);
                                                    }
                                                ?>
                                                <select name="selectOption" id="selectOption">
                                                    <?php
                                                    if($product_id!=null){
                                                        ?>
                                                        <option value="<?= $product_id ?>"><?= $fetch_value['name'] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    <option value="">null</option>
                                                    <?php
                                                    while ($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)) {
                                                        $category_id = $fetch_category['id'];
                                                    ?>
                                                        <optgroup label="<?= $fetch_category['name'] ?>">
                                                            <?php
                                                            $select_type = $conn->prepare("SELECT * FROM tbl_product where category_id=$category_id");
                                                            $select_type->execute();
                                                            while ($fetch_type = $select_type->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                <option value="<?= $fetch_type['id'] ?>">
                                                                    <?= $fetch_type['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><textarea class="form-control" required rows="6" style="width: 100%;" id="autoresize" name="title_img"><?= $fetch_content['title_img'] ?></textarea></td>
                                            <td><textarea class="form-control" required rows="6" style="width: 100%;" id="autoresize" name="content"><?= $fetch_content['content_img'] ?></textarea></td>
                                        </tbody>
                                    </table>
                                    <input class="nut-bam" style="width:100px;" type="submit" name="updatetintuc" value="Update">
                                </div>
                                <div id="buttongui"></div>
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>
