<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $cat = $_POST['category_id'];
    $stock = $_POST['stock'];

    $img = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/images/products/$img");

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, category_id, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsdis", $name, $price, $desc, $cat, $stock, $img);
    $stmt->execute();
    header("Location: list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة منتج</title>
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

        button {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>إضافة منتج جديد</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>الاسم:</label>
        <input type="text" name="name" required>

        <label>السعر:</label>
        <input type="number" step="0.01" name="price" required>

        <label>الوصف:</label>
        <textarea name="description" rows="4"></textarea>

        <label>التصنيف:</label>
        <select name="category_id" required>
            <option disabled selected>اختر تصنيفًا</option>
            <?php while($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>الكمية في المخزون:</label>
        <input type="number" name="stock" required>

        <label>صورة المنتج:</label>
        <input type="file" name="image" required>

        <button type="submit">إضافة</button>
    </form>
</body>
</html>
