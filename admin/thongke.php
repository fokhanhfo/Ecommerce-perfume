<?php 
    include '../components/connect.php';
    session_start();




    
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
        </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
            <main>
                <form action="" method="POST">
                    <div class="container-fluid px-4">
                    <div class="container-fluid px-4">
                        <div style="margin: auto; margin-top: 20px; text-align: center;">
                            <h3>Đơn hàng</h3>
                        </div>
                        <form action="search.php" method="post">
                            <?php
                            $sql = "SELECT MIN(booking_date) AS min_date FROM tbl_order WHERE status = 2";
                            $result = $conn->query($sql);
                            $fetch_date_min = $result->fetch(PDO::FETCH_ASSOC);
                            $min_date = $fetch_date_min['min_date'];

                            $sql = "SELECT MAX(booking_date) AS max_date FROM tbl_order WHERE status = 2";
                            $result = $conn->query($sql);
                            $fetch_date_max = $result->fetch(PDO::FETCH_ASSOC);
                            $max_date = $fetch_date_max['max_date'];
                            ?>
                            Từ ngày: <input type="date" name="start_date" value="<?= $min_date ?>">
                            Đến ngày: <input type="date" name="end_date" value="<?= $max_date ?>">
                            <input type="submit" value="Tìm kiếm" name="search_order">
                            <input type="submit" value="Tất cả" name="all_orders">
                        </form>


                        <?php
                            
                            $sql= "SELECT * from tbl_order where status=2";
                            $result = $conn->query($sql);
                            if ($result && $result->rowCount() > 0) {
                        ?>

                        <table class="table" style="margin-top: 20px;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã đơn hàng</th>
                                    <th>Người nhận</th>
                                    <th>Số điện thoại</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $STT=1;
                                    $doanhthu=0;
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {$doanhthu+=$row['total_price'];
                                        
                                ?>
                                <tr>
                                    <td><?= $STT ?></td>
                                    <td> <?=$row['id']; ?> </td>
                                    <td> <?=$row['order_name']; ?> </td>
                                    <td> <?=$row['phone']; ?></td>
                                    <td> <?=date('d-m-Y', strtotime( $row['booking_date']));?></td>
                                    <td> <?= number_format($row['total_price'] )?><sup>đ</sup> </td>
                                    <td>
                                        <a href="order-details.php?id=<?=$row['id']; ?>" name="update"><i class="fa-sharp fa-solid fa-pen-to-square" style="color: #288ae6;"></i></a>
                                        <!-- <a href="order-details.php?id=<=$row['id']; ?>" name="delete" onclick="return confirm('delete this cart item')"><i class="fa-solid fa-trash" style="color: #ed7c12;"></i></a> -->
                                    </td>
                                </tr>
                                <?php
                                $STT++;
                                }
                                ?>
                                <tr>
                                    <td>Số đơn hàng : <?= $STT-1 ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Doanh thu :<?= number_format($doanhthu) ?><sup>đ</sup></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        <?php
                            } else {
                                echo "Không có dữ liệu để hiển thị.";
                            }
                        ?>
                    </div>
                    </form>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>