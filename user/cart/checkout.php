<?php
session_start();
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $items = json_encode($_SESSION['cart']);

    $stmt = $conn->prepare("INSERT INTO orders (name, phone, address, items) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        die("خطأ في التحضير: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $phone, $address, $items);
    $stmt->execute();

    unset($_SESSION['cart']);
    header("Location: ../../user/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إتمام الطلب - Hazo Stor</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background-color: #f5f5f5;
            padding: 30px;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 25px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #e91e63;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            width: 100%;
            background-color: #e91e63;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>معلومات التوصيل</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="الاسم بالكامل" required>
            <input type="text" name="phone" placeholder="رقم الهاتف" required>
            <input type="text" name="address" placeholder="العنوان بالتفصيل" required>
            <button type="submit">تأكيد الطلب</button>
        </form>
    </div>

</body>
</html>
