<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $cat = $_POST['category_id'];
    $stock = $_POST['stock'];

    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/images/products/$img");
        $conn->query("UPDATE products SET image='$img' WHERE id=$id");
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, category_id=?, stock=? WHERE id=?");
    $stmt->bind_param("sdsdii", $name, $price, $desc, $cat, $stock, $id);
    $stmt->execute();
    header("Location: list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل منتج</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f2f2f2;
            direction: rtl;
            text-align: right;
            padding: 40px;
        }

        h2 {
            color: #333;
            margin-bottom: 25px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        img {
            max-width: 100px;
            display: block;
            margin-top: 10px;
        }

        button {
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h2>تعديل المنتج</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>الاسم:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>السعر:</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

        <label>الوصف:</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

        <label>التصنيف:</label>
        <select name="category_id" required>
            <?php while($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>الكمية في المخزون:</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required>

        <label>تحديث الصورة:</label>
        <input type="file" name="image">
        <?php if (!empty($product['image'])): ?>
            <img src="../../assets/images/products/<?= $product['image'] ?>" alt="صورة المنتج الحالية">
        <?php endif; ?>

        <button type="submit">تحديث</button>
    </form>
</body>
</html>
