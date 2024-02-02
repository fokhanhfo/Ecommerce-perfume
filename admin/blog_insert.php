<?php
include '..\components\connect.php';

session_start();

    // if(isset($_SESSION['admin_id'])){
    //     $admin_id = $_SESSION['admin_id'];
    // } else {
    //     header('location:../admin_login.php');
    // }

// if(isset($_SESSION['user_id'])){
//    $user_id = $_SESSION['user_id'];
// }else{
//    $user_id = '';
// };
if (isset($_POST['themtintuc'])) {
    $title=$_POST['title'];
    $content_blog=$_POST['content_blog'];
    $file_name = $_FILES["file_anh_blog"]["name"];
    $file_temp = $_FILES["file_anh_blog"]["tmp_name"];
    $select_blog = $conn->prepare("SELECT * FROM tbl_blog"); 
    $select_blog->execute();
    $fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC);
    $errors = [];
    // if (!preg_match('/^[a-zA-Z0-9\sàáạãèéẹẽìíịĩòóọõùúụũưừứựửừứựữêếềệểôồốộỗăằắặẳẵâầấậẩẫđÑñ]+$/u', $title)) {
    //     $errors['title'] = "Tiêu đề không được chứa ký tự đặc biệt.";
    // }
    // if (!preg_match('/^[a-zA-Z0-9\sàáạãèéẹẽìíịĩòóọõùúụũưừứựửừứựữêếềệểôồốộỗăằắặẳẵâầấậẩẫđÑñ]+$/u', $content_blog)) {
    //     $errors['content_blog'] = "Mô tả không được chứa ký tự đặc biệt.";
    // }
    if(empty($errors)){
        $bolen=true;
        while ($fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC)){
            if($fetch_blog['title'] == $title){
                $bolen=false;
            }
        }
        if($bolen==false){
            echo '<script>alert("Tiêu đề trùng với blog cũ!");window.location.href ="blog_insert.php";</script>';
        }else{
            $target_directory = "../uploaded_img/"; // Thư mục lưu trữ ảnh
            $target_file = $target_directory . $file_name;
            $luu = move_uploaded_file($file_temp, $target_file);
            $sql = "INSERT INTO tbl_blog (title,blog_image,content) VALUES (?, ?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title,$file_name,$content_blog]);
        }
        $newlyInsertedID = $conn->lastInsertId();
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
            if($product_qc[$i]==null){
            // Thêm dữ liệu vào cơ sở dữ liệu
            $sql = "INSERT INTO tbl_img_blog (id_blog,title_img ,image_blog, content_img,product_id) VALUES (?, ?, ?, ?,null)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$newlyInsertedID,$title_img[$i] ,$file_anh[$i], $content[$i]]);
            }else{
                $sql = "INSERT INTO tbl_img_blog (id_blog,title_img ,image_blog, content_img,product_id) VALUES (?, ?, ?, ?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$newlyInsertedID,$title_img[$i] ,$file_anh[$i], $content[$i],$product_qc[$i]]);
            }
        }

        if ($stmt) {
            echo '<script>alert("Thêm thành công tin tức!");window.location.href ="blog.php";</script>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Thêm tin tức</title>
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
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="add_blog" style="padding: 25px;">
                            <h3 style="text-align: center;">Thêm mới tin tức</h3>
                            <div class="input-container">
                                <label for="">Tiêu đề *</label>
                                <input class="form-control" type="text" required id="tieuDe" name="title" placeholder="Nhập tiêu đề">
                                <?php if(isset($errors['title'])) { ?>
                                    <span class="error-message" style="color: red;"><?php echo $errors['title']; ?></span>
                                <?php } ?>
                            </div>
                            <div class="input-container">
                                <label for="">Lời mở đầu *</label>
                                <textarea class="form-control" required id="tieuDe" name="content_blog" placeholder="Nhập lời mở đầu" cols="30" rows="4"></textarea>
                                <?php if(isset($errors['content_blog'])) { ?>
                                    <span class="error-message" style="color: red;"><?php echo $errors['content_blog']; ?></span>
                                <?php } ?>
                            </div>
                            <div class="input-container">
                                <input style="width: 24%;" type="file" required id="anh" accept="image/*" name="file_anh_blog">
                            </div>
                            <div class="input-container">
                                <label for="rowCountInput">Nhập số nội dung muốn thêm</label>
                                <input oninput="handleInput(this)"; class="form-control" required type="number" id="rowCountInput" min="1" max="10" value="1">
                            </div>
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
                            </div>
                            <input id="submit-button" class="nut-bam" style="width:100px;" type="submit" name="themtintuc" value="Thêm">
                        </div>
                    </form>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
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
                                    <td><textarea class="form-control" required rows="4" style="height: 64px; width: 100%;" id="autoresize${i}" name="title_img${i}"></textarea></td>
                                    <td><textarea class="form-control" required rows="4" style="height: 64px; width: 100%;" id="autoresize${i}" name="content${i}"></textarea>
                                    </td>`;
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
    </body>
</html>
