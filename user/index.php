<?php
session_start();
include '../includes/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// حساب عدد عناصر السلة من الجلسة (مثال)
$cart_count = 0;

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['name'];
    
    $stmt = $conn->prepare("SELECT SUM(quantity) AS total_items FROM cart_items WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $cart_count = intval($row['total_items']);
    }
}

// استعلام المنتجات المميزة (featured)
$featured = $conn->query("SELECT * FROM products WHERE featured = 1 ORDER BY id DESC LIMIT 10");
$featured_products = [];
while ($row = $featured->fetch_assoc()) {
    $featured_products[] = $row;
}

// استعلام المنتجات الأخرى (static)
$static = $conn->query("SELECT * FROM products WHERE featured = 0 ORDER BY id DESC LIMIT 10");
if (!$static) {
    die("خطأ في استعلام المنتجات الأخرى: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Hazo Stor - الصفحة الرئيسية</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* --- التنسيق العام --- */
        
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding-top: 70px; /* لتفادي الهيدر */
            background-image: url('../assets/images/2.jpg'); /* استبدل 'your-image.jpg' برابط الصورة أو مسارها */
            background-size: cover;       /* تغطي الشاشة بالكامل */
            background-attachment: fixed; /* تجعل الخلفية ثابتة */
            background-repeat: no-repeat; /* تمنع تكرار الصورة */
            background-position: center;  /* تضع الصورة في المنتصف */
            margin: 0;
        }

        /* --- الهيدر --- */
        header {
            position: fixed;
            top: 0; right: 0; left: 0;
            height: 60px;
            background-color: #e91e63;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
            font-weight: 600;
        }

        header .logo {
            font-size: 24px;
            cursor: default;
        }

        header .nav-buttons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        header .nav-buttons a, header .nav-buttons form button {
            background-color: white;
            color: #e91e63;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        header .nav-buttons a:hover,
        header .nav-buttons form button:hover {
            background-color: #c2185b;
            color: white;
        }

        header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 16px;
        }

        header .user-info span {
            white-space: nowrap;
        }

        header .icon-button {
            position: relative;
            font-size: 20px;
            cursor: pointer;
            color: white;
            background: transparent;
            border: none;
            padding: 0 6px;
        }

        header .icon-button:hover {
            color: #c2185b;
        }

        header .cart-count {
            position: absolute;
            top: -6px;
            right: 0;
            background: yellow;
            color: #e91e63;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
            min-width: 18px;
            text-align: center;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        /* --- زر قائمة المنتجات --- */
        .products-link {
            display: block;
            margin: 20px auto;
            max-width: 150px;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .products-link:hover {
            background-color: #0056b3;
        }

        /* --- السلايدر --- */
        .slider-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px 0;
        }

        .product-slider {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 15px;
            width: 320px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            transition: all 0.5s ease-in-out;
        }

        .product-slider img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-slider h3 {
            margin: 10px 0;
            color: #e91e63;
        }

        .product-slider p {
            margin: 5px 0;
        }

        .product-slider a,
        .product-slider a.button-link {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
            font-weight: bold;
        }

        .product-slider a:hover,
        .product-slider a.button-link:hover {
            background-color: #c2185b;
        }

        /* --- قسم المنتجات الأخرى --- */
        .products-section {
            margin-top: 60px;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }

        .products-section h2 {
            margin-bottom: 25px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
        }

        .product-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 220px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-card p {
            margin: 5px 0;
            font-weight: 600;
            color: #333;
        }

        .product-card a, .product-card form button {
            margin-top: 8px;
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            background-color: #e91e63;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .product-card a:hover, .product-card form button:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">Hazo Stor</div>
    <div class="nav-buttons">
        <?php if (isset($_SESSION['user'])): ?>
            <div class="user-info">
                <span>مرحباً، <?= htmlspecialchars($_SESSION['user']['name']) ?></span>

                <a href="cart/view.php" class="icon-button" title="سلة التسوق">
                    🛒
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count" data-cart-count="<?= $cart_count ?>"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>

                <a href="account/orders.php" class="icon-button" title="متابعة الطلبات" style="font-size: 20px;">
                    📦
                </a>

                <form method="POST" action="account/logout.php" style="margin: 0;">
                    <button type="submit" title="تسجيل الخروج">تسجيل الخروج</button>
                </form>
            </div>
        <?php else: ?>
            <a href="account/login.php">تسجيل دخول</a>
            <a href="account/register.php">إنشاء حساب</a>
        <?php endif; ?>
    </div>
</header>

<h1>أهلاً بكم في Hazo Stor</h1>

<a href="products/list.php" class="products-link">قائمة المنتجات</a>

<!-- سلايدر المنتجات المختارة -->
<div class="slider-container">
    <div class="product-slider" id="slider">
        <!-- سيتم تعبئة البيانات هنا بواسطة JavaScript -->
    </div>
</div>

<!-- منتجات أخرى -->
<div class="products-section">
    <h2>منتجات أخرى</h2>
    <div class="products">
        <?php while($row = $static->fetch_assoc()): ?>
            <div class="product-card">
                <img src="../assets/images/products/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <p><?= htmlspecialchars($row['name']) ?></p>
                <p><?= $row['price'] ?> EGP</p>
                <a href="products/view.php?id=<?= $row['id'] ?>">عرض التفاصيل</a>
                <a href="#" class="add-to-cart-btn" data-product-id="<?= $row['id'] ?>">أضف إلى السلة</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- جافاسكريبت للسلايدر -->
<script>
    const products = <?= json_encode($featured_products, JSON_UNESCAPED_UNICODE) ?>;
    let index = 0;

    function showProduct(i) {
        const product = products[i];
        const slider = document.getElementById('slider');
        slider.innerHTML = `
            <img src="../assets/images/products/${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>السعر: ${product.price} EGP</p>
            <a href="products/view.php?id=${product.id}" class="button-link">عرض الصفحة</a>
            <a href="#" class="add-to-cart-btn" data-product-id="${product.id}">أضف إلى السلة</a>
        `;
    }

    showProduct(index);
    setInterval(() => {
        index = (index + 1) % products.length;
        showProduct(index);
    }, 3000);
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    function showNotification(message, success = true) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.padding = '12px 20px';
        notification.style.backgroundColor = success ? '#4caf50' : '#f44336';
        notification.style.color = 'white';
        notification.style.borderRadius = '8px';
        notification.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
        notification.style.zIndex = 10000;
        notification.style.fontSize = '16px';
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn')) {
            e.preventDefault(); // منع السلوك الافتراضي للرابط

            const productId = e.target.getAttribute('data-product-id');

            fetch('cart/add.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + encodeURIComponent(productId)
            })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.success);
                if(data.success){
                    // تحديث عدد المنتجات في السلة في الهيدر بناءً على العدد من الرد
                    const cartCountElem = document.querySelector('.cart-count');
                    if(cartCountElem && typeof data.cart_count !== 'undefined'){
                        cartCountElem.textContent = data.cart_count;
                        cartCountElem.setAttribute('data-cart-count', data.cart_count);
                    } else if(!cartCountElem && data.cart_count > 0){
                        // إذا لم يكن هناك عنصر للعدد (السلة كانت فارغة)، أضفه الآن
                        const cartIcon = document.querySelector('.icon-button[href="cart/view.php"]');
                        if(cartIcon){
                            const span = document.createElement('span');
                            span.className = 'cart-count';
                            span.textContent = data.cart_count;
                            span.setAttribute('data-cart-count', data.cart_count);
                            cartIcon.appendChild(span);
                        }
                    }
                }
            })
            .catch(() => {
                showNotification('حدث خطأ في الاتصال بالخادم', false);
            });
        }
    });

});
</script>

</body>
</html>
