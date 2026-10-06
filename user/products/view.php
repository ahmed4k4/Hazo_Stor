<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// استعلام المنتج
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("المنتج غير موجود.");
}

// استعلام التعليقات
$commentsStmt = $conn->prepare("SELECT * FROM comments WHERE product_id = ? ORDER BY id DESC");
$commentsStmt->bind_param("i", $id);
$commentsStmt->execute();
$comments = $commentsStmt->get_result();

// إضافة تعليق
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = 'زائر';
    $comment = $_POST['comment'];

    $insert = $conn->prepare("INSERT INTO comments (product_id, user, content) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $id, $user, $comment);
    $insert->execute();

    header("Location: view.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        h2 {
            color: #444;
            margin-bottom: 10px;
        }

        .price {
            font-size: 20px;
            color: #e91e63;
            margin-bottom: 15px;
        }

        .description {
            line-height: 1.8;
            margin-bottom: 30px;
        }

        form textarea {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        form button {
            background-color: #e91e63;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #d81b60;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: #f1f1f1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }

        li strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <img src="../../assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="" width="200">
        <p class="price">السعر: <?= $product['price'] ?> EGP</p>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <!-- زر إضافة للسلة -->
        <form method="POST" action="../cart/view.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; margin: 15px 0;">
                🛒 أضف إلى السلة
            </button>
        </form>

        <h3>أضف تعليق:</h3>
        <form method="POST">
            <textarea name="comment" placeholder="اكتب تعليقك هنا..." required></textarea>
            <button type="submit">إرسال</button>
        </form>

        <h3>التعليقات:</h3>
        <ul>
            <?php while ($c = $comments->fetch_assoc()): ?>
                <li><strong><?= htmlspecialchars($c['user']) ?>:</strong> <?= htmlspecialchars($c['content']) ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>
