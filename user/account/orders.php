<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user']['email'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_email = ? ORDER BY id DESC");
if (!$stmt) {
    die("خطأ في إعداد الاستعلام: " . $conn->error);
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>طلباتي</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background-color: #f9f9f9;
            margin: 20px;
            padding: 0;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #e91e63;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #e91e63;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: right;
        }
        ul li {
            margin-bottom: 6px;
        }
        .back-button {
            display: inline-block;
            margin: 10px 20px;
            padding: 8px 18px;
            background-color: #e91e63;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>

    <a href="../index.php" class="back-button">رجوع للصفحة الرئيسية</a>

    <h2>طلباتي</h2>
    <table>
        <tr>
            <th>رقم الطلب</th>
            <th>العنوان</th>
            <th>المنتجات</th>
        </tr>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
                <td>#<?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td>
                    <ul>
                    <?php 
                    $items = json_decode($order['items'], true);
                    if (is_array($items)) {
                        foreach ($items as $id => $qty) {
                            echo "<li>منتج #".htmlspecialchars($id)." - كمية: ".htmlspecialchars($qty)."</li>";
                        }
                    }
                    ?>
                    </ul>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
