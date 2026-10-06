<?php
session_start();
include '../../includes/db.php';

// تأكد من أن المستخدم مسجل دخول
if (!isset($_SESSION['user'])) {
    header("Location: ../account/login.php");
    exit;
}

$username = $_SESSION['user']['name']; // هذا ما يتم تخزينه في عمود username في جدول cart_items

// التعامل مع POST (تحديث، حذف، تفريغ السلة)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_quantity'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = max(1, intval($_POST['quantity']));
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE username = ? AND product_id = ?");
        $stmt->bind_param("isi", $quantity, $username, $product_id);
        $stmt->execute();
    }

    if (isset($_POST['remove_item'])) {
        $product_id = intval($_POST['product_id']);
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE username = ? AND product_id = ?");
        $stmt->bind_param("si", $username, $product_id);
        $stmt->execute();
    }

    if (isset($_POST['clear_cart'])) {
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
    }
}

// تحميل عناصر السلة من قاعدة البيانات
$items = [];
$total = 0;

$stmt = $conn->prepare("
    SELECT p.*, c.quantity 
    FROM cart_items c 
    JOIN products p ON p.id = c.product_id 
    WHERE c.username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>سلة المشتريات - Hazo Stor</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background-color: #f5f5f5;
            padding: 20px;
            margin: 0;
        }
        h2 {
            color: #e91e63;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        th {
            background-color: #f8f8f8;
            color: #555;
        }
        .total {
            font-size: 18px;
            margin-top: 15px;
            color: #222;
        }
        .btn {
            padding: 8px 12px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #c2185b;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        .btn-warning {
            background-color: #ff9800;
        }
        .btn-warning:hover {
            background-color: #fb8c00;
        }
        .empty {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            color: #856404;
            margin-top: 20px;
        }
        .actions {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        input[type="number"] {
            width: 60px;
            padding: 5px;
        }
    </style>
</head>
<body>

    <h2>سلة المشتريات</h2>

    <?php if (count($items) > 0): ?>
        <form method="POST">
            <table>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                    <th>التحكم</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                            <button class="btn btn-warning" name="update_quantity">تحديث</button>
                        </td>
                        <td><?= $item['price'] ?> EGP</td>
                        <td><?= $item['subtotal'] ?> EGP</td>
                        <td>
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button class="btn btn-danger" name="remove_item">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="actions">
                <p class="total">المجموع: <strong><?= $total ?> EGP</strong></p>
                <div>
                    <a class="btn" href="checkout.php">متابعة الدفع</a>
                    <button class="btn btn-danger" name="clear_cart">إفراغ السلة</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="empty">
            <p>🚫 السلة فارغة، أضف منتجاتك أولاً!</p>
        </div>
    <?php endif; ?>

</body>
</html>
