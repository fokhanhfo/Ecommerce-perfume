<?php
include '..\components\connect.php';

session_start();


    // if(isset($_SESSION['admin_id'])){
    //     $admin_id = $_SESSION['admin_id'];
    // } else {
    //     header('location:../admin_login.php');
    // }

// if(isset($_SESSION['user_id'])){
//    $user_id = $_SESSION['user_id'];
// }else{
//    $user_id = '';
// };
$blog_id=$_GET['update_blog'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <?php include ('../admin/component/admin_head.php'); ?>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group input[type="file"] {
            cursor: pointer;
        }
        h2 {
            margin-top: 20px;
        }
        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table {
            max-width:1200px;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: center;
            
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        th:hover {
            background-color: #555;
        }

        td:hover {
            background-color: #f5f5f5;
        }

        td:first-child {
            border-radius: 5px 0 0 5px;
        }

        td:last-child {
            border-radius: 0 5px 5px 0;
        }
        textarea {
            height: auto;
            min-height: 100px; /* Đổi giá trị này thành chiều cao mặc định mong muốn */
            overflow: auto;
        }
        .nut-bam {
            width: 100px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .nut-bam:hover {
            background-color: #0056b3;
        }
    </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <form action="" method="post" enctype="multipart/form-data">
                        <?php
                             $select_blog = $conn->prepare("SELECT * FROM tbl_blog JOIN tbl_img_blog ON tbl_blog.id = tbl_img_blog.id_blog WHERE tbl_blog.id = ?;"); 
                             $select_blog->execute([$blog_id]);
                             $fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <div class="container" style="max-width: 1200px;">
                            <h4 style="text-align: center;font-size: xx-large;">Tin tức</h4>
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" required id="title" name="title" value="<?= $fetch_blog['title'] ?>">
                                <input type="hidden" id="title" name="time_post" value="">
                            </div>
                            <div class="form-group">
                                <label for="title">Nội dung đầu:</label>
                                <input type="text" id="title" name="content" value="<?= $fetch_blog['content'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="image">Ảnh tin tức:</label>
                                <div style="display:flex;">
                                    <div>
                                        <p>Ảnh cũ</p>
                                        <img style="width: 100px;" src="../uploaded_img/<?= $fetch_blog['blog_image'] ?>" alt="">
                                    </div>
                                    <input  style="width: 45%;height: 50px;" type="file" id="image" name="file_anh_blog">
                                </div>
                            </div>
                            <input class="nut-bam" style="margin-top:5px;" type="submit" value="Update" name="update">
                        </div>
                    </form>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>
<?php
if (isset($_POST['update'])) {
    $blog_id=$_GET['update_blog'];
    $title1=$_POST['title'];
    $content=$_POST['content'];
    $time_post=$_POST['time_post'];
    $file_name = $_FILES["file_anh_blog"]["name"];
    $file_temp = $_FILES["file_anh_blog"]["tmp_name"];
    $select_blog = $conn->prepare("SELECT * FROM tbl_blog where id != $blog_id "); 
    $select_blog->execute();
    $fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC);
    $bolen=true;
    while ($fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC)){
        if($fetch_blog['title'] == $title1){
            $bolen=false;
        }
    }
    if($bolen==false){
        echo '<script>alert("trùng!");</script>';
    }else{
        if($file_name==null){
            $sql = "update tbl_blog set title=? , time_post=null, content=? where id=?;";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title1,$content,$blog_id]);
        }else{
            $target_directory = "../uploaded_img/"; // Thư mục lưu trữ ảnh
            $target_file = $target_directory . $file_name;
            $luu = move_uploaded_file($file_temp, $target_file);
            $sql = "update tbl_blog set title=? , time_post=null ,blog_image=?, content=? where id=?;";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title1,$file_name,$content,$blog_id]);
        }
    }
}
if($stmt){
    echo '<script>alert("Sửa thành công!");window.location.href ="blog.php";</script>';
}
?>