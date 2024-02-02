<?php 
    include '../components/connect.php';
    session_start();

    if(isset($_SESSION['admin_id'])){
        $admin_id = $_SESSION['admin_id'];
    } else {
        header('location:admin_login.php');
    }
    if(isset($_GET['delete_phieunhap'])){
        $id_phieu=$_GET['delete_phieunhap'];
        $delete_product = $conn->prepare("DELETE FROM `phieunhap` WHERE id = ?");
        $delete_product->execute([$id_phieu]);
        if($delete_product){
            echo "<script>
            alert('Xóa thành công $id_phieunhap');
            </script>";
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="/qlkh/admin/index.php">Admin</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <?php
            if (isset($_SESSION['admin_id'])) {
                $adname = isset($_SESSION['adname']) ? $_SESSION['adname'] : '';
            }
            ?>
            <form method="post" class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input name="search_id" class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button name="button_search" class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= (isset($_SESSION['admin_id'])) ? $adname : '' ?><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="admin_update.php">Account</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><?= (isset($_SESSION['admin_id'])) ? '<a class="dropdown-item" href="admin_logout.php">Logout</a>' : '<a class="dropdown-item" href="admin_login.php">Login</a>' ?></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                <h2>Danh sách phiếu nhập</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Nhân viên</th>
                                <th>Nhà cung cấp</th>
                                <th>Ngày nhập</th>
                                <th>Tổng tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql="SELECT * FROM `phieunhap` WHERE 1 and status=1";
                                if(isset($_POST['button_search'])){
                                    $search_id=$_POST["search_id"];
                                    if(!empty($search_id)){
                                        $sql .= " AND id=$search_id";
                                    }else{
                                        $sql="SELECT * FROM `phieunhap` WHERE 1 and status=1";
                                    }
                                }
                                $select_phieunhap = $conn->prepare($sql); 
                                $select_phieunhap->execute();
                                while($fetch_phieunhap = $select_phieunhap->fetch(PDO::FETCH_ASSOC)){
                            ?>
                            <tr>
                                <td><?= $fetch_phieunhap['id'] ?></td>
                                <?php
                                $select_admin = $conn->prepare("SELECT * FROM `tbl_admin` WHERE 1 and id=?"); 
                                $select_admin->execute([$fetch_phieunhap['id_admin']]);
                                $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <td><?= $fetch_admin['adname'] ?></td>
                                <?php
                                $select_NCC = $conn->prepare("SELECT * FROM `nhacungcap` WHERE 1 and id=?"); 
                                $select_NCC->execute([$fetch_phieunhap['MaNCC']]);
                                $fetch_NCC = $select_NCC->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <td><?= $fetch_NCC['tenNCC'] ?></td>
                                <td><?= $fetch_phieunhap['ngayNhap'] ?></td>
                                <td><?= number_format($fetch_phieunhap['Tongtien']) ?></td>
                                <td>
                                    <a href="chitietphieu.php?id_phieunhap=<?= $fetch_phieunhap['id']; ?>" style="display: inline;width:16px;height:16px;" name="delete_product">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?= (isset($_SESSION['admin_id'])&&$_SESSION['adname']=='a') ? '<a href="phieunhap.php?delete_phieunhap=' . $fetch_phieunhap["id"] . '" style="display: inline; width: 16px; height: 16px;"><i class="fas fa-trash"></i></a>' : '' ?>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>
