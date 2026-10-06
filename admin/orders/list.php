<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الطلبات</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
        }

        table {
            margin: 0 auto;
            width: 90%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <h2>قائمة الطلبات</h2>
    <table>
        <tr>
            <th>رقم الطلب</th>
            <th>الاسم</th>
            <th>رقم الهاتف</th>
            <th>العنوان</th>
            <th>المنتجات</th>
            <th>تاريخ الإنشاء</th>
            <th>التحكم</th>
        </tr>
        <?php while($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['name']) ?></td>
                <td><?= htmlspecialchars($order['phone']) ?></td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td>
<?php
$items = json_decode($order['items'], true);
if (is_array($items)) {
    foreach ($items as $product_id => $qty) {
        $product_id = intval($product_id);
        $product = $conn->query("SELECT name FROM products WHERE id = $product_id")->fetch_assoc();
        $name = $product ? $product['name'] : "منتج غير معروف (ID: $product_id)";
        echo htmlspecialchars($name) . " (x" . intval($qty) . ")<br>";
    }
} else {
    echo "بيانات غير صالحة";
}
?>
</td>

                <td><?= $order['created_at'] ?></td>
                <td><a href="view.php?id=<?= $order['id'] ?>">عرض</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a class="back-link" href="../dashboard.php">← الرجوع للوحة التحكم</a>
</body>
</html>
