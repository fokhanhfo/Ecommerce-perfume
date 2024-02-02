<?php 
    include '../components/connect.php';
    session_start();
    $sql = "SELECT * FROM tbl_order WHERE(1=1) ";
    $status = " ";
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $status = $_GET['status'];
        echo $status;
        $sql .= " AND status=($status - 1)";
    }
    $sql .= " ORDER BY id DESC ";
    $result = $conn->query($sql);
    if(isset($_POST['search'])){
        // tìm kiếm status
        if(!empty($_POST['select'])){
            $chon=$_POST['select'];
            if($chon=="1"){
                $sql= "SELECT * from tbl_order where status=($chon-1)";
                $result = $conn->query($sql);
            }
            if($chon=="2"){
                $sql= "SELECT * from tbl_order where status=($chon-1)";
                $result = $conn->query($sql);
            }
            if($chon=="3"){
                $sql= "SELECT * from tbl_order where status=($chon-1)";
                $result = $conn->query($sql);
            }
            if($chon=="4"){
                $sql= "SELECT * from tbl_order where status=($chon-1)";
                $result = $conn->query($sql);
            }
        }
        // tìm kiếm theo mục chọn: mã, tên
        elseif(!empty($_POST['chon'])){
            $chon=$_POST['chon'];
            $timkiem= $_POST['txttimkiem'];
            if($chon=="order_id"){
                $sql= "SELECT * from tbl_order where id LIKE '%$timkiem%' ";
                $result = $conn->query($sql);
            }
            if($chon=="order_name"){
                $sql= "SELECT * from tbl_order where order_name LIKE '%$timkiem%' ";
                $result = $conn->query($sql);
            }
        }
        else{
        // tìm kiếm theo ngày đặt
            if(!empty($_POST['txtdate'])){
                $date=$_POST['txtdate'];
                $sql= "SELECT * from tbl_order where booking_date='$date'";
                $result = $conn->query($sql);
            }
        }
    }
    $count=$result->rowCount();
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
            .back:hover{
            /* lop gia :hover di chuot se doi mau */
            color:  #da2b56;
            text-decoration: none;
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
                        <div style="margin: 20px auto 20px auto;  text-align: center;">
                            <h3><a href="order.php" class="back">Đơn hàng</a></h3>
                        </div>
                        <!-- tìm kiếm -->
                        <div>
                            <select name="chon" >
                                <option selected="selected" value="">Chọn</option>
                                <option value="order_id">Mã đơn hàng</option>
                                <option value="order_name">Người nhận</option>
                            </select>
                            <input type="text" name="txttimkiem">        
                            <span> <?php if(isset($error['chon'])) echo $error['chon']; ?> </span> 

                            <select name="select" >
                                <option selected="selected" value="">Trạng thái</option>
                                <option value="1">In processing</option>
                                <option value="2">Delivering</option>
                                <option value="3">Received</option>
                                <option value="4">Canceled</option>
                            </select>
                            
                            <label>Ngày</label>
                            <?php $time=date("Y-m-d"); ?>
                            <input type="date" name="txtdate" value="<?=$time?>">
                            <button name="search" class="btn btn-info">Tìm Kiếm</button>
                            <?php if($count!=0){
                                echo '<p>Có '.$count.' đơn hàng</p>';
                            }?>
                        </div>

                        <?php
                            if ($result->rowCount() > 0) {
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
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $STT = 1;
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td> <?=$STT ?></td>
                                    <td> <?=$row['id']; ?> </td>
                                    <td> <?=$row['order_name']; ?> </td>
                                    <td> <?=$row['phone']; ?></td>
                                    <td> <?=date('d-m-Y', strtotime( $row['booking_date']));?></td>
                                    <td> <?= number_format($row['total_price']); ?></td>
                                    <td> <p class="status" style="padding:5px;color: #fff; background-color: <?php 
                                                if($row['status']=='1'){echo'green';} 
                                                elseif($row['status']=='3'){echo'red';}
                                                elseif($row['status']=='2'){echo'#2f5de4';}
                                                else{echo'orange';} ?> "><?php if($row['status']=="0") {echo 'In processing';}elseif($row['status']=="1") {echo 'Delivering';}
                                                elseif($row['status']=="2") {echo 'Received';} else echo 'Canceled';?></p> </td>
                                    <td>
                                        <a href="order-details.php?id=<?=$row['id']; ?>" name="update"><i class="fa-sharp fa-solid fa-pen-to-square" style="color: #288ae6;"></i></a>
                                        <a href="order-print.php?id=<?=$row['id']; ?>" name="print"><i class="fa-solid fa-print" style="color: #b44a1d;"></i></a>

                                    </td>
                                </tr>
                                <?php
                                    $STT++;
                                    }
                                ?>
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