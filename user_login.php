<?php

include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $username = $_POST['username'];
   $pass = ($_POST['pass']);

   $select_user = $conn->prepare("SELECT * FROM `tbl_user` WHERE username = ? AND password = ?");
   $select_user->execute([$username, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      if (isset($row['status']) && $row['status'] != 1) {
         $_SESSION['user_id'] = $row['id'];
         header('location:index.php');
      } elseif (isset($row['status']) && $row['status'] == 1) {
         echo "
            <script type='text/javascript'>
               alert('Tài khoản đã bị khóa!');
            </script>";
      }
   } else {
      echo "
         <script type='text/javascript'>
            alert('Tài khoản không tồn tại hoặc mật khẩu không chính xác!');
         </script>";
   }
   

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>
   
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
           margin-top: 110px;
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
           color: #fff;
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
      <h3 style="color: #28465c; margin-bottom: 10px;">login now</h3>
      <input type="text" name="username" required placeholder="enter your username" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
      <p style="margin-top: 8px; text-align: center;">don't have an account?</p>
      <div style=" text-align: center;">
         <a href="user_register.php" class="option-btn">Register now</a>
      </div>
   </form>

</section>

<script src="js/script.js"></script>

</body>
</html>