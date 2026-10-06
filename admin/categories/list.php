<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة التصنيفات</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .add-button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .add-button:hover {
            background-color: #218838;
        }

        table {
            width: 60%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>قائمة التصنيفات</h2>
    <a href="add.php" class="add-button">+ إضافة تصنيف</a>
    <table>
        <tr>
            <th>الاسم</th>
            <th>التحكم</th>
        </tr>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td>
                    <a href="delete.php?id=<?= $cat['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
