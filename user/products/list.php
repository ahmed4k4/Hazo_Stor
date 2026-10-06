<?php
include '../../includes/db.php';

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$order = $_GET['order'] ?? '';

$query = "SELECT * FROM products WHERE 1";
if ($category) {
    $query .= " AND category_id = " . intval($category);
}
if ($search) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}
if ($order === 'price_asc') {
    $query .= " ORDER BY price ASC";
} elseif ($order === 'price_desc') {
    $query .= " ORDER BY price DESC";
} elseif ($order === 'az') {
    $query .= " ORDER BY name ASC";
} elseif ($order === 'za') {
    $query .= " ORDER BY name DESC";
} else {
    $query .= " ORDER BY id DESC";
}

$products = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>كل المنتجات</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 30px;
            margin: 0;
        }

        h2 {
            text-align: center;
            color: #e91e63;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        input[type="text"], select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            min-width: 200px;
        }

        button {
            padding: 8px 16px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c2185b;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 200px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-card p {
            margin: 8px 0;
        }

        .product-card a {
            display: inline-block;
            margin-top: 5px;
            padding: 6px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .product-card a:hover {
            background-color: #388E3C;
        }
    </style>
</head>
<body>

    <h2>كل المنتجات</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="بحث باسم المنتج" value="<?= htmlspecialchars($search) ?>">
        <select name="order">
            <option value="">ترتيب</option>
            <option value="price_asc" <?= $order === 'price_asc' ? 'selected' : '' ?>>السعر: من الأقل للأعلى</option>
            <option value="price_desc" <?= $order === 'price_desc' ? 'selected' : '' ?>>السعر: من الأعلى للأقل</option>
            <option value="az" <?= $order === 'az' ? 'selected' : '' ?>>الترتيب الأبجدي A-Z</option>
            <option value="za" <?= $order === 'za' ? 'selected' : '' ?>>الترتيب الأبجدي Z-A</option>
        </select>
        <button type="submit">تصفية</button>
    </form>

    <div class="products">
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="../../assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <p><strong><?= htmlspecialchars($product['name']) ?></strong></p>
                <p><?= $product['price'] ?> EGP</p>
                <a href="view.php?id=<?= $product['id'] ?>">عرض التفاصيل</a>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>






