<?php
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // يمكنك إضافة هاش لكلمة المرور هنا لتحسين الأمان:
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <title>إنشاء حساب</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 320px;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            color: #e91e63;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin-bottom: 18px;
            border: 1.8px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }
        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="password"]:focus {
            border-color: #e91e63;
            outline: none;
        }
        form button {
            width: 100%;
            padding: 12px;
            background-color: #e91e63;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #c2185b;
        }
        .login-link {
            margin-top: 15px;
            display: block;
            color: #777;
            font-size: 14px;
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>تسجيل حساب جديد</h2>
        <form method="POST" autocomplete="off">
            <input type="text" name="name" placeholder="الاسم" required />
            <input type="email" name="email" placeholder="البريد الإلكتروني" required />
            <input type="password" name="password" placeholder="كلمة المرور" required />
            <button type="submit">تسجيل</button>
        </form>
        <a href="login.php" class="login-link">هل لديك حساب؟ تسجيل دخول</a>
    </div>
</body>
</html>
