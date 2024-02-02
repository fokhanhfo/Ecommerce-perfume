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
if(isset($_GET['delete_blog'])){
    $delete_blog=$_GET['delete_blog'];
    $delete_order = $conn->prepare("DELETE FROM `tbl_blog` WHERE id = ?");
    $delete_order->execute([$delete_blog]);
    header('location:blog.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <?php include ('../admin/component/admin_head.php'); ?>
    <style>
        main{
            
        }
        .container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
        }

        .block {
        width: calc(33.33%);
        margin-bottom: 30px;
        padding: 0 15px;
        box-sizing: border-box;
        flex-direction: column;
        }

        .image-container {
        width: 100%; /* Kích thước cố định cho khối chứa ảnh */
        height: 254.96px;
        overflow: hidden; /* Ẩn các phần tử vượt ra ngoài kích thước của khối */
        }

        .image {
        width: 100%; /* Ảnh tỷ lệ theo kích thước của khối chứa */
        height: 100%;
        }

        .content {
        position: relative;
        padding: 30px 25px 25px 25px;
        margin: -35px 25px 0 25px;
        background-color: white;
        z-index: 1;
        }

        .date {
        font-size: 14px;
        margin-bottom: 10px;
        }

        .title {
        font-size: 18px;
        margin-bottom: 10px;
        }

        .read-more {
        text-decoration: none;
        display: inline-block;
        color: #111111;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 4px;
        text-transform: uppercase;
        padding: 3px 0;
        position: relative;
        }

        a:hover{
            color: #111111;
            text-decoration: none;
        }

        .read-more::after {
            content: "";
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: 2.5px;
            background-color: red; /* Màu sắc của đường kẻ */
            transition: width 0.3s ease; /* Hiệu ứng dãn đường kẻ */
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        /* Hiệu ứng khi di vào chữ "Đọc thêm" */
        .read-more:hover::after {
            width: 50%; /* Khi di vào, đường kẻ dãn ra hết chiều rộng của chữ "Đọc thêm" */
            height: 1px;
        }

        .table_content{
            width: 100%;
        }

        .paragraph-container {
            overflow: hidden; /* Ẩn phần tử nếu nội dung vượt quá kích thước */
        }

        #my-paragraph {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Chỉ hiển thị 2 dòng */
            -webkit-box-orient: vertical;
            line-height: 1.2em; /* Điều chỉnh khoảng cách giữa các dòng */
            max-height: 2.4em; /* Khoảng cách giữa các dòng (2 dòng) x line-height (1.2em) */
            overflow: hidden; /* Ẩn phần tử nếu nội dung vượt quá kích thước */
            text-overflow: ellipsis; /* Hiển thị dấu "..." khi nội dung bị ẩn đi */
            white-space: normal; /* Cho phép phần tử xuống dòng tự động nếu cần */
        }

        a.button-like {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db; /* Màu nền */
            color: #fff; /* Màu chữ */
            text-decoration: none; /* Loại bỏ gạch chân mặc định */
            border: none;
            border-radius: 5px; /* Góc bo tròn */
            cursor: pointer;
            text-align: center;
        }

        a.button-like:hover {
            background-color: #2980b9; /* Màu nền khi di chuột qua */
        }
        
        @media screen and (max-width: 1200px) {
        .block {
            width: 50%; /* Khi màn hình nhỏ hơn hoặc bằng 768px, 1 dòng chỉ hiển thị 2 khối */
        }

        @media screen and (max-width: 768px) {
            .block {
                width: 100%; /* Khi màn hình nhỏ hơn hoặc bằng 480px, 1 dòng chỉ hiển thị 1 khối */
            }
        }
        
    }
    </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="/qlkh/admin/index.php">Admin</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form method="post" class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <?php 
                $searchValue = isset($_POST['search_blog']) ? htmlspecialchars($_POST['search_blog'], ENT_QUOTES, 'UTF-8') : '';  
                ?>
                <div class="input-group">
                    <input name="search_blog" class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button name="button_search" class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                <div>
                    <a class="button-like" style="margin-left: 100px;margin-top:10px;" href="blog_insert.php">Thêm Tin Tức</a>
                </div>
                <div class="container">
                    <?php
                    $sql="SELECT * FROM tbl_blog where 1 ORDER BY id DESC";
                    if(isset($_POST['button_search'])){
                        $sql="SELECT * FROM tbl_blog where 1 ";
                        $title_search=$_POST['search_blog'];
                        $search_terms = explode(" ", $_POST['search_blog']);
                        if(!empty($title_search)){
                            foreach ($search_terms as $term) {
                                $sql .= " AND title LIKE '%$term%'";
                            }
                        }else{
                            $sql="SELECT * FROM tbl_blog ORDER BY id DESC;";
                        }

                    }
                        $select_blog = $conn->prepare($sql); 
                        $select_blog->execute();
                        if($select_blog->rowCount() > 0){
                        while($fetch_blog = $select_blog->fetch(PDO::FETCH_ASSOC)){
                            $formatted_time_post = date("d F Y", strtotime($fetch_blog["time_post"]));
                    ?>
                    <div class="block paragraph-container">
                        <div class="image-container">
                        <img src="../uploaded_img/<?= $fetch_blog['blog_image'] ?>" alt="Image 2" class="image">
                        </div>
                        <div class="content">
                        <p class="date"><?= $formatted_time_post ?></p>
                        <h2 class="title" id="my-paragraph"><?= $fetch_blog['title'] ?></h2>
                        <div>
                            <a href="blog_update.php?update_blog=<?= $fetch_blog['id']; ?>" class="read-more">Sửa tin tức</a>
                            <a href="blog_details.php?blog_id=<?= $fetch_blog['id']; ?>" class="read-more">Quản lí nội dung</a>
                            <a href="blog.php?delete_blog=<?= $fetch_blog['id']; ?>" class="read-more" onclick="return confirm('update?');">Xóa tin tức</a>
                        </div>
                        </div>
                    </div>
                    <?php
                        }
                        }else{
                            echo '<p class="empty">Không có tin tức nào !</p>';
                        }
                    ?>
                    <!-- Thêm các khối khác nếu cần -->
                </div>
                </main>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>
    </body>
</html>
<?php
    if (isset($_POST['themnoidung'])) {
        $sonoidung = $_POST['sonoidunghidden'];
        $id_blog = $_POST['id_blog'];
        $title_img=array();
        $file_anh = array();
        $filetemp_anh = array();
        $content = array();
    
        // Thu thập thông tin ảnh và nội dung từ biểu mẫu
        for ($i = 0; $i < $sonoidung; $i++) {
            $file_anh[$i] = $_FILES["anh$i"]["name"];
            $content[$i] = $_POST["content$i"];
            $title_img[$i] = $_POST["title_img$i"];
            $filetemp_anh[$i] = $_FILES["anh$i"]["tmp_name"];
        }
    
        // Thực hiện lưu trữ ảnh và thêm thông tin vào cơ sở dữ liệu
        $target_directory = "../uploaded_img/";
        for ($i = 0; $i < $sonoidung; $i++) {
            $target_anh = $target_directory . $file_anh[$i];
            $luu = move_uploaded_file($filetemp_anh[$i], $target_anh);
    
            // Thêm dữ liệu vào cơ sở dữ liệu
            $sql = "INSERT INTO tbl_img_blog (id_blog,title_img ,image_blog, content_img) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_blog,$title_img[$i] ,$file_anh[$i], $content[$i]]);
        }
    
        if ($stmt) {
            echo '<script>alert("Thêm thành công tin tức!");</script>';
        }
    }
?>