<?php 
    include '../components/connect.php';
    session_start();

    // if(isset($_SESSION['admin_id'])){
    //     $admin_id = $_SESSION['admin_id'];
    // } else {
    //     header('location:admin_login.php');
    // }
    $sql = "SELECT * FROM `tbl_product` WHERE 1";



    if(isset($_GET['category'])){
        $category_id = $_GET['category'];
        $sql .= " AND category_id = $category_id";
    }

    if(isset($_GET['sex'])){
        $sex = $_GET['sex'];
        $sql .= " AND sex = '$sex'";
    }


    if (isset($_GET['id_product'])) {
        $id_product = $_GET['id_product'];
    
        // Kiểm tra nếu có phiếu nhập chưa hoàn thành (status=0)
        $select_phieunhap = $conn->prepare("SELECT * FROM `phieunhap` WHERE status=0");
        $select_phieunhap->execute();
        $fetch_phieunhap = $select_phieunhap->fetch(PDO::FETCH_ASSOC);
    
        // Nếu không có, tạo mới một phiếu nhập
        if ($select_phieunhap->rowCount() < 1) {
            $insert_phieunhap = $conn->prepare("INSERT INTO `phieunhap`(`id`) VALUES ('')");
            $insert_phieunhap->execute();
    
            // Lấy thông tin phiếu nhập mới tạo
            $select_phieunhap = $conn->prepare("SELECT * FROM `phieunhap` WHERE status=0");
            $select_phieunhap->execute();
            $fetch_phieunhap = $select_phieunhap->fetch(PDO::FETCH_ASSOC);
        }

        $check_maHang = $conn->prepare("SELECT * FROM `ctphieunhap` WHERE `maHang` = ? AND `maBL` = ?");
        $check_maHang->execute([$id_product, $fetch_phieunhap['id']]);

        // Nếu đã có, thông báo và không thêm mới
        if ($check_maHang->rowCount() > 0) {
            echo '<script>
            alert("Đã có");
            </script>';
        }else{
            $insertCT_phieunhap = $conn->prepare("INSERT INTO `ctphieunhap`(`maHang`,`maBL`) VALUES (?,?)");
            $insertCT_phieunhap->execute([$id_product, $fetch_phieunhap['id']]);
            if ($insertCT_phieunhap) {
                echo '<script>
                alert("Thêm thành công");
                </script>';
            }
        }
    
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <?php include ('../admin/component/admin_head.php'); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        .container {
            display: flex;
        }

        .left-column {
            width: 20%;
            padding: 10px;
            background-color: #f0f0f0;
            height: 620px;
        }

        .right-column {
            width: 80%;
            padding: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 3px;
            transition: background-color 0.3s ease;
            opacity: 0.5;
        }

        ul li a:hover {
            background-color: #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        .deselected {
            color: red;
        }
    </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container">
                        <div class="left-column">
                            <input style="width:165px;" type="text" id="tuKhoa" placeholder="Nhập tên...">
                            <p>CATEGORYS</p>
                                <ul>
                                <?php
                                    $select_category = $conn->prepare("SELECT * FROM `tbl_category` WHERE 1"); 
                                    $select_category->execute();
                                    while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <li><a <?= (isset($_GET['category']) && $_GET['category'] == $fetch_category['id']) ? 'style="color: red;opacity: 1;"' : '' ?> href="#" onclick="updateUrl('category','<?= $fetch_category['id'] ?>')"><?= $fetch_category['name'] ?></a></li>
                                <?php
                                    }
                                ?>
                                    <li><a href="#" onclick="removeUrlParam('category')">Bỏ chọn</a></li>
                                </ul>
                            <p>SEX</p>
                                <ul>
                                    <li><a <?= (isset($_GET['sex']) && $_GET['sex'] == 1) ? 'style="color: red;opacity: 1;"' : '' ?> href="#" onclick="updateUrl('sex','1', this)">Nam</a></li>
                                    <li><a <?= (isset($_GET['sex']) && $_GET['sex'] == 2) ? 'style="color: red;opacity: 1;"' : '' ?> href="#" onclick="updateUrl('sex','2', this)">Nữ</a></li>
                                    <li><a <?= (isset($_GET['sex']) && $_GET['sex'] == 3) ? 'style="color: red;opacity: 1;"' : '' ?> href="#" onclick="updateUrl('sex','3', this)">Basic</a></li>
                                    <li><a href="#" onclick="removeUrlParam('sex')">Bỏ chọn</a></li>
                                </ul>
                        </div>

                        <div class="right-column">
                            <h2 style="display: inline;" >Hàng hóa</h2>
                            <a style="display: inline;float: right;" href="ctphieunhap.php">
                                <i class="fas fa-shopping-cart"></i>
                                <?php
                                    $select_ctphieu = $conn->prepare("SELECT * FROM `ctphieunhap` WHERE 1 and status=0");
                                    $select_ctphieu->execute();
                                ?>
                                <div style="display: inline-block;">
                                    <sup><?= $select_ctphieu->rowCount() ?></sup>
                                </div>
                            </a>
                            <form action="" method="post">
                            <table id="bangDuLieu">
                                <thead>
                                    <tr>
                                        <th>Sản phảm</th>
                                        <th>Ảnh</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $select_product = $conn->prepare($sql);
                                        $select_product->execute();
                                        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                    <tr>
                                        <td><?= $fetch_product['name'] ?></td>
                                        <td><img style="width:100px;" src="../uploaded_img/<?= $fetch_product['image_1'] ?>" alt=""></td>
                                        <td>
                                            <?php
                                                $select_pPhieu = $conn->prepare("SELECT * FROM `ctphieunhap` WHERE maHang = ? and status=0");
                                                $select_pPhieu->execute([$fetch_product['id']]);
                                                if($select_pPhieu->rowCount()>0){
                                            ?>
                                                <p>Đã nhập</p>
                                            <?php
                                            }else{
                                                ?>
                                                <a href="nhaphang.php?id_product=<?= $fetch_product['id'] 
                                                . (isset($_GET['sex'])? "&sex=".$_GET['sex'] : '') 
                                                . (isset($_GET['category'])? "&category=".$_GET['category'] : ''); ?>" 
                                                class="read-more" >Nhập hàng</a>
                                                <?php
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                    <?php
                                        }
                                    ?>
                                    <!-- Các dòng khác nếu cần -->
                                </tbody>
                            </table>
                            <form>
                        </div>
                    </div>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
        <script>
            function updateUrl(param, value) {
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set(param, value);
                window.location.href = currentUrl.toString();
            }
            function removeUrlParam(param) {
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete(param);
                window.location.href = currentUrl.toString();
            }
            document.getElementById("tuKhoa").addEventListener("input", function() {
                var tuKhoa = chuanHoaChuoi(this.value);
                var bangDuLieu = document.getElementById("bangDuLieu");
                var dongDuLieu = bangDuLieu.getElementsByTagName("tr");

                for (var i = 1; i < dongDuLieu.length; i++) {
                    var cells = dongDuLieu[i].getElementsByTagName("td")[0];
                    var hienThi = false;

                    if (cells) {
                        var cellValue = chuanHoaChuoi(cells.textContent || cells.innerText);
                        if (cellValue.includes(tuKhoa)) {
                            hienThi = true;
                        }
                    }

                    dongDuLieu[i].style.display = hienThi ? "" : "none";
                }
            });

            function chuanHoaChuoi(chuoi) {
                return chuoi.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            }
        </script>
    </body>
</html>
