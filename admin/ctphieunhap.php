<?php 
    include '../components/connect.php';
    session_start();

    if(isset($_SESSION['admin_id'])){
        $admin_id = $_SESSION['admin_id'];
    } else {
        header('location:admin_login.php');
    }
    if(isset($_POST['update_ctphieu'])){
        foreach ($_POST['quantity'] as $productId => $quantity) {
            $price = $_POST['price'][$productId];
            if($quantity!=null){
                $thanhTien = $quantity * $price;
            }
            // $thanhTien = $quantity * $price;
            $sql = "UPDATE `ctphieunhap` SET `soLuong`=?,`Thanhtien`=? WHERE maHang=?";
            $update_ctphieu = $conn->prepare($sql);
            $update_ctphieu->execute([$quantity,$thanhTien,$productId]);
            $solan+=1;
        }
        if ($update_ctphieu) {
            echo "<script>
            alert('Cập nhật thành công');
            </script>";
        }
    }
    if(isset($_POST['xuat_ctphieu'])){
        $total=0;
        $id_NCC=$_POST['selectOption'];
        $id_phieunhap=$_POST['id_phieunhap'];
        $timestamp = time();
        $ngayGioHienTai = date("Y-m-d", $timestamp);
        foreach ($_POST['quantity'] as $productId => $quantity) {
            $price = $_POST['price'][$productId];
            if($quantity!=null){
                $thanhTien = $quantity * $price;
                $total+=$thanhTien;
            }
            $sql = "UPDATE `ctphieunhap` SET `status`=1,`soLuong`=?,`Thanhtien`=? WHERE maHang=?";
            $update_ctphieu = $conn->prepare($sql);
            $update_ctphieu->execute([$quantity,$thanhTien,$productId]);

            $sql_select_SL="SELECT * FROM `tbl_product` WHERE id=?";
            $select_SL = $conn->prepare($sql_select_SL);
            $select_SL->execute([$productId]);
            $fetch_select_SL = $select_SL->fetch(PDO::FETCH_ASSOC);
            $total_SL=$fetch_select_SL['quantity']+$quantity;

            $sql_totalSL = "UPDATE `tbl_product` SET `quantity`=? WHERE id=?";
            $update_SL = $conn->prepare($sql_totalSL);
            $update_SL->execute([$total_SL,$productId]);

        }
        $sql_phieunhap = "UPDATE `phieunhap` SET `id_admin`=?,`MaNCC`=?,`ngayNhap`=?,`Tongtien`=?, `status`=1 WHERE id=?";
        $update_phieunhap = $conn->prepare($sql_phieunhap);
        $update_phieunhap->execute([$admin_id,$id_NCC, $ngayGioHienTai, $total, $id_phieunhap]);
        if ($update_phieunhap) {
            echo "<script>
            alert('Xuất phiếu thành công $id_phieunhap');
            </script>";
        }
    }
    if(isset($_GET['id_ctphieu'])){
        $id_ctphieu=$_GET['id_ctphieu'];
        $delete_product = $conn->prepare("DELETE FROM `ctphieunhap` WHERE id = ?");
        $delete_product->execute([$id_ctphieu]);
        if($delete_product){
            echo "<script>
            alert('Xuất phiếu thành công $id_phieunhap');
            </script>";
            header('location:ctphieunhap.php');
        }
    }
    if(isset($_POST['delete_ctphieu'])){
        $delete_product = $conn->prepare("DELETE FROM `ctphieunhap` WHERE status =0");
        $delete_product->execute();
        if($delete_product){
            echo "<script>
            alert('Xuất phiếu thành công $id_phieunhap');
            </script>";
            header('location:ctphieunhap.php');
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
                    <h2>Hàng hóa đã thêm</h2>
                    <p>
                        <a href="nhaphang.php">Nhập hàng</a>->
                        <a href="ctphieunhap.php">Chi tiết hàng đã nhập</a>
                    </p>
                    <form action="" method="post">
                    <table id="myTable">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $select_ctphieu = $conn->prepare("SELECT * FROM `ctphieunhap` WHERE 1 and status=0");
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
                                        <?php
                                        if($fetch_ctphieu['soLuong']>0){
                                        ?>
                                            <input required value="<?= $fetch_ctphieu['soLuong'] ?>" type="number" class="positiveNumber" name="quantity[<?= $fetch_product['id'] ?>]" min="1" oninput="updateTotal(this)" pattern="[0-9]*">
                                        <?php
                                        }else{?> <input value=0 required type="number" class="positiveNumber" name="quantity[<?= $fetch_product['id'] ?>]" min="1" oninput="updateTotal(this)" pattern="[0-9]*"> <?php }
                                        ?>
                                    </td>
                                    <td class="totalAmount" id="totalAmount_<?= $fetch_product['id'] ?>">
                                        <?= number_format($fetch_ctphieu['soLuong'] * $fetch_product['price']) ?>
                                    </td>
                                    <td>
                                        <a onclick="return confirm('Chắc chắn xóa?');" href="ctphieunhap.php?id_ctphieu=<?= $fetch_ctphieu['id']; ?>" style="display: inline;width:16px;height:16px;" name="delete_product"><i class="fas fa-trash-alt"></i></a>
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
                        <?php
                        if($select_ctphieu -> rowCount() >0){
                            ?><tr>
                            <td>
                                <select name="selectOption">
                                    <?php
                                    $select_NCC = $conn->prepare("SELECT * FROM `nhacungcap` WHERE 1");
                                    $select_NCC->execute();
                                    if($select_NCC -> rowCount() >0){
                                        while ($fetch_NCC = $select_NCC->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <option value="<?= $fetch_NCC['id'] ?>"><?= $fetch_NCC['tenNCC'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td><p id="totalAmount">Tổng số tiền: 0</p></td>
                            <td></td>
                        </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                        if($select_ctphieu -> rowCount() >0){
                    ?>
                    <input type="submit" value="Cập nhật" name="update_ctphieu">
                    <input type="submit" value="Xuất Phiếu" name="xuat_ctphieu">
                    <?php } ?>
                    </form>
                    <?php
                        if($select_ctphieu -> rowCount() >0){
                    ?>
                    <form action="" method="post" onsubmit="return confirm('Chắc chắn xóa hết?');">
                        <input style="display:inline-block;" type="submit" value="Xóa hết" name="delete_ctphieu">
                    </form>
                    <?php }else{
                        ?>
                        <a href="nhaphang.php">Thêm sản phẩm</a>
                        <?php
                    } ?>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            function updateTotal(input) {
                var row = input.closest('tr');
                var quantityInput = row.querySelector('.positiveNumber');
                var pricePerUnit = parseFloat(row.querySelector('#price').textContent.replace(/[^0-9.]/g, ''));
                var totalAmountElement = row.querySelector('.totalAmount');

                // Loại bỏ ký tự đặc biệt
                input.value = input.value.replace(/[^0-9]/g, '');

                if (!isNaN(quantityInput.value)) {
                    var quantity = parseInt(quantityInput.value);
                    var totalAmount = quantity * pricePerUnit;
                    totalAmountElement.textContent = formatNumber(totalAmount);
                } else {
                    totalAmountElement.textContent = 0;
                }
                calculateTotalAmount();
            }

            $(document).ready(function () {
                // Tính tổng số tiền khi trang tải
                calculateTotalAmount();

                // Thêm sự kiện lắng nghe cho sự thay đổi giá trị
                $("#myTable").on("input", ".totalAmount", function () {
                    calculateTotalAmount();
                });

                // Hàm tính tổng số tiền
                function calculateTotalAmount() {
                    var totalAmount = 0;

                    // Lặp qua tất cả các ô có class 'totalAmount'
                    $(".totalAmount").each(function () {
                        // Lấy giá trị từ các ô và cộng vào tổng
                        var amount = parseFloat($(this).text().replace(/,/g, ""));
                        totalAmount += isNaN(amount) ? 0 : amount;
                    });

                    // Hiển thị tổng số tiền
                    $("#totalAmount").text("Tổng số tiền: " + numberWithCommas(totalAmount));
                }

                // Hàm thêm dấu phẩy cho số
                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            });
            function calculateTotalAmount() {
                var totalAmount = 0;

                // Lặp qua tất cả các dòng trong bảng
                document.querySelectorAll('.totalAmount').forEach(function (totalAmountElement) {
                    // Lấy giá trị từ các ô và cộng vào tổng
                    var amount = parseFloat(totalAmountElement.textContent.replace(/[^0-9.]/g, ''));
                    totalAmount += isNaN(amount) ? 0 : amount;
                });

                // Hiển thị tổng số tiền
                document.getElementById("totalAmount").textContent = "Tổng số tiền: " + formatNumber(totalAmount);
            }
        </script>
    </body>
</html>
