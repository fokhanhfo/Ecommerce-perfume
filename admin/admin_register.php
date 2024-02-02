<?php

include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['btnsubmit'])){

   $adname = $_POST['txtadname'];
   $adname = filter_var($adname, FILTER_SANITIZE_STRING);
   $name = $_POST['txtname'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = ($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = ($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_ad = $conn->prepare("SELECT * FROM `tbl_admin` WHERE adname = ?");
   $select_ad->execute([$adname,]);
   $row = $select_ad->fetch(PDO::FETCH_ASSOC);

   if($select_ad->rowCount() > 0){
      echo"
         <script type = 'text/javascript'>
            alert('acc already exists!');
         </script>";
   }else{
      if($pass != $cpass){
         echo"
         <script type = 'text/javascript'>
            alert('confirm password not matched!');
         </script>";
      }else{
        if($admin_id == 1) {
         $insert_ad = $conn->prepare("INSERT INTO `tbl_admin`(adname, name, password) VALUES(?,?,?)");
         $insert_ad->execute([$adname, $name, $cpass]);
         // $newAdId = $conn->lastInsertId();
         // $ad_role = $conn->prepare("SELECT id FROM `tbl_role` WHERE role_name = 'admin'");
         // $ad_role->execute();
         // $role_id = $ad_role->fetchColumn();
         // $insert_ad_role = $conn->prepare("INSERT INTO `tbl_user_role`(user_id, role_id) VALUES(?,?)");
         // $insert_ad_role->execute([$newAdId, $role_id]);
         echo"
         <script type = 'text/javascript'>
            alert('Đăng kí thành công!');
         </script>"; } else {
            echo"
         <script type = 'text/javascript'>
            alert('Bạn không phải chủ cửa hàng!');
         </script>";
         }
      }
   }

}


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
                        <div class="container form-text">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 style="margin: auto; margin-top: 20px; text-align: center;">Thêm Tài Khoản</h3>
                                </div>
                                <div class="col-sm-12" style="display: flex; justify-content: center; align-items: center;">
                                    <!-- Form Thêm sản phẩm -->
                                    <form method="post" style="width: 500px; ">
                                        <!-- Tên sản phẩm -->
                                        <div class="form-group">
                                            <label for="txtadname">Tên tài khoán</label>
                                            <input class="form-control" id="txtadname" type="text" name="txtadname" required placeholder="enter your username" oninvalid="this.setCustomValidity('Vui lòng nhập tên đăng nhập')" oninput="setCustomValidity('')" maxlength="20" pattern="[a-zA-Z0-9]+">
                                        </div>
                                        <div class="form-group">
                                            <label for="txtname">Họ và tên</label>
                                            <input class="form-control" id="txtname" type="text" name="txtname" required placeholder="enter your full name" oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')" oninput="setCustomValidity('')"  maxlength="50"  class="box" pattern="[a-zA-Z0-9\sàáạãèéẹẽìíịĩòóọõùúụũưừứựửừứựữêếềệểôồốộỗăằắặẳẵâầấậẩẫđÑñ]+">
                                        </div>
                                        <div class="form-group">
                                            <label for="pass">Mật khẩu</label>
                                            <input class="form-control" id="pass" type="password" name="pass" required placeholder="enter your password" oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')" oninput="setCustomValidity('')" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        </div>
                                        <div class="form-group">
                                            <label for="cpass">Nhập lại mật khẩu</label>
                                            <input class="form-control" id="cpass" type="password" name="cpass" required placeholder="confirm your password" oninvalid="this.setCustomValidity('Vui lòng nhập lại mật khẩu')" oninput="setCustomValidity('')" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="btnsubmit">Thêm tài khoản</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>
