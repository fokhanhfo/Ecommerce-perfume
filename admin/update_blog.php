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
        .table-container th, .table-container td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
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
                            <h4>Tin tức</h4>
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
                                    <input style="width: 45%;" type="file" id="image" name="file_anh_blog">
                                </div>
                            </div>
                                <div id="htmlContainer" style="display: none;">
                                    <h4>Nội dung</h4>
                                    <table class="table-container">
                                        <thead>
                                            <tr>
                                                <th style="width: 290px;">Ảnh</th>
                                                <th style="width: 280px;">Tiêu đề</th>
                                                <th>Nội dung ảnh</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dữ liệu ảnh và nội dung ảnh sẽ được thêm vào đây sau -->
                                            <?php
                                            $select_blog = $conn->prepare("SELECT * FROM tbl_blog JOIN tbl_img_blog ON tbl_blog.id = tbl_img_blog.id_blog WHERE tbl_blog.id = $blog_id;"); 
                                            $select_blog->execute();
                                            $count=0;
                                            while($fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                            <tr>
                                                <td>
                                                    <input type="file" name="anh<?= $count ?>"><br>
                                                    <img style="width: 100px;" src="../uploaded_img/<?= $fetch_blog['image_blog'] ?>" alt="">
                                                </td>
                                                <td><textarea required rows="4" style="width: 100%;" name="title_img<?= $count ?>"><?= $fetch_blog['title_img'] ?></textarea></td>
                                                <td><textarea required  rows="4" style="width: 100%;" name="content_img<?= $count ?>"><?= $fetch_blog['content_img'] ?></textarea></td>
                                                <td style="display: none;"><input name="id_img_blog<?= $count ?>" type="hidden" value=<?= $fetch_blog['id'] ?>></td>
                                            </tr>
                                            <?php
                                            $count+=1;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <input name="count" type="hidden" value=<?= $count?>>
                            <input style="margin-top:5px;" type="submit" value="Update" name="update">
                            <input style="margin-top: 5px;" type="button" value="Sửa nội dung" id="updateButton">
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    var updateButton = document.getElementById("updateButton");
                                    var htmlContainer = document.getElementById("htmlContainer");

                                    updateButton.addEventListener("click", function () {
                                        // Thay đổi nội dung của phần tử htmlContainer khi nút được click
                                        
                                        // Hiển thị phần tử htmlContainer
                                        htmlContainer.style.display = "block";
                                        updateButton.parentNode.removeChild(updateButton);
                                    });
                                });
                            </script>
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
if (isset($_POST['update'])) {
    $blog_id=$_GET['update_blog'];
    $id_img_blog=array();
    $sonoidung = $_POST['count'];
    $title_img=array();
    $file_anh = array();
    $filetemp_anh = array();
    $content_img = array();
    // Thu thập thông tin ảnh và nội dung từ biểu mẫu
    for ($i = 0; $i < $sonoidung; $i++) {
        $file_anh[$i] = $_FILES["anh$i"]["name"];
        $content_img[$i] = $_POST["content_img$i"];
        $id_img_blog[$i] = $_POST["id_img_blog$i"];
        $title_img[$i] = $_POST["title_img$i"];
        $filetemp_anh[$i] = $_FILES["anh$i"]["tmp_name"];
    }

    // Thực hiện lưu trữ ảnh và thêm thông tin vào cơ sở dữ liệu
    $target_directory = "../uploaded_img/";
    for ($i = 0; $i < $sonoidung; $i++) {
        if($file_anh[$i] == null){
            $target_anh = $target_directory . $file_anh[$i];
            $luu = move_uploaded_file($filetemp_anh[$i], $target_anh);
            // Thêm dữ liệu vào cơ sở dữ liệu
            $sql = "update tbl_img_blog set title_img=?, content_img=? where id_blog=? and id=?;";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title_img[$i],$content_img[$i],$blog_id,$id_img_blog[$i]]);
        }else{
            $target_anh = $target_directory . $file_anh[$i];
            $luu = move_uploaded_file($filetemp_anh[$i], $target_anh);
            // Thêm dữ liệu vào cơ sở dữ liệu
            $sql = "update tbl_img_blog set title_img=? , image_blog=?, content_img=? where id_blog=? and id=?;";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title_img[$i],$file_anh[$i],$content_img[$i],$blog_id,$id_img_blog[$i]]);
        }
    }
    if($stmt){
        echo '<script>alert("Sửa thành công!");window.location.href ="blog.php";</script>';
    }
}
?>