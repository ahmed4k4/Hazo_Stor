<?php
include '../includes/db.php';
include '../includes/auth.php';

if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

$ordersCount = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$productsCount = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$usersCount = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-header">لوحة تحكم الأدمن</div>

    <div class="admin-nav">
        <a href="stats.php">عرض الإحصائيات</a>
        <a href="products/list.php">المنتجات</a>
        <a href="orders/list.php">الطلبات</a>
        <a href="categories/list.php">التصنيفات</a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>

    <div class="admin-container">
        <h2>مرحبًا بك 👋</h2>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex:1; min-width: 200px; background-color:#f1c40f; color:white; padding: 20px; border-radius: 8px;">
                <h3>عدد الطلبات</h3>
                <p style="font-size: 28px;"><?= $ordersCount ?></p>
            </div>
            <div style="flex:1; min-width: 200px; background-color:#27ae60; color:white; padding: 20px; border-radius: 8px;">
                <h3>عدد المنتجات</h3>
                <p style="font-size: 28px;"><?= $productsCount ?></p>
            </div>
            <div style="flex:1; min-width: 200px; background-color:#2980b9; color:white; padding: 20px; border-radius: 8px;">
                <h3>عدد المستخدمين</h3>
                <p style="font-size: 28px;"><?= $usersCount ?></p>
            </div>
        </div>
    </div>
</body>
</html>
