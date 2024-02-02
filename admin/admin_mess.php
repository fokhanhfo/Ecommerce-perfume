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
   $delete_message = $conn->prepare("DELETE FROM `tbl_messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:admin_mess.php');
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Messages</title>
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
                                            <h5 class="mb-0">Tin nhắn</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="small text-uppercase bg-body text-muted">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th class="text-end">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                try {
                                                    $sql = $conn->prepare("SELECT * FROM tbl_messages");
                                                    $sql->execute();
                                                    $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
                                                    
                                                    if($rows){
                                                        foreach($rows as $row){
                                                            echo "<tr>";
                                                            echo "<td>" . $row["name"] . "</td>";
                                                            echo "<td>" . $row["email"] . "</td>";
                                                            echo '<td class="text-end">
                                                                    <div class="drodown">
                                                                        <a data-bs-toggle="dropdown" href="#" class="btn p-1" aria-expanded="false">
                                                                            <i class="fa fa-bars" aria-hidden="true"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                                            <a href="#!"  class="dropdown-item" onclick="showInput(this, ' . $row["id"] . ')">View Details</a>
                                                                        </div>
                                                                    </div>
                                                                </td>';
                                                            echo "</tr>";   
                                                            // Thêm dòng input và nút "Hide Input" ẩn đi ban đầu
                                                            echo '<tr id="inputRow-' . $row["id"] . '" style="display:none;">
                                                                    <td colspan="3">
                                                                        <span style="float: left">Message</span>
                                                                        <textarea type="text" id="input-' . $row["id"] . '" class="form-control" placeholder="'. $row["message"] .'" readonly></textarea>
                                                                        <button class="btn btn-secondary btn-sm mt-2" onclick="hideInput(' . $row["id"] . ')" style="float: left; margin-right: 4px">Hide</button>
                                                                        <a class="btn btn-secondary btn-sm mt-2" href="admin_mess.php?delete=' . $row["id"] . ' " onclick=\'return confirm("Delete this message")\' style="float: left">Delete</a>
                                                                    </td>
                                                                </tr>';
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                                                    }
                                                } catch (PDOException $e) {
                                                    echo "Error: " . $e->getMessage();
                                                }
                                                ?>
                                            </table>
                                            <script>
                                                function showInput(button, id) {
                                                    // Hiển thị dòng input tương ứng với nút "View Details" được nhấn
                                                    var inputRow = document.getElementById('inputRow-' + id);
                                                    inputRow.style.display = 'table-row';
                                                    
                                                    // Ẩn các dòng input khác (nếu có)
                                                    var allInputRows = document.querySelectorAll('[id^="inputRow-"]');
                                                    allInputRows.forEach(function(row) {
                                                        if (row.id !== 'inputRow-' + id) {
                                                            row.style.display = 'none';
                                                        }
                                                    });
                                                }
                                            </script>


                                            <script>
                                                function showInput(button, id) {
                                                    // Hiển thị dòng input tương ứng với nút "View Details" được nhấn
                                                    var inputRow = document.getElementById('inputRow-' + id);
                                                    inputRow.style.display = 'table-row';

                                                    // Hiển thị nút "Hide Input"
                                                    var hideButton = inputRow.querySelector('.btn-secondary');
                                                    hideButton.style.display = 'block';

                                                    // Ẩn các dòng input khác (nếu có)
                                                    var allInputRows = document.querySelectorAll('[id^="inputRow-"]');
                                                    allInputRows.forEach(function(row) {
                                                        if (row.id !== 'inputRow-' + id) {
                                                            row.style.display = 'none';
                                                        }
                                                    });
                                                }

                                                function hideInput(id) {
                                                    // Ẩn dòng input khi nút "Hide Input" được nhấn
                                                    var inputRow = document.getElementById('inputRow-' + id);
                                                    inputRow.style.display = 'none';
                                                }
                                            </script>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Thêm sửa đoạn này -->
                    </div>
                </main>
        
                <?php include ('../admin/component/admin_footer.php'); ?>
            </div>
        </div>
        <?php include ('../admin/component/admin_script.php'); ?>          
        
    </body>
</html>

