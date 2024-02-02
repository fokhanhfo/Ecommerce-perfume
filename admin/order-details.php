<?php 
    include '../components/connect.php';
    session_start();
    if(isset($_GET['id'])){
        $order_id=$_GET['id'];
        
    }
    if(isset($_POST['update'])){
        $status=$_POST['chon'];
        $update_order = $conn->prepare("UPDATE tbl_order SET status=? where id=?"); 
        $update_order->execute([$status,$order_id]);
                //cập nhật số lượng nếu đơn hàng đc vận chuyển
                if($status=='1'){
                    $quantity=$_POST['quantity'];
                    $product_id=$_POST['product_id'];
                    // echo "slg còn";
                    // echo $quantity;
                    // echo "id san phẩm";
                    // echo $product_id;
                    $update_products = $conn->prepare("UPDATE tbl_product SET quantity=? where id=?"); 
                    $update_products->execute([$quantity,$product_id]);
                }
        header("location: order.php");
    }
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

                <?php
                    $select_order=$conn->prepare("SELECT * FROM tbl_order where id=?");
                    $select_order->execute([$order_id]);
                    if($select_order->rowCount() >0){
                        $fetch_order=$select_order->fetch(PDO::FETCH_ASSOC)
                ?>
                <div class="container-fluid px-4">
                    <div class="row">
                        <h3 style="margin-top:10px;">Thông tin khách hàng</h3>
                    </div>
                    <div>
                        <p>Tên khách hàng: <?=$fetch_order['order_name'];?> </p>
                        <p>Số điện thoại: <?=$fetch_order['phone'];?> </p>
                        <p>Địa chỉ nhận hàng: <?=$fetch_order['address'];?> </p>
                        <p>Ngày đặt hàng: <?=date('d-m-Y', strtotime( $fetch_order['booking_date']));?> </p>
                        <p>Ghi chú: <?=$fetch_order['note'];?> </p>
                        <p>Trạng thái đơn hàng: <?php if($fetch_order['status']=="0") {echo 'In processing';}elseif($fetch_order['status']=="1") {echo 'Delivering';}
                                                elseif($fetch_order['status']=="2") {echo 'Received';} else echo 'Canceled';?>
                            <form action="" method="POST">
                                <div class="form-group">
                                    <select name="chon" required>
                                        <option value="0" <?php echo ($fetch_order['status'] == "0") ? 'selected' : ''; ?> >In processing </option>
                                        <option value="1" <?php echo ($fetch_order['status'] == "1") ? 'selected' : ''; ?> >Delivering</option>
                                        <option value="2" <?php echo ($fetch_order['status'] == "2") ? 'selected' : ''; ?> >Received</option>
                                        <option value="3" <?php echo ($fetch_order['status'] == "3") ? 'selected' : ''; ?> >Canceled</option>
                                    </select>
                                    <button type="submit" name ="update" class="btn btn-primary">Cập nhật</button> <br>
                                </div>
                            
                        </p>
                    </div>
                </div>
                <?php
                }
                ?>

                <div class="container-fluid px-4">
                
                    <h3 style="margin-top:10px;">Danh sách đơn hàng</h3>
                </select>
                        <table class="table">
                            <thead >
                                <tr>
                                    <th>STT</th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php
                                $STT=1;
                                $select_order = $conn->prepare("SELECT * FROM tbl_order where id=? ORDER BY booking_date "); 
                                $select_order->execute([$order_id]);
                                if($select_order->rowCount() > 0){
                                    while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){ 

                                        $select_order_products = $conn->prepare("SELECT * FROM tbl_order_product where order_id=? "); 
                                        $select_order_products->execute([$fetch_order['id']]);
                                        if($select_order_products->rowCount() > 0){
                                            $count=$select_order_products->rowCount();
                                            while($fetch_order_product = $select_order_products->fetch(PDO::FETCH_ASSOC)){
                                                
                                                $select_products = $conn->prepare("SELECT * FROM tbl_product where id=?"); 
                                                $select_products->execute([$fetch_order_product['product_id']]);
                                                if($select_products->rowCount() > 0){
                                                    while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                                                        
                                ?>
                                
                                    <tr>
                                        <td><?=$STT++;?></td> 
                                        <td>
                                            <img src="../uploaded_img/<?=$fetch_product['image_1']?>" alt="Ảnh nước hoa" style="width: 100px; height: 100px;">
                                        </td>
                                        <td >
                                            <div>
                                                <h6><?= $fetch_product['name']; ?></h6>
                                                <h5><?= number_format($fetch_product['price']); ?><span>x<?=$fetch_order_product['quantity']; ?></span></h5>
                                                <input type="hidden" name="order_id" value="<?= $fetch_order_product['order_id']; ?>">
                                                <input type="hidden" name="product_id" value="<?= $fetch_order_product['product_id']; ?>">
                                                <input type="hidden" name="quantity" value="<?=($fetch_product['quantity']-$fetch_order_product['quantity']);?>">

                                            </div>
                                        </td>
                                        <td ><?= number_format($fetch_order_product['quantity']*$fetch_order_product['price']);?></td>
                                    </tr> 
                                <?php
                                                    }
                                                }
                                            }
                                        }?>
                                    <tr style="background-color: pink;">
                                        <td class="col-sm-3"><h5><b>Total: </b></h5>
                                        </td>
                                        <td ><h5 ><b><span> <?= number_format($fetch_order['total_price']); ?> </span></b></h5></td>
                                        <td></td>
                                        <td></td>
                                    </tr> 
                                <?php }
                                
                                }?>
                                </tbody>
                            </form>
                        </table>
                    </div>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>