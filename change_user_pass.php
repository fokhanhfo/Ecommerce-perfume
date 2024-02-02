<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

$select_user = $conn->prepare("SELECT password FROM `tbl_user` WHERE id = ?");
$select_user->execute([$user_id,]);


if($select_user->rowCount() > 0){
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
    }

if(isset($_POST['save'])){
   $old_pass = ($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = ($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = ($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

if($old_pass != $row['password']){
      echo 'please enter old password!';
   }elseif($new_pass != $cpass){
      echo 'confirm password not matched!';
   }else{
         $update_admin_pass = $conn->prepare("UPDATE `tbl_user` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$cpass, $user_id]);
         echo 'password updated successfully!';
      }
   }


?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../qlkh/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/style.css" type="text/css">
   <!-- custom css file link  -->   
</head>


<body style="background-color: #e2e8f0;  ">

<?php include 'components/user_header.php'; ?>
<div class="container" style="height:60vh; display:flex;justify-content:center;align-items:center;padding: 0;font-family: 'Poppins', sans-serif">
    <div class="card" style="width:350px;height:375px;background-color:#fff;box-shadow:0px 15px 30px rgba(0,0,0,0.1);border-radius:10px;overflow:hidden;"> 
        <div class="info" style="padding:15px;display:flex;justify-content:space-between;border-bottom:1px solid #e1dede;background-color:#e5e5e5"> 
            <span style="font-size:17px;">Thông tin cá nhân</span> 
           
        </div> 
        <div class="forms" style="padding:15px"> 
            <form method="POST">
            <div class="inputs" style="display:flex;flex-direction:column;margin-bottom:10px"> 
                <span style="font-size:15px">Old Pass</span> 
                <input type="password" name="old_pass" required placeholder="enter your old password" maxlength=""  class="box" oninput="this.value = this.value.replace(/\s/g, '')" style="height:40px;padding:0px 10px;font-size:17px;box-shadow:none;outline:none;border: 2px solid rgba(0,0)"> 
            </div> 

            <div class="inputs" style="display:flex;flex-direction:column;margin-bottom:10px"> 
                <span style="font-size:15px">New Pass</span> 
                <input type="password" name="new_pass" required placeholder="enter your new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')" style="height:40px;padding:0px 10px;font-size:17px;box-shadow:none;outline:none;border: 2px solid rgba(0,0)"> 
            </div> 

            <div class="inputs" style="display:flex;flex-direction:column;margin-bottom:10px"> 
                <span style="font-size:15px">Confirm</span> 
                <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')" style="height:40px;padding:0px 10px;font-size:17px;box-shadow:none;outline:none;border: 2px solid rgba(0,0)"> 
            </div> 
            <button id="a" style="height:30px;width:80px;border:none;color:#fff;border-radius:4px;background-color:#007bff;cursor:pointer;text-transform:uppercase;" name="save";  >Lưu</button> 

        </div> 
        </form>
    </div>
</div>

</body>
</html>