<?php
function renderHeader($title = "Hazo Stor") {
    echo "<!DOCTYPE html>";
    echo "<html lang='ar'>";
    echo "<head><meta charset='UTF-8'><title>$title</title><link rel='stylesheet' href='/assets/css/style.css'></head>";
    echo "<body><header><h1>$title</h1><nav><a href='/user/index.php'>الرئيسية</a> | <a href='/user/products/list.php'>المنتجات</a> | <a href='/user/cart/view.php'>السلة</a></nav></header>";
}
?>
