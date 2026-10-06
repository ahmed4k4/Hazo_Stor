<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Hazo Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../user/products/list.php">المنتجات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/cart/view.php">السلة <span class="badge bg-light text-dark cart-count">0</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/wishlist.php">المفضلة</a>
                </li>
            </ul>
            <form class="d-flex search-form" action="../user/products/list.php" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="ابحث عن منتج..." aria-label="Search">
                <button class="btn btn-outline-light" type="submit">بحث</button>
            </form>
            <ul class="navbar-nav">
                <?php if (is_logged_in()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> حسابي
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../user/account/orders.php">طلباتي</a></li>
                            <li><a class="dropdown-item" href="../user/wishlist.php">قائمة المفضلة</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (is_admin()): ?>
                                <li><a class="dropdown-item" href="../admin/dashboard.php">لوحة التحكم</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="../includes/logout.php">تسجيل الخروج</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/account/login.php">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/account/register.php">إنشاء حساب</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="alerts-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>

<script>
// تحديث عدد العناصر في السلة عند تحميل الصفحة
$(document).ready(function() {
    $.ajax({
        url: '../includes/get_cart_count.php',
        method: 'GET',
        success: function(response) {
            $('.cart-count').text(response.count);
        }
    });
});
</script>