<!-- comment -->
<section>
        <div class="container my-5 text-dark">
            <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-10 col-xl-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-dark mb-0">ĐÁNH GIÁ SẢN PHẨM</h4>
                </div>
                <?php
                    
                    // Fetch all comments
                    $select_comment = $conn->prepare("SELECT DATE_FORMAT(comment.time_post, '%d/%m/%Y %H:%i:%s') AS time_hour, USER.full_name, COMMENT.content, comment.review, product.name FROM tbl_user AS user, tbl_product AS product, tbl_comment AS comment WHERE comment.user_id = user.id and COMMENT.product_id = product.id AND product.id = $product_id;");
                    $select_comment->execute();
                    if($select_comment->rowCount()>0){
                    $fetch_comments = $select_comment->fetchAll(PDO::FETCH_ASSOC);
                    
                    // // Số comment trên mỗi trang
                    // $commentsPerPage = 3;
                    
                    // // Số trang hiện tại
                    // if (isset($_GET['page'])) {
                    //     $currentPage = $_GET['page'];
                    // } else {
                    //     $currentPage = 1;
                    // }
                    
                    // // Tổng số comment
                    // $totalComments = count($fetch_comment);
                    
                    // // Tính tổng số trang
                    // $totalPages = ceil($totalComments / $commentsPerPage);
                    
                    // // Tính chỉ số bắt đầu và kết thúc của comment mà bạn muốn hiển thị trên trang hiện tại
                    // $startIndex = ($currentPage - 1) * $commentsPerPage;
                    // $endIndex = min($startIndex + $commentsPerPage - 1, $totalComments - 1);
                    
                    // // Lấy danh sách comment cho trang hiện tại
                    // $commentsOnCurrentPage = array_slice($fetch_comment, $startIndex, $commentsPerPage);
                    $dem=0;
                    foreach ($fetch_comments as $fetch_comment) {
                        $dem++;
                        // Rest of your code to display comments          
                    ?>
                        <div class="card mb-3 comment-list item">
                            <div class="card-body">
                                <div class="d-flex flex-start">
                                <img class="rounded-circle shadow-1-strong me-3"
                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(26).webp" alt="avatar" width="40"
                                    height="40" />
                                <div class="w-100">
                                    <div  class="d-flex justify-content-between align-items-center mb-3">
                                    <div style="margin-left:10px;" class="text-primary fw-bold mb-0">
                                        <?=$fetch_comment['full_name']?><br>
                                        <div style="margin-bottom:5px;" class="d-flex flex-row">
                                            <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $fetch_comment['review']) {
                                                        echo '<i class="fa fa-star"></i>';
                                                    } else {
                                                        echo '<i class="fa fa-star-o"></i>';
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <span class="text-dark ms-2"><?=$fetch_comment['content']?></span>
                                        </div>
                                    <p class="mb-0"><?= $fetch_comment['time_hour']?></p>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                <!-- Tạo các liên kết phân trang -->
                <button id="showMore" class="button-see-more">Xem thêm</button>
                <?php
                    }else{
                        echo 'Chưa có đánh giá nào';
                    }
                ?>
                <button id="hideItems" class="button-see-more" style="display: none;">Ẩn</button>
            </div>
            </div>
        </div>
    </section>
    <script>
        // Sử dụng JavaScript để tự động tăng chiều cao
        function autoExpandTextArea(element) {
            element.style.height = "auto";
            element.style.height = (element.scrollHeight) + "px";
        }
        const items = document.querySelectorAll('.item');
        const showMoreButton = document.getElementById('showMore');
        const hideItemsButton = document.getElementById('hideItems');

        let visibleItemCount = 4;

        for (let i = 0; i < visibleItemCount; i++) {
            if (items[i]) {
                items[i].style.display = 'block';
            }
        }

        showMoreButton.addEventListener('click', function () {
            visibleItemCount += 4;

            for (let i = 0; i < visibleItemCount; i++) {
                if (items[i]) {
                    items[i].style.display = 'block';
                }
            }

            if (visibleItemCount >= items.length) {
                showMoreButton.style.display = 'none';
            }
            hideItemsButton.style.display = 'inline-block';
        });

        hideItemsButton.addEventListener('click', function () {
            visibleItemCount = 4;

            for (let i = visibleItemCount; i < items.length; i++) {
                items[i].style.display = 'none';
            }

            showMoreButton.style.display = 'inline-block';
            hideItemsButton.style.display = 'none';
        });

    </script>
    <!-- end-comment -->