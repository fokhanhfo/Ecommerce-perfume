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
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">NHÓM 8</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">9đ</li>
                        </ol>
                        
                        <!-- Thêm sửa đoạn này -->
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    <a href="/qlkh/admin/product.php">Số sản phẩm</a>
                                                </div>
                                                <div>
                                                <p><?=($conn->query("SELECT COUNT(*) FROM tbl_product WHERE (1=1) ")->fetchColumn())?> sản phẩm</p>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    <a href="/qlkh/admin/order.php">Tổng tiền nhập</a>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <p><?=number_format(($conn->query("SELECT SUM(Tongtien) FROM phieunhap WHERE (1=1) ")->fetchColumn()), 0, ',', '.')?>đ</p>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    <a href="/qlkh/admin/order.php">Tổng doanh thu</a>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <p><?=number_format(($conn->query("SELECT SUM(total_price) FROM tbl_order WHERE (1=1) ")->fetchColumn()), 0, ',', '.')?>đ</p>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    <a href="/qlkh/admin/order.php?status=3">Đơn giao thành công</a>
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                            <p><?=($conn->query("SELECT COUNT(*) FROM tbl_order WHERE (status = 2) ")->fetchColumn())?> đơn</p>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="progress progress-sm mr-2">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                style="width: <?=(($conn->query("SELECT COUNT(*) FROM tbl_order WHERE (status = 2) ")->fetchColumn())*100/($conn->query("SELECT COUNT(*) FROM tbl_order ")->fetchColumn()))?>%" aria-valuenow="50" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    <a href="/qlkh/admin/order.php?status=1">Đơn đợi xác nhận</a>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <p><?=($conn->query("SELECT COUNT(*) FROM tbl_order WHERE (status = 0) ")->fetchColumn())?> đơn</p>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                // Thực hiện câu truy vấn SQL
                                $sql = "SELECT 
                                            op.product_id,
                                            p.id as product_id,
                                            p.name,
                                            SUM(op.quantity) as total_quantity
                                        FROM 
                                            tbl_order_product AS op
                                        JOIN 
                                            tbl_product AS p ON p.id = op.product_id
                                        GROUP BY 
                                            op.product_id
                                        ORDER BY 
                                            total_quantity DESC
                                        LIMIT 3";

                                $result = $conn->query($sql);

                                // Biến để lưu trữ danh sách sản phẩm
                                $productList = '';

                                // Kiểm tra và xây dựng danh sách sản phẩm
                                if ($result->rowCount() > 0) {
                                    $productList .= '<ul>'; // Mở thẻ ul

                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $productId = $row['product_id'];
                                        $productName = $row['name'];
                                        $totalQuantity = $row['total_quantity'];

                                        // Thêm mỗi sản phẩm vào danh sách với id sản phẩm trong URL
                                        $productList .= '<li>
                                                            <a href="/qlkh/admin/product.php?id='.$productId.'">'.$productName.'</a>
                                                            <span>'.$totalQuantity.' sản phẩm đã bán</span>
                                                        </li>';
                                    }

                                    $productList .= '</ul>'; // Đóng thẻ ul
                                } else {
                                    // Nếu không có sản phẩm
                                    $productList = '<p>Không có sản phẩm nào.</p>';
                                }

                                // Hiển thị div và danh sách sản phẩm
                                echo '<div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    <a href="/qlkh/admin/product.php">Sản phẩm bán chạy</a>
                                                </div>
                                                '.$productList.'
                                            </div>
                                        </div>
                                    </div>';
                                ?>
                        </div>
                    </div>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>
