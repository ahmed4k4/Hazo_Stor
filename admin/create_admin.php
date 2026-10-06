<?php
include '../includes/db.php';

$username = 'admin';
$password = password_hash('1234', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "تم إنشاء الأدمن بنجاح.";
} else {
    echo "فشل: " . $conn->error;
}
?>
