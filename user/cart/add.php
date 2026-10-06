<?php
session_start();
include '../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || empty($_SESSION['user']['name'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

$username = $_SESSION['user']['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);

    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'رقم المنتج غير صحيح']);
        exit;
    }

    // التحقق إذا كان المنتج موجود مسبقاً لهذا المستخدم
    $check_stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE username = ? AND product_id = ?");
    $check_stmt->bind_param("si", $username, $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // إذا كان موجود، قم بتحديث الكمية
        $new_qty = $row['quantity'] + 1;
        $update_stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_qty, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // إذا لم يكن موجود، أضف عنصر جديد
        $insert_stmt = $conn->prepare("INSERT INTO cart_items (username, product_id, quantity) VALUES (?, ?, 1)");
        $insert_stmt->bind_param("si", $username, $product_id);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $check_stmt->close();

    echo json_encode(['success' => true, 'message' => 'تمت إضافة المنتج إلى السلة']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مدعومة']);
