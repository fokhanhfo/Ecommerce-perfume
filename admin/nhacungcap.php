<?php 
    include '../components/connect.php';
    session_start();

    // if(isset($_SESSION['admin_id'])){
    //     $admin_id = $_SESSION['admin_id'];
    // } else {
    //     header('location:admin_login.php');
    // }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include ('../admin/component/admin_head.php'); ?>
        <style>
            .table td, .table th {
                vertical-align: middle;
                text-align: center;
            }
            /* button */
            button {
                border: none;
                background-color: transparent;
                cursor: pointer; 
            }
            button i {
                color: #007bff;
                transition: color 0.3s ease;
            }
            button:hover i {
                color: #0056b3;
            }

            .product-pagination-container {
                text-align: center;
            }

            .pagination-link {
                display: inline-block;
                font-size: 16px;
                font-weight: 700;
                color: #000000;
                height: 30px;
                width: 30px;
                border: 1px solid #000000;
                border-radius: 50%;
                line-height: 30px;
                text-align: center;
                background-color: #ffffff; /* Màu nền mặc định */
                margin: 0 5px;
                text-decoration: none;
                transition: background-color 0.3s, color 0.3s; /* Hiệu ứng chuyển động màu nền và chữ khi hover */
            }

            .pagination-link.active {
                background-color: #000000;
                color: #ffffff;
                border-color: #000000;
            }

            .pagination-link:hover {
                background-color: #000000;
                color: #ffffff;
                border-color: #000000;
            }
        </style>
    </head>
    <?php
        $sql = "SELECT * FROM nhacungcap WHERE (1=1) ";
        $whereClasue = " ";
        $checkValue = 0;
    ?>
    <body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="/qlkh/admin/index.php">Admin</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="GET">
                <?php 
                $searchValue = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';  
                ?>
                <div class="input-group">
                    <input class="form-control" value="<?php echo $searchValue; ?>" name="search" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['search']) && !empty($_GET['search'])) {
                    $value = trim($_GET['search']);
                    if (preg_match('/^[\p{L}0-9\s]+$/u', $value)) {
                        $whereClasue .= " AND (tenNCC LIKE '%$value%' OR Diachi LIKE '%$value%') ";
                        $checkValue = 1;
                    }
                }
            ?>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                    <div class="container-fluid px-4">
                        <div style="margin: auto; margin-top: 20px; text-align: center;">
                            <h3>Nhà cung cấp</h3>
                        </div>
                        <?php 
                            $item_per_page = !empty($_GET['per_page'])?$_GET['per_page']:3;
                            $current_page = !empty($_GET['page'])?$_GET['page']:1;
                            $offset = ($current_page -  1) * $item_per_page;
                            $totalRecords;
                            $totalPages;
                            $totalPages = ceil(($conn->query("SELECT COUNT(*) FROM nhacungcap WHERE (1=1) " . $whereClasue)->fetchColumn()) / $item_per_page);
                        ?> 
                        <?php
                            $sql .= $whereClasue;
                            $sql .= " LIMIT " . $item_per_page . " OFFSET " . $offset;
                            $result = $conn->query($sql);
                            $totalRecords = $result->rowCount();
                            if ($result && $result->rowCount() > 0) {
                        ?>
                        <p style="text-align: right; color: red;">Hiển thị <?=($conn->query("SELECT COUNT(*) FROM nhacungcap WHERE (1=1) " . $whereClasue)->fetchColumn())?> nhà cung cấp</p>
                        <table class="table" style="margin-top: 20px;">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên nhà cung cấp</th>
                                    <th scope="col">Địa chỉ</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $STT = $offset + 1;
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <th scope="row"><?=$STT?></th>
                                    <td><?=$row['tenNCC']?></td>
                                    <td><?=$row['Diachi']?></td>
                                    <td><?=$row['Dienthoai']?></td>
                                    <td>
                                        <a href="../admin/nhacungcap/saveOrUpdate.php?id=<?=$row['id']?>" style="display: inline; margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                        <form method='POST' style="display: inline;">
                                            <input type='hidden' name='delete_id' value='<?=$row['id']?>'>
                                            <button type='submit' name='btnXoa' onclick='return confirm("Bạn có chắc chắn muốn xóa nhà cung cấp này?")'><i style="color: red" class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- ở đây -->
                                <?php
                                    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnXoa'])) {
                                        $idDelete = $_POST['delete_id'];
                                        $sqlGetProducts = "SELECT id FROM tbl_product WHERE category_id = $idDelete";
                                        $resultProducts = $conn->query($sqlGetProducts);
                                    
                                        if ($resultProducts) {
                                            $sqlDeleteCategory = "DELETE FROM nhacungcap WHERE id = $idDelete";
                                            if ($conn->query($sqlDeleteCategory)) {
                                                echo "<script>alert('Xóa thành công!'); window.location.href = '/qlkh/admin/nhacungcap.php';</script>";
                                            } else {
                                                echo "<script>alert('Lỗi khi xóa dữ liệu: " . $conn->error . "');</script>";
                                            }
                                        } else {
                                            echo "<script>alert('Lỗi khi lấy danh sách nhà cung cấp: " . $conn->error . "');</script>";
                                        }
                                    }                                    
                                ?>
                                <?php
                                    $STT++;
                                    }
                                ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-pagination-container">
                                    <?php if ($current_page > 3) { 
                                        $first_page = 1;
                                        $link = "?per_page=$item_per_page&page=$first_page";
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $value = $_GET['search'];
                                            $link .= "&search=$value";
                                        }
                                    ?>
                                    <a class="pagination-link active" href="<?= $link ?>">F</a>
                                    <?php } ?>
                                    <?php if ($current_page > 1) { 
                                        $prev_page = $current_page - 1;
                                        $link = "?per_page=$item_per_page&page=$prev_page";
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $value = $_GET['search'];
                                            $link .= "&search=$value";
                                        }
                                    ?>
                                    <a class="pagination-link active" href="<?= $link ?>"><</a>
                                    <?php } ?>
                                    <?php
                                    for ($num = 1; $num <= $totalPages; $num++) {
                                        $link = "?per_page=$item_per_page&page=$num";
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $value = $_GET['search'];
                                            $link .= "&search=$value";
                                        }
                                        if ($num != $current_page) {
                                            if ($num > $current_page - 2 && $num < $current_page + 2) {
                                                echo "<a class='pagination-link active' href='$link'>$num</a>";
                                            }
                                        } else {
                                            echo "<a class='pagination-link active' href='$link' style='background-color: #ffffff; color: #000000; border-color: #111111;'>$num</a>";
                                        }
                                    }
                                    ?>
                                    <?php if ($current_page < $totalPages - 1) { 
                                        $next_page = $current_page + 1;
                                        $link = "?per_page=$item_per_page&page=$next_page";
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $value = $_GET['search'];
                                            $link .= "&search=$value";
                                        }
                                    ?>
                                    <a class="pagination-link active" href="<?= $link ?>">></a>
                                    <?php } ?>
                                    <?php if ($current_page < $totalPages - 3) { 
                                        $end_page = $totalPages;
                                        $link = "?per_page=$item_per_page&page=$end_page";
                                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                                            $value = $_GET['search'];
                                            $link .= "&search=$value";
                                        }
                                    ?>
                                    <a class="pagination-link active" href="<?= $link ?>">L</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php
                            } else {
                                echo "Không có dữ liệu để hiển thị.";
                            }
                        ?>
                    </div>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>