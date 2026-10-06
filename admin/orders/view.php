<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$id = intval($_GET['id']); // أمان ضد SQL Injection
$order = $conn->query("SELECT * FROM orders WHERE id = $id")->fetch_assoc();

if (!$order) {
    die("الطلب غير موجود.");
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل الطلب</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
            direction: rtl;
            text-align: right;
        }

        h2, h3 {
            color: #333;
        }

        .order-details {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .order-items {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .order-items pre {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
        }

        a.back-link {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="order-details">
        <h2>تفاصيل الطلب رقم <?= $order['id'] ?></h2>
        <p><strong>الاسم:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>رقم الهاتف:</strong> <?= htmlspecialchars($order['phone']) ?></p>
        <p><strong>العنوان:</strong> <?= htmlspecialchars($order['address']) ?></p>
        <p><strong>تاريخ الإنشاء:</strong> <?= $order['created_at'] ?></p>
    </div>

    <div class="order-items">
        <h3>العناصر المطلوبة:</h3>
        <?php
            $item_data = json_decode($order['items'], true);
if (!is_array($item_data)) {
    echo "<p>لا يمكن قراءة العناصر المطلوبة.</p>";
} else {
    $grand_total = 0;

    echo '<table style="width: 100%; border-collapse: collapse; text-align: right; direction: rtl;">';
    echo '<tr style="background: #007bff; color: white;">
            <th style="padding: 10px;">الصورة</th>
            <th style="padding: 10px;">المنتج</th>
            <th style="padding: 10px;">الكمية</th>
            <th style="padding: 10px;">سعر القطعة</th>
            <th style="padding: 10px;">الإجمالي</th>
          </tr>';

    foreach ($item_data as $product_id => $qty) {
        $product_id = intval($product_id);
        $product = $conn->query("SELECT name, price, image FROM products WHERE id = $product_id")->fetch_assoc();

        if ($product) {
            $name = htmlspecialchars($product['name']);
            $price = number_format($product['price'], 2);
            $total = number_format($product['price'] * $qty, 2);
            $img = "../../assets/images/products/" . htmlspecialchars($product['image']);

            $grand_total += $product['price'] * $qty;

            echo "<tr style='background-color: #fff; border-bottom: 1px solid #ccc;'>
                    <td style='padding: 10px;'><img src='$img' alt='$name' width='60'></td>
                    <td style='padding: 10px;'>$name</td>
                    <td style='padding: 10px;'>$qty</td>
                    <td style='padding: 10px;'>$price EGP</td>
                    <td style='padding: 10px;'>$total EGP</td>
                  </tr>";
        } else {
            echo "<tr><td colspan='5' style='padding: 10px;'>منتج غير موجود (ID: $product_id)</td></tr>";
        }
    }

    // صف الإجمالي الكلي
    $grand_total_formatted = number_format($grand_total, 2);
    echo "<tr style='background-color: #f1f1f1; font-weight: bold;'>
            <td colspan='4' style='padding: 10px;'>الإجمالي الكلي</td>
            <td style='padding: 10px;'>$grand_total_formatted EGP</td>
          </tr>";

    echo '</table>';
}

        ?>

    </div>

    <a class="back-link" href="list.php">← الرجوع إلى قائمة الطلبات</a>
</body>
</html>
