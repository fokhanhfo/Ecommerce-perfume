<?php 
    include '../../components/connect.php';
    session_start();

    if(isset($_SESSION['admin_id'])){
        $admin_id = $_SESSION['admin_id'];
    } else {
        header('location:../admin_login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>
            <?php 
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                echo ($id != null) ? "Sửa Sản Phẩm" : "Thêm Sản Phẩm"; 
            ?>
        </title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
             .required {
                color: red;
                margin-left: 5px;
            }
            span {
                font-size: 16px;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <?php
                            $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                            $name = "";
                            $details = "";
                            $quantity = "";
                            $price = "";
                            $category_id = "";
                            $sex = "";
                            $image_1 = "";
                            $image_2 = "";
                            $image_3 = "";

                            if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnsubmit'])){
                                $name = $_POST['name'];
                                $details = $_POST['details'];
                                $quantity = $_POST['quantity'];
                                $price = $_POST['price'];
                                $category_id = $_POST['category'];
                                if(isset($_POST['gender'])){
                                    $sex = $_POST['gender'];
                                } else {
                                    $sex = "";
                                }
                                //upload img
                                $target_dir = "../../uploaded_img/";
                                if (!file_exists($target_dir)) {
                                    mkdir($target_dir, 0777, true); // Tham số 0777 là quyền truy cập của thư mục
                                }
                                // Lấy thông tin về các tệp hình ảnh từ $_FILES và di chuyển chúng vào thư mục đích
                                $image_1 = basename($_FILES["image_1"]["name"]);
                                $image_2 = basename($_FILES["image_2"]["name"]);
                                $image_3 = basename($_FILES["image_3"]["name"]);

                                // Di chuyển tệp hình ảnh vào thư mục đích
                                move_uploaded_file($_FILES["image_1"]["tmp_name"], ($target_dir . $image_1));
                                move_uploaded_file($_FILES["image_2"]["tmp_name"], ($target_dir . $image_2));
                                move_uploaded_file($_FILES["image_3"]["tmp_name"], ($target_dir . $image_3));

                                //validate
                                $errors = [];
                                if (empty($name)) {
                                    $errors['name'] = "Vui lòng nhập Tên sản phẩm.";
                                } else if (!preg_match('/^[a-zA-Z0-9\sàáạãèéẹẽìíịĩòóọõùúụũừứựửừứựữêếềệểôồốộỗăằắặẳẵâầấậẩẫđÑñ]+$/u', $name)) {
                                    $errors['name'] = "Tên sản phẩm không được chứa ký tự đặc biệt.";
                                }                                
                                if(empty($details)){
                                    $errors['details'] = "Vui lòng nhập mô tả sản phẩm.";
                                } else if (!preg_match('/^[a-zA-Z0-9\sàáạãèéẹẽìíịĩòóọõùúụũừứựửừứựữêếềệểôồốộỗăằắặẳẵâầấậẩẫđÑñ]+$/u', $name)) {
                                    $errors['details'] = "Mô tả sản phẩm không được chứa ký tự đặc biệt.";
                                }
                                if($quantity === null || $quantity < 0){
                                    $errors['quantity'] = "Số lượng sản phẩm không hợp lệ.";
                                }
                                if($price === null || $price <= 0){
                                    $errors['price'] = "Giá sản phẩm không hợp lệ.";
                                }
                                if(empty($category_id)){
                                    $errors['category'] = "Vui lòng chọn Thương hiệu.";
                                }
                                if(empty($sex)){
                                    $errors['gender'] = "Vui lòng chọn Giới tính.";
                                }
                                if(empty($_FILES['image_1']['name'])){
                                    $errors['image_1'] = "Vui lòng chọn Ảnh 1.";
                                }
                                if(empty($_FILES['image_2']['name'])){
                                    $errors['image_2'] = "Vui lòng chọn Ảnh 2.";
                                }
                                if(empty($_FILES['image_3']['name'])){
                                    $errors['image_3'] = "Vui lòng chọn Ảnh 3.";
                                }
                                if(empty($errors)){
                                    if ($id != null) {
                                        $sql = "UPDATE tbl_product SET name='$name', details='$details', quantity='$quantity', price='$price', category_id='$category_id', sex='$sex', image_1='$image_1', image_2='$image_2', image_3='$image_3' WHERE ID = $id";
                                    } else {
                                        $sql = "INSERT INTO tbl_product (name, details, quantity, price, category_id, sex, image_1, image_2, image_3) VALUES ('$name', '$details', '$quantity', '$price', '$category_id', '$sex', '$image_1', '$image_2', '$image_3')";
                                    }
    
                                    if ($conn) {
                                        $result = $conn->query($sql);
                                        try {
                                            if ($result) {
                                                echo "<script>alert('" . (($id != null) ? "Sửa Thành Công" : "Thêm Thành công") . "!'); window.location.href = '/qlkh/admin/product.php';</script>";
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
                                }
                            }
                            if ($id != null) {
                                $sql = "SELECT * FROM tbl_product WHERE ID = $id";
                                $result = $conn->query($sql);
                                if ($result->rowCount() > 0) {
                                    $row = $result->fetch(PDO::FETCH_ASSOC);
                                    $name = $row['name'];
                                    $details = $row['details'];
                                    $quantity = $row['quantity'];
                                    $price = $row['price'];
                                    $category_id = $row['category_id'];
                                    $sex = $row['sex'];
                                    $image_1 = $row['image_1'];
                                    $image_2 = $row['image_2'];
                                    $image_3 = $row['image_3'];
                                }
                            }
                        ?>
                        <div class="container form-text">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2 style="margin: auto; margin-top: 20px; text-align: center;">
                                        <?php echo ($id != null) ? "Sửa Sản Phẩm" : "Thêm Sản Phẩm"; ?>
                                    </h2>
                                </div>
                                <div class="col-sm-12">
                                    <form method="POST" enctype="multipart/form-data">
                                        <!-- Tên sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtname">
                                                <span class="title">Tên sản phẩm</span>
                                                <span class="required">*</span>
                                            </label>
                                            <input class="form-control" id="name" type="text" name="name" value="<?php echo $name; ?>">
                                            <?php if(isset($errors['name'])) { ?>
                                                <span class="error-message" style="color: red;"><?php echo $errors['name']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- Mô tả sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtdesc">
                                                <span class="title">Mô tả sản phẩm</span>
                                                <span class="required">*</span>
                                            </label>
                                            <textarea class="form-control" id="details" name="details" rows="3"><?php echo $details; ?></textarea>
                                            <?php if(isset($errors['details'])) { ?>
                                                <span class="error-message" style="color: red;"><?php echo $errors['details']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- Số lượng sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtquantity">
                                                <span class="title">Số lượng sản phẩm</span>
                                                <span class="required">*</span>
                                            </label>
                                            <input class="form-control" type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>">
                                            <?php if(isset($errors['quantity'])) { ?>
                                                <span class="error-message" style="color: red;"><?php echo $errors['quantity']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- Giá sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtprice">
                                                <span class="title">Giá sản phẩm</span>
                                                <span class="required">*</span>
                                            </label>
                                            <input class="form-control" type="number" id="price" name="price" value="<?php echo $price; ?>">
                                            <?php if(isset($errors['price'])) { ?>
                                                <span class="error-message" style="color: red;"><?php echo $errors['price']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- Loại sản phẩm -->
                                        <div class="form-group">
                                            <?php
                                            $resultCategories = $conn->query("SELECT * FROM tbl_category");
                                            ?>
                                            <label>
                                                <span class="title">Thương hiệu</span>
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-control" name="category">
                                                <option value="" selected>-- Chọn --</option>
                                                <?php while ($row = $resultCategories->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <option value="<?= $row['id'] ?>" <?php if ($category_id == $row['id']) echo 'selected'; ?>>
                                                        <?= $row['name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <?php if(isset($errors['category'])) { ?>
                                                <span class="error-message" style="color: red;"><?php echo $errors['category']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- Giới tính -->
                                        <div class="form-group">
                                            <label><span class="title">Giới tính:</span></label>
                                            <input type="radio" name="gender" value="1" id="nam" style="margin-left: 10px" <?php if ($sex == 1) echo 'checked'; ?>>
                                            <label for="nam">Nam</label>
                                            <input type="radio" name="gender" value="2" id="nu" style="margin-left: 10px" <?php if ($sex == 2) echo 'checked'; ?>>
                                            <label for="nu">Nữ</label>
                                            <input type="radio" name="gender" value="3" id="nam_va_nu" style="margin-left: 10px" <?php if ($sex == 3) echo 'checked'; ?>>
                                            <label for="nam_va_nu">Nam và nữ</label>
                                            <?php if(isset($errors['gender'])) { ?>
                                                <br><span class="error-message" style="color: red;"><?php echo $errors['gender']; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- Ảnh 1 -->
                                        <div class="form-group">
                                            <label for="txtpic">
                                                <span class="title">Ảnh 1</span>
                                                <span class="required">*</span>
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" id="image_1" name="image_1" accept=".png,.gif,.jpg,.jpeg">
                                                <?php if(isset($errors['image_1'])) { ?>
                                                    <br><span class="error-message" style="color: red;"><?php echo $errors['image_1']; ?></span>
                                                <?php } ?>
                                                <?php if (!empty($image_1)) { ?>
                                                    <p>Ảnh hiện tại: <?php echo $image_1; ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Ảnh 2 -->
                                        <div class="form-group">
                                            <label for="txtpic">
                                                <span class="title">Ảnh 2</span>
                                                <span class="required">*</span>
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" id="image_2" name="image_2" accept=".png,.gif,.jpg,.jpeg">
                                                <?php if(isset($errors['image_2'])) { ?>
                                                    <br><span class="error-message" style="color: red;"><?php echo $errors['image_2']; ?></span>
                                                <?php } ?>
                                                <?php if (!empty($image_2)) { ?>
                                                    <p>Ảnh hiện tại: <?php echo $image_2; ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Ảnh 3 -->
                                        <div class="form-group">
                                            <label for="txtpic">
                                                <span class="title">Ảnh 3</span>
                                                <span class="required">*</span>
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" id="image_3" name="image_3" accept=".png,.gif,.jpg,.jpeg">
                                                <?php if(isset($errors['image_3'])) { ?>
                                                    <br><span class="error-message" style="color: red;"><?php echo $errors['image_3']; ?></span>
                                                <?php } ?>
                                                <?php if (!empty($image_3)) { ?>
                                                    <p>Ảnh hiện tại: <?php echo $image_3; ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Nút submit -->
                                        <button type="submit" class="btn btn-success" name="btnsubmit">
                                            <?php echo ($id != null) ? "Sửa" : "Thêm"; ?>
                                        </button>
                                        <a href="/qlkh/admin/product.php" class="btn btn-danger">Hủy</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include ('../component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../component/admin_script.php'); ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Lấy giá trị của ảnh từ cơ sở dữ liệu
                var image1Name = "<?php echo $image_1; ?>";
                var image2Name = "<?php echo $image_2; ?>";
                var image3Name = "<?php echo $image_3; ?>";

                // Thiết lập giá trị của input type "file" nếu có dữ liệu từ cơ sở dữ liệu
                document.getElementById("image_1").value = image1Name;
                document.getElementById("image_2").value = image2Name;
                document.getElementById("image_3").value = image3Name;
            });
        </script>
    </body>
</html>
