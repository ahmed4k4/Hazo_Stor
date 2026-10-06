<?php
include '../includes/db.php';
include '../includes/auth.php';

if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$orderCount = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$visitorCount = file_exists('../visitors.txt') ? file_get_contents('../visitors.txt') : 0;
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الإحصائيات</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .stats-container {
            max-width: 600px;
            margin: 80px auto;
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #d63384;
            text-align: center;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            font-size: 18px;
            margin: 15px 0;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
        }

        li span {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="stats-container">
        <h2>لوحة الإحصائيات</h2>
        <ul>
            <li><span>عدد المنتجات:</span> <?= $productCount ?></li>
            <li><span>عدد الطلبات:</span> <?= $orderCount ?></li>
            <li><span>عدد المستخدمين:</span> <?= $userCount ?></li>
            <li><span>عدد الزوار:</span> <?= $visitorCount ?></li>
        </ul>
    </div>
</body>
</html>
