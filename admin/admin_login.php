<?php

include '../components/connect.php';
session_start();

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
    $adname = isset($_SESSION['adname']) ? $_SESSION['adname'] : '';
} else {
    $admin_id = '';
}


if(isset($_POST['btnsubmit'])){

   $adname = $_POST['txtadname'];
   $pass = ($_POST['pass']);

   $select_ad = $conn->prepare("SELECT * FROM `tbl_admin` WHERE adname = ? and password = ?");
   $select_ad->execute([$adname,$pass]);
   $row = $select_ad->fetch(PDO::FETCH_ASSOC);

    if($select_ad->rowCount() > 0){
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['adname'] = $row['adname'];
        echo "Session adname: " . $_SESSION['adname'];
        header('location:index.php');
   }else{
      
         echo"
         <script type = 'text/javascript'>
            alert('Tài khoản không tồn tại hoặc tài khoản mật khẩu không đúng');
         </script>";
      
   }

}


?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <head>
        <style>
            .divider:after,
            .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <div id="layoutSidenav">
            <div id="layoutSidenav_content">
                <main>
                    <form action="" method="post">
                        <section class="vh-100">
                            <div class="container py-5 h-100">
                                <div class="row d-flex align-items-center justify-content-center h-100">
                                <div class="col-md-8 col-lg-7 col-xl-6">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                                    class="img-fluid" alt="Phone image">
                                </div>
                                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                                    <form>
                                    <!-- Email input -->
                                    <div class="form-outline mb-4">
                                        <input type="text" id="txtadname" name="txtadname" class="form-control form-control-lg" />
                                        <label class="form-label" for="txtadname" required placeholder="Nhập tài khoản" maxlength="20" pattern="[a-zA-Z0-9]+">Nhập tài khoản</label>
                                    </div>

                                    <!-- Password input -->
                                    <div class="form-outline mb-4">
                                        <input type="password" id="pass" name="pass" class="form-control form-control-lg" />
                                        <label class="form-label" for="form1Example23" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">Nhập mật khẩu</label>
                                    </div>

                

                                    <!-- Submit button -->
                                    <button type="submit" name="btnsubmit" class="btn btn-primary btn-lg btn-block">Đăng nhập</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </main>
            </div>
        </div>
    </body>
</html>
