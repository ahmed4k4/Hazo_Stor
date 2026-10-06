<?php
include 'includes/db.php';
include 'includes/functions.php';

$featured = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 4");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Hazo Stor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #d48ca6;
            padding: 20px;
            text-align: center;
            color: white;
        }
        h1 {
            margin: 0;
            font-size: 32px;
        }
        .featured {
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }
        .featured h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .product {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product h3 {
            margin: 10px 0 5px;
            font-size: 20px;
            color: #333;
        }
        .product p {
            color: #d48ca6;
            font-weight: bold;
        }
        .product a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #d48ca6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .product a:hover {
            background-color: #bb6e8a;
        }
    </style>
</head>
<body>
    <header>
        <h1>مرحبًا بكم في Hazo Stor</h1>
    </header>

    <section class="featured">
        <h2>منتجات مختارة</h2>
        <div class="products">
            <?php while($p = $featured->fetch_assoc()): ?>
                <div class="product">
                    <img src="assets/images/products/<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                    <h3><?= $p['name'] ?></h3>
                    <p><?= $p['price'] ?> EGP</p>
                    <a href="user/products/view.php?id=<?= $p['id'] ?>">عرض التفاصيل</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>
</html>
