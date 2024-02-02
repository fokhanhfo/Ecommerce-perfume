<?php 
    include '../components/connect.php';
    session_start();

    // if(isset($_SESSION['admin_id'])){
    //     $admin_id = $_SESSION['admin_id'];
    // } else {
    //     header('location:admin_login.php');
    // }
    $id_phieunhap=$_GET['id_phieunhap'];
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
            text-align: center; /* Căn giữa dữ liệu trong ô */
            border-bottom: 1px solid #ddd;
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
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div>
                        <h2>Thông tin phiếu nhập</h2>
                        <?php
                        $select_phieunhap = $conn->prepare("SELECT * FROM `phieunhap` WHERE id=? AND status=1");
                        $select_phieunhap->execute([$id_phieunhap]);
                        $fetch_phieunhap = $select_phieunhap->fetch(PDO::FETCH_ASSOC);

                        $select_admin = $conn->prepare("SELECT * FROM `tbl_admin` WHERE id=?");
                        $select_admin->execute([$fetch_phieunhap['id_admin']]);
                        $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

                        $select_NCC = $conn->prepare("SELECT * FROM `nhacungcap` WHERE id=?");
                        $select_NCC->execute([$fetch_phieunhap['MaNCC']]);
                        $fetch_NCC = $select_NCC->fetch(PDO::FETCH_ASSOC);
                        ?>

                        <p>Người nhập :<?= htmlspecialchars($fetch_phieunhap['id_admin']) ?></p>
                        <p>Nhà cung cấp :<?= htmlspecialchars($fetch_NCC['tenNCC']) ?></p>
                        <p>Ngày nhập :<?= htmlspecialchars($fetch_phieunhap['ngayNhap']) ?></p>
                        <p>Tổng tiền :<?= number_format($fetch_phieunhap['Tongtien']) ?></p>

                    </div>
                    <h2>Danh sách hàng hóa</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $select_ctphieu = $conn->prepare("SELECT * FROM `ctphieunhap` WHERE 1 and maBL=$id_phieunhap");
                            $select_ctphieu->execute();
                            if($select_ctphieu -> rowCount() >0){
                            while ($fetch_ctphieu = $select_ctphieu->fetch(PDO::FETCH_ASSOC)) {
                                $select_product = $conn->prepare("SELECT * FROM `tbl_product` WHERE id=?");
                                $select_product->execute([$fetch_ctphieu['maHang']]);
                                $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
                            ?>
                                <tr>
                                    <input type="hidden" name="id_phieunhap" value="<?= $fetch_ctphieu['maBL'] ?>">
                                    <td><?= $fetch_product['name'] ?></td>
                                    <td id="price"><?= number_format($fetch_product['price']) ?>
                                        <input type="hidden" value="<?= $fetch_product['price'] ?>" name="price[<?= $fetch_product['id'] ?>]">
                                    </td>
                                    <!-- <td><input value="<?= $fetch_ctphieu['soLuong'] ?>" type="number" class="positiveNumber" name="quantity[<?= $fetch_product['id'] ?>]" min=1 oninput="updateTotal(this)" pattern="[0-9]*"></td>
                                    <td class="totalAmount">0</td> -->
                                    <td>
                                    <?= $fetch_ctphieu['soLuong'] ?>
                                    </td>
                                    <td class="totalAmount" id="totalAmount_<?= $fetch_product['id'] ?>">
                                        <?= number_format($fetch_ctphieu['soLuong'] * $fetch_product['price']) ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }else{
                            ?>
                            <tr>
                                <td>Chưa có sản phẩm</td>
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
