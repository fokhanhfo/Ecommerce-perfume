

<div>
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="/qlkh/admin/index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Thống kê
                    </a>

                    <?php
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        // Kiểm tra xem người dùng đã đăng nhập hay chưa
                        if (isset($_SESSION['admin_id'])) {
                            $adname = isset($_SESSION['adname']) ? $_SESSION['adname'] : '';
                            // Kiểm tra nếu tên đăng nhập là "admin"
                            if ($adname == 'a') {
                                // Hiển thị đoạn mã HTML nếu tên đăng nhập là "admin"
                                echo '<div>
                                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                                            <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
                                            Tài khoản
                                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                        </a>
                                        <div class="collapse" id="collapseAdmin" aria-labelledby="headingO" data-bs-parent="#sidenavAccordion">
                                            <nav class="sb-sidenav-menu-nested nav">
                                                <a class="nav-link" href="/qlkh/admin/admin_list.php">Admins</a>
                                            </nav>
                                            <nav class="sb-sidenav-menu-nested nav">
                                                <a class="nav-link" href="/qlkh/admin/user_list.php">Users</a>
                                            </nav>
                                            <nav class="sb-sidenav-menu-nested nav">
                                                <a class="nav-link" href="admin_register.php">Thêm mới</a>
                                            </nav>
                                        </div>
                                    </div>';
                            }
                        }
                        ?>

                    <div class="sb-sidenav-menu-heading">Quản lý sản phẩm</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Thương hiệu
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="/qlkh/admin/category.php">Quản lý</a>
                        </nav>
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="/qlkh/admin/category/saveOrUpdate.php">Thêm mới</a>
                        </nav>
                    </div>

                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Sản phẩm
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="/qlkh/admin/product.php" style="margin-left: 0">Quản lý</a>
                        <a class="nav-link" href="/qlkh/admin/product/saveOrUpdate.php">Thêm mới</a>   
                    </div>
                    <!-- đơn hàng -->
                    <div class="sb-sidenav-menu-heading">Quản lý đơn hàng</div>
                        <a class="nav-link" href="/qlkh/admin/order.php" style="margin-left: 0">Danh sách</a>
                        <a class="nav-link" href="/qlkh/admin/phieunhap.php" style="margin-left: 0">Phiếu nhập</a>
                        <a class="nav-link" href="/qlkh/admin/nhaphang.php" style="margin-left: 0">Nhập hàng</a>
                    <!-- <div class="sb-sidenav-menu-heading">Liên lạc</div>
                        <a class="nav-link" href="/qlkh/admin/admin_mess.php" style="margin-left: 0">Tin nhắn</a> -->
                    <!-- comment -->
                    <!-- <div class="sb-sidenav-menu-heading">Tin tức</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBlog" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Tin tức
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseBlog" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/qlkh/admin/blog.php">Quản lí tin tức</a>
                                </nav>
                            </div> -->
                            <!-- endcomment -->
                    <div class="sb-sidenav-menu-heading">Quản lý nhà cung cấp</div>
                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages1" aria-expanded="false" aria-controls="collapsePages1">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Nhà cung cấp
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages1" aria-labelledby="headingTwo1" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages1">
                            <a class="nav-link px-4 py-3" href="/qlkh/admin/nhacungcap.php">Quản lý</a>
                            <a class="nav-link px-4 py-3" href="/qlkh/admin/nhacungcap/saveOrUpdate.php">Thêm mới</a>
                        </nav>
                    </div>

                </div>
                
            </div>
            <div class="sb-sidenav-footer">
                Nhóm 8
            </div>
        </nav>
    </div>
</div>