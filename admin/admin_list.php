<?php

include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
} else {
    header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   if($admin_id == 1) {
    if($delete_id == 1) {
        echo"
         <script type = 'text/javascript'>
            alert('không thể xóa tk gốc');
         </script>";
    } else {
   $delete_admins = $conn->prepare("DELETE FROM `tbl_admin` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_list.php');}
    } else {
        echo"
         <script type = 'text/javascript'>
            alert('bạn không phải admin');
         </script>";
    } 
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Accounts</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <?php include ('../admin/component/admin_head.php'); ?>
        <style type="text/css">
            body{
            margin-top:20px;
            background:#ffffff;
            }

            .table>:not(caption)>*>* {
                padding: 0.75rem 1.25rem;
                border-bottom-width: 1px;
                border-color: inherit;
            }
            table th, table td  {
                font-weight: 600;
                background-color: #343a40 !important;
                vertical-align: middle;
                text-align: center;
                color: white;
                
            }
            table td {
                background-color: #fff !important;
                color: #000;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include ('../admin/component/admin_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include ('../admin/component/admin_layout.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

                        <div class="container">
                            <div class="row">
                                <div class="col-12 mb-3 mb-lg-5">
                                    <div class="overflow-hidden card table-nowrap table-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Admin</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="small text-uppercase bg-body text-muted">
                                                    <tr>
                                                        <th>Acc</th>
                                                        <th>Name</th>
                                                        <th class="text-end">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                try {
                                                    $sql = $conn->prepare("SELECT * FROM tbl_admin");
                                                    $sql->execute();
                                                    $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
                                                    
                                                    if($rows){
                                                        foreach($rows as $row){
                                                            echo "<tr>";
                                                            echo "<td>" . $row["adname"] . "</td>";
                                                            echo "<td>" . $row["name"] . "</td>";
                                                            echo '<td class="text-end">
                                                                    <div class="drodown">
                                                                        <a data-bs-toggle="dropdown" href="#" class="btn p-1" aria-expanded="false">
                                                                            <i class="fa fa-bars" aria-hidden="true"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                                            <a href="admin_list.php?delete=' . $row["id"] . '" onclick=\'return confirm("Are you sure?")\' class="dropdown-item">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </td>';
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                                                    }
                                                } catch (PDOException $e) {
                                                    echo "Error: " . $e->getMessage();
                                                }
                                                ?>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Thêm sửa đoạn này -->
                    </div>
                </main>
                <script type="text/javascript">
    document.getElementById('showButton').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'flex';
});

document.getElementById('popup').addEventListener('click', function(event) {
    // Ẩn cửa sổ popup khi click bên ngoài cửa sổ
    if (event.target === this) {
        this.style.display = 'none';
    }
});

</script>
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>          
        
    </body>
</html>

