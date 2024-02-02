<?php

include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $full_name = $_POST['full_name'];
   $full_name = filter_var($full_name, FILTER_SANITIZE_STRING);
   $username = $_POST['username'];
   $username = filter_var($username, FILTER_SANITIZE_STRING);
   $phone_number = $_POST['phone_number'];
   $phone_number = filter_var($phone_number, FILTER_SANITIZE_STRING);
   $password = ($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $cpassword = ($_POST['cpassword']);
   $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `tbl_user` WHERE username = ?");
   $select_user->execute([$username,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      echo"
         <script type = 'text/javascript'>
            alert('username already exists!');
         </script>";
   }else{
      if($password != $cpassword){
         echo"
         <script type = 'text/javascript'>
            alert('confirm password not matched!');
         </script>";
      }else{
         $insert_user = $conn->prepare("INSERT INTO `tbl_user`(email, full_name, password, phone_number, username) VALUES(?,?,?,?,?)");
         $insert_user->execute([$email, $full_name, $cpassword, $phone_number, $username]);
         // $newUserId = $conn->lastInsertId();
         // $user_role = $conn->prepare("SELECT id FROM `tbl_role` WHERE role_name = 'user'");
         // $user_role->execute();
         // $role_id = $user_role->fetchColumn();
         // $insert_user_role = $conn->prepare("INSERT INTO `tbl_user_role`(user_id, role_id) VALUES(?,?)");
         // $insert_user_role->execute([$newUserId, $role_id]);
         echo"
         <script type = 'text/javascript'>
            alert('registered successfully, login now please!');
         </script>";
      }
   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   
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
<style>
      body {
            background-image: url('https://lirp.cdn-website.com/md/unsplash/dms3rep/multi/opt/photo-1615634260167-c8cdede054de-640w.jpg');
            background-size: 100% 100%;
            background-position: 0 0;
        }
      form {
           position: relative;
           width: 250px;
           margin: 0 auto;
           margin-top: 20px;
           background: rgba(247,245,250, .5);
           padding: 20px 22px;
           border: 1px solid;
           border-top-color: rgba(255,255,255,.4);
           border-left-color: rgba(255,255,255,.4);
           border-bottom-color: rgba(60,60,60,.4);
           border-right-color: rgba(60,60,60,.4);
         }

         form input, form button {
           width: 212px;
           border: 1px solid #e1e1e1;
           border-bottom-color: rgba(255,255,255,.5);
           border-right-color: rgba(60,60,60,.35);
           border-top-color: rgba(60,60,60,.35);
           border-left-color: rgba(80,80,80,.45);
           background-color: rgba(0,0,0,.2);
           background-repeat: no-repeat;
           padding: 8px 24px 8px 10px;
           font: .875em/1.25em "Nunito Sans", sans-serif;
           color: #ffffff;
           text-shadow: 0 1px 0 rgba(0,0,0,.1);
           margin-bottom: 19px;

         }
         ::-webkit-input-placeholder { color: #ccc;}
         ::-moz-placeholder { color: #ccc;}
         :-ms-input-placeholder { color: #ccc;}
         form input[type=submit] {
           width: 212px;
           margin-bottom: 0;
           color: #ffffff;
           letter-spacing: .05em;
           text-shadow: 0 1px 0 #133d3e;
           text-transform: uppercase;
           background: #225556;
           border-top-color: #9fb5b5;
           border-left-color: #608586;
           border-bottom-color: #1b4849;
           border-right-color: #1e4d4e;
           cursor: pointer;
         }

   form input:focus { background-color: rgba(0,0,0,.4); }
</style>

<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3 style="color: #28465c; margin-bottom: 10px;">Đăng kí</h3>
      <input type="text" name="username" required placeholder="enter your username" maxlength="20"  class="box" pattern="[a-zA-Z0-9]+">
      <input type="text" name="full_name" required placeholder="enter your full_name" maxlength="50"  class="box" pattern="[a-zA-Z0-9\sàáạãèéẹẽìíịĩòóọõùúụũưừứựửừứựữêếềệểôồốộỗăằắặẳẵâầấậẩẫđÑñ]+">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="phone_number" required placeholder="enter your phone number" pattern="[0-9]{10}" required>
      <input type="password" name="password" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpassword" required placeholder="confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="submit">
      <p style="margin-top: 8px; text-align: center;">already have an account?</p>
      <div style=" text-align: center;">
         <a href="user_login.php" class="option-btn">login now</a>
      </div>
      
   </form>

</section>



<script src="js/script.js"></script>

</body>
</html>