<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

$select_user = $conn->prepare("SELECT * FROM `tbl_user` WHERE id = ?");
$select_user->execute([$user_id,]);


if($select_user->rowCount() > 0){
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
    }


if(isset($_POST['save'])){

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $full_name = $_POST['full_name'];
    $full_name = filter_var($full_name, FILTER_SANITIZE_STRING);
    $phone_number = $_POST['phone_number'];
    $phone_number = filter_var($phone_number, FILTER_SANITIZE_STRING);

    $update_user = $conn->prepare("UPDATE tbl_user SET full_name = ?, email = ?, phone_number =? WHERE id = ?");
    $update_user->execute([$full_name, $email, $phone_number, $user_id]);
    echo "
    <script type = 'text/javascript'>
    alert('cap nhat thanh cong');
        window.location.href = 'profile_with_data.php';
    </script>";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">


<title>profile with data and skills - Bootdey.com</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../qlkh/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../qlkh/css/style.css" type="text/css">
<style type="text/css">
        body{
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;    
}
.main-body {
    padding: 15px;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.form-control {
        display: block;
    width: 100%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}

    </style>
</head>
<body>
    <?php include 'components/user_header.php'; ?>
<div class="container">
<div class="main-body">

<!-- <nav aria-label="breadcrumb" class="main-breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.html">Home</a></li>
<li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
<li class="breadcrumb-item active" aria-current="page">User Profile</li>
</ol>
</nav> -->

<div class="row gutters-sm">
<div class="col-md-4 mb-3">
<div class="card">
<div class="card-body">
<div class="d-flex flex-column align-items-center text-center">
<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
<div class="mt-3">
<p style="font-size: 20px;">
    <?php          
        $select_profile = $conn->prepare("SELECT REVERSE(SUBSTRING(REVERSE(full_name), 1, POSITION(' ' IN REVERSE(full_name)))) AS tu_cuoi_cung FROM tbl_user WHERE id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);}
    ?>
    <?= $fetch_profile["tu_cuoi_cung"]; ?>
</p>
<a class="btn btn-primary" href="change_user_pass.php">Change pass</a>
</div>
</div>
</div>
</div>

</div>
<div class="col-md-8">
<div class="card mb-3">
<div class="card-body">
<form method="POST" style="">
<div class="row">
<div class="col-sm-3">
<h6 class="mb-0">Full Name</h6>
</div>
<div class="col-sm-9 text-secondary">
<input type="text" id="nameInput" name="full_name" value="<?php echo $row["full_name"]; ?>" style=" display: block; width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
">
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
<h6 class="mb-0">Email</h6>
</div>
<div class="col-sm-9 text-secondary">
<input type="text" id="emailInput" name="email" value="<?php echo $row["email"]; ?>" style=" display: block; width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
"> 
</div>
</div>
<hr>
<div class="row">
<div class="col-sm-3">
<h6 class="mb-0">Phone</h6>
</div>
<div class="col-sm-9 text-secondary">
<input type="text" id="phoneInput" name="phone_number" value="<?php echo $row["phone_number"]; ?>" pattern="[0-9]{3}[0-9]{3}[0-9]{4}"  style=" display: block; width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
"> 
</div>
</div>

<hr>
<div class="row">
<div class="col-sm-12">
<button class="btn btn-primary px-4" name="save">Save</button>
</form>
</div>
</div>
</div>
</div>
<div class="row gutters-sm">
<div class="col-sm-6 mb-3">
<div class="card h-100">
<div class="card-body">
<h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>
<small>Web Design</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>Website Markup</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>One Page</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>Mobile Template</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<small>Backend API</small>
<div class="progress mb-3" style="height: 5px">
<div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
</div>
</div>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    
</script>
</body>
</html>