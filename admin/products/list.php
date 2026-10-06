<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// تحديث حالة featured عند التغيير
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $stmt = $conn->prepare("UPDATE products SET featured = ? WHERE id = ?");
    $stmt->bind_param("ii", $featured, $product_id);
    $stmt->execute();
    $stmt->close();
}

$products = $conn->query("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة المنتجات</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f9f9f9;
            direction: rtl;
            text-align: right;
            padding: 40px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        a.add-button {
            display: inline-block;
            margin-bottom: 15px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        a.add-button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        img {
            border-radius: 4px;
        }

        .actions a {
            margin: 0 5px;
            text-decoration: none;
            color: #007bff;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        form.featured-form {
            margin: 0;
        }
    </style>
</head>
<body>
    <h2>المنتجات</h2>
    <a href="add.php" class="add-button">+ إضافة منتج جديد</a>
    <table>
        <tr>
            <th>الاسم</th>
            <th>السعر</th>
            <th>الصورة</th>
            <th>التصنيف</th>
            <th>منتج مختار</th>
            <th>التحكم</th>
        </tr>
        <?php while($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?> EGP</td>
                <td><img src="../../assets/images/products/<?= htmlspecialchars($row['image']) ?>" width="60" alt="صورة المنتج"></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>
                    <form method="POST" class="featured-form">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <input type="checkbox" name="featured" onchange="this.form.submit()" <?= $row['featured'] ? 'checked' : '' ?>>
                    </form>
                </td>
                <td class="actions">
                    <a href="edit.php?id=<?= $row['id'] ?>">تعديل</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
