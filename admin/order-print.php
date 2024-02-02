<?php 
    include '../components/connect.php';
    session_start();
    if(isset($_GET['id'])){
        $order_id=$_GET['id'];
    }
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
       <style>
        .box{
            /* border:1px solid black; */
            border-style: double ;
            width: max-content;
            align:center;
            margin:36px auto 20px auto;
            padding: 10px 20px;
        }
       </style>
    </head>
    <body class="sb-nav-fixed">
        <div class="box">
            <h2 style="text-align: center;">Chi tiết hóa đơn</h2>
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
                        <p><b>Người nhận: </b><?=$fetch_order['order_name'];?> </p>
                        <p><b>Số điện thoại: </b><?=$fetch_order['phone'];?> </p>
                        <p><b>Địa chỉ nhận: </b><?=$fetch_order['address'];?> </p>
                        
                    </div>
                    <hr>
                </div>
                <?php
                }
                ?>

                <div class="container-fluid px-4">
                
                    <h3 style="margin-top:10px;">Danh sách đơn hàng</h3>
                
                    <table class="table">
                        <thead >
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                                <?php
                                $STT=1; $quantity=0;
                                $select_order = $conn->prepare("SELECT * FROM tbl_order where id=? ORDER BY booking_date "); 
                                $select_order->execute([$order_id]);
                                // truy vấn 3 bảng
                                // $select_order = $conn->prepare("SELECT * FROM (tbl_order as a inner join tbl_order_product as b on a.id=b.order_id ) inner join tbl_product as c on b.product_id=c.id where a.id=$order_id"); 
                                // $select_order->execute();
                                if($select_order->rowCount() > 0){
                                    while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){ 

                                        $select_order_products = $conn->prepare("SELECT * FROM tbl_order_product where order_id=? "); 
                                        $select_order_products->execute([$fetch_order['id']]);
                                        if($select_order_products->rowCount() > 0){
                                            while($fetch_order_product = $select_order_products->fetch(PDO::FETCH_ASSOC)){
                                                // tổng sp
                                                $quantity+=$fetch_order_product['quantity'];
                                                
                                                $select_products = $conn->prepare("SELECT * FROM tbl_product where id=?"); 
                                                $select_products->execute([$fetch_order_product['product_id']]);
                                                if($select_products->rowCount() > 0){
                                                    while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                                                        
                                ?>
                        <tbody>
                            <tr>
                                <h5>
                                <td><?=$STT++;?>. <?= $fetch_product['name']; ?></td>
                                <td><?= number_format($fetch_order_product['price']); ?>x<?=$fetch_order_product['quantity']; ?> sản phẩm</td>
                                </h5>
                            </tr>
                            
                        <?php
                                            }
                                        }
                                    }
                                }?>
                            <td>
                                
                                <h4><b>Tổng số lượng: </b><span> <?=$quantity?> </span> - <b>Tổng tiền: </b><span> <?= number_format($fetch_order['total_price']); ?> </span></h4>
                                <?php
                                    if(empty($fetch_order['note'])==false){
                                        echo '<p><b>Ghi chú: </b>'.$fetch_order['note'].' </p>';
                                    }
                                ?>
                                
                            </td>

                        <?php }
                        
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
    </body>
</html>