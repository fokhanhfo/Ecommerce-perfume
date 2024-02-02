<?php

include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

$select_profile = $conn->prepare("SELECT * FROM `tbl_admin` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
if(isset($_POST['btnsubmit'])){
    $name = $_POST['txtname'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $update_profile_name = $conn->prepare("UPDATE `tbl_admin` SET name = ? WHERE id = ?");
    $update_profile_name->execute([$name, $admin_id]);
    
    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $_POST['prev_pass'];
    $name = $_POST['txtname'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $opass = ($_POST['opass']);
    $opass = filter_var($opass, FILTER_SANITIZE_STRING);
    $npass = ($_POST['npass']);
    $npass = filter_var($npass, FILTER_SANITIZE_STRING);
    $cpass = ($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    if($opass == $empty_pass){
        echo 'please enter old password!';
    }elseif($opass != $prev_pass){
        echo 'old password not matched!';
    }elseif($npass != $cpass){
        echo 'confirm password not matched!';
    }else{
        if($npass != $empty_pass){
            $update_admin_pass = $conn->prepare("UPDATE `tbl_admin` SET password = ? WHERE id = ?");
            $update_admin_pass->execute([$cpass, $admin_id]);
            echo "
            <script type = 'text/javascript'>
            alert('cap nhat thanh cong');
                window.location.href = 'admin_update.php';
            </script>";
        }else{
            echo 'please enter a new password!';
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
                                    <h3 style="margin: auto; margin-top: 20px; text-align: center;">Cập Nhật Tài Khoản</h3>
                                </div>
                                <div class="col-sm-12" style="display: flex; justify-content: center; align-items: center;">
                                    <!-- Form Thêm sản phẩm -->
                                    <form method="post" style="width: 500px; ">
                                        <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
                                        <div class="form-group">
                                            <label for="txtname">Họ và tên</label>
                                            <input class="form-control" id="txtname" type="text" name="txtname" value="<?= $fetch_profile['name']; ?>" required placeholder="enter your full_name"  maxlength="50"  class="box">
                                        </div>
                                        <div class="form-group">
                                            <label for="pass">Mật khẩu cũ</label>
                                            <input class="form-control" id="opass" type="password" name="opass" required placeholder="enter your old password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        </div>
                                        <div class="form-group">
                                            <label for="pass">Mật khẩu mới</label>
                                            <input class="form-control" id="npass" type="password" name="npass" required placeholder="enter your new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        </div>
                                        <div class="form-group">
                                            <label for="cpass">Nhập lại mật khẩu</label>
                                            <input class="form-control" id="cpass" type="password" name="cpass" required placeholder="confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="btnsubmit">Cập nhật</button>
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
