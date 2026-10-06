<?php
session_start();

function isAdmin() {
    return isset($_SESSION['admin']);
}

// حماية صفحات الأدمن
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit;
    }
}
?>
