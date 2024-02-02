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
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>
            <?php 
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                echo ($id != null) ? "Sửa Nhà cung cấp" : "Thêm Nhà cung cấp"; 
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
                            $tenNCC = "";
                            $Diachi = "";
                            $Dienthoai = "";

                            if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnsubmit'])){
                                $tenNCC = $_POST['tenNCC'];
                                $Diachi = $_POST['Diachi'];
                                $Dienthoai = $_POST['Dienthoai'];                      
                                
                                if ($id != null) {
                                    $sql = "UPDATE nhacungcap SET tenNCC='$tenNCC', Diachi='$Diachi', Dienthoai='$Dienthoai' WHERE ID = $id";
                                } else {
                                    $sql = "INSERT INTO nhacungcap (tenNCC, Diachi, Dienthoai) VALUES ('$tenNCC', '$Diachi', '$Dienthoai')";
                                }

                                if ($conn) {
                                    $result = $conn->query($sql);
                                    try {
                                        if ($result) {
                                            echo "<script>alert('" . (($id != null) ? "Sửa Thành Công" : "Thêm Thành công") . "!'); window.location.href = '/qlkh/admin/nhacungcap.php';</script>";
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
                            if ($id != null) {
                                $sql = "SELECT * FROM nhacungcap WHERE ID = $id";
                                $result = $conn->query($sql);
                                if ($result->rowCount() > 0) {
                                    $row = $result->fetch(PDO::FETCH_ASSOC);
                                    $tenNCC = $row['tenNCC'];
                                    $Diachi = $row['Diachi'];
                                    $Dienthoai = $row['Dienthoai'];
                                }
                            }
                        ?>
                        <div class="container form-text">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 style="margin: auto; margin-top: 20px; text-align: center;">
                                    <?php echo ($id != null) ? "Sửa Nhà cung cấp" : "Thêm Nhà cung cấp"; ?>
                                    </h3>
                                </div>
                                <div class="col-sm-12">
                                    <!-- Form Thêm sản phẩm -->
                                    <form method="post" enctype="multipart/form-data">
                                        <!-- Tên sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtname">
                                                <span class="title">Tên nhà cung cấp</span>
                                                <span class="required">*</span>
                                            </label>
                                            <input class="form-control" id="txtname" type="text" name="tenNCC" value="<?php echo $tenNCC; ?>">
                                            
                                        </div>
                                        <!-- Mô tả sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtdesc">
                                                <span class="title">Địa chỉ</span>
                                                <span class="required">*</span>
                                            </label>
                                            <textarea class="form-control" type="text" id="Diachi" name="Diachi" rows="3"><?php echo $Diachi; ?></textarea>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="txtdesc">
                                                <span class="title">Số điện thoại</span>
                                                <span class="required">*</span>
                                            </label>
                                            <input class="form-control" type="text" id="Dienthoai" name="Dienthoai"  value="<?php echo $Dienthoai; ?>"></input>
                                            
                                        </div>
                                        <button type="submit" class="btn btn-success" name="btnsubmit">
                                        <?php echo ($id != null) ? "Sửa" : "Thêm"; ?>
                                        </button>
                                        <a href="/qlkh/admin/nhacungcap.php" class="btn btn-danger">Hủy</a>
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
    </body>
</html>
