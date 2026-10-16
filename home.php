<?php
session_start();
require_once 'db.php';

// التحقق من صلاحية الأدمن
$is_admin = (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'admin') || (isset($_SESSION['username']) && $_SESSION['username'] === 'admin');

// جلب آخر اللوحات المضافة
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 6");
    $recent_artworks = $stmt->fetchAll();
} catch (PDOException $e) {
    $recent_artworks = [];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallé Online Store | معرض اللوحات الفنية</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; scroll-behavior: smooth; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fcfbfa;
            color: #333;
        }

        /* --- الهيدر الاحترافي والمُرتّب --- */
        header {
            background-color: #6b2d34;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .header-top {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        header h1.logo {
            font-size: 2em;
            color: #fffdf9;
            letter-spacing: 2px;
            font-weight: 700;
        }

        /* حاوية بيانات الحساب (يسار الهيدر) */
        .user-auth-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-welcome {
            color: #ffd700;
            font-size: 0.9em;
            font-weight: bold;
        }

        .logout-btn {
            background-color: transparent;
            color: #ffcccc !important;
            border: 1px solid rgba(255,204,204,0.4);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.85em;
            text-decoration: none;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background-color: rgba(255, 204, 204, 0.15);
            color: #fff !important;
        }

        .login-link {
            background-color: #8c3b44;
            color: white !important;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.9em;
            text-decoration: none;
            font-weight: bold;
        }

        nav {
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 10px;
            display: flex;
            justify-content: center;
        }

        nav ul {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        nav ul li a {
            color: #f7f3ed;
            text-decoration: none;
            font-size: 0.95em;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffd700;
        }

        nav ul li.dropdown {
            position: relative;
        }
        
        nav ul li.dropdown ul {
            display: none;
            position: absolute;
            top: 100%;
            right: 50%;
            transform: translateX(50%);
            background-color: #fffdf9;
            box-shadow: 0 6px 20px rgba(107, 45, 52, 0.15);
            border-radius: 8px;
            list-style: none;
            padding: 8px 0;
            width: 170px;
            border: 1px solid #e6dcd0;
            flex-direction: column;
            z-index: 110;
        }

        nav ul li.dropdown ul li a {
            color: #6b2d34;
            padding: 10px 15px;
            display: block;
            text-align: right;
        }

        nav ul li.dropdown ul li a:hover {
            background-color: #f7f3ed;
            color: #8c3b44;
        }

        nav ul li.dropdown:hover ul {
            display: flex;
        }

        .admin-nav-btn {
            background-color: #ffd700 !important;
            color: #6b2d34 !important;
            font-weight: bold;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        
        .admin-nav-btn:hover {
            background-color: #f3cc00 !important;
        }

        .features-bar {
            background-color: #f3efe6;
            display: flex;
            justify-content: space-around;
            padding: 12px 20px;
            font-size: 0.9em;
            color: #6b2d34;
            border-bottom: 1px solid #e6dcd0;
            text-align: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* --- الواجهة الرئيسية (Hero Section) --- */
        .hero {
            background: linear-gradient(rgba(107, 45, 52, 0.82), rgba(107, 45, 52, 0.82)), center/cover;
            color: white;
            text-align: center;
            padding: 90px 20px;
            margin-bottom: 40px;
        }
        
        .hero h2 {
            font-size: 2.8em;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        
        .hero p {
            font-size: 1.2em;
            max-width: 700px;
            margin: 0 auto 30px auto;
            line-height: 1.6;
            color: #f7f3ed;
        }
        
        .hero-btn-container {
            display: flex;
            justify-content: center;
        }
        
        .btn-explore {
            background-color: #ffd700;
            color: #6b2d34;
            padding: 14px 36px;
            border-radius: 35px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.15em;
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
            box-shadow: 0 6px 15px rgba(0,0,0,0.25);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-explore:hover {
            transform: translateY(-3px);
            background-color: #ffcc00;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto 50px auto;
            padding: 0 20px;
        }
        
        .section-title {
            text-align: center;
            color: #6b2d34;
            font-size: 2em;
            margin-bottom: 35px;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background-color: #ffd700;
            margin: 10px auto 0 auto;
            border-radius: 2px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(115, 61, 61, 0.08);
            border: 1px solid #e6dcd0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(115, 61, 61, 0.15);
        }
        
        .product-img-container {
            width: 100%;
            height: 220px;
            background-color: #f7f3ed;
            overflow: hidden;
            position: relative;
        }
        
        .product-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-img-container img {
            transform: scale(1.05);
        }
        
        .product-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        
        .product-title {
            font-size: 1.2em;
            color: #6b2d34;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .product-desc {
            font-size: 0.9em;
            color: #7d6b67;
            margin-bottom: 15px;
            line-height: 1.4;
            flex-grow: 1;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f0eae1;
            padding-top: 12px;
            margin-top: auto;
        }
        
        .product-price {
            font-size: 1.25em;
            color: #8c3b44;
            font-weight: bold;
        }
        
        .buy-btn {
            background-color: #6b2d34;
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: bold;
            transition: background 0.2s;
        }
        
        .buy-btn:hover {
            background-color: #8c3b44;
        }

        footer {
            background-color: #3b181c;
            color: #f7f3ed;
            text-align: center;
            padding: 25px;
            font-size: 0.9em;
            margin-top: 60px;
            border-top: 4px solid #ffd700;
        }
    </style>
    <link rel="stylesheet" href="responsive.css">
</head>

<body>

    <header>
        <div class="header-container">
            <!-- الجزء العلوي: الشعار وبيانات المستخدم -->
            <div class="header-top">
                <h1 class="logo">Wallé Store</h1>
                
                <div class="user-auth-section">
                    <?php if (isset($_SESSION['username'])): ?>
                        <span class="user-welcome">أهلاً، <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="logout.php" class="logout-btn">خروج</a>
                    <?php else: ?>
                        <a href="index.php" class="login-link">تسجيل الدخول</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- الروابط وأقسام الموقع في الأسفل بانسيابية -->
            <nav>
                <ul>
                
                    
                    <li class="dropdown">
                        <a href="#">الأقسام ▾</a>
                        <ul>
                            <li><a href="arabic-art.php">لوحات عربية</a></li>
                            <li><a href="black-white.php">لوحات أبيض وأسود</a></li>
                            <li><a href="variety.php">منوعات</a></li>
                            <li><a href="nature-art.php">لوحات من الطبيعة</a></li>
                        </ul>
                    </li>

                    <li><a href="contact.php">تواصل معنا</a></li>
                    <li><a href="about-us.php">من نحن</a></li>

                    <!-- يظهر زر لوحة التحكم حصرياً للأدمن -->
                    <?php if ($is_admin): ?>
                        <li><a href="dashboard.php" class="admin-nav-btn">⚙️ لوحة التحكم</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="features-bar">
        <span>🎨 لوحات فنية أصلية ومميزة</span>
        <span>🚚 توصيل سريع وآمن لجميع الأعمال</span>
        <span>💎 جودة طباعة وخامات راقية</span>
        <span>🔒 تسوق آمن وموثوق</span>
    </div>

    <!-- الواجهة الرئيسية (Hero Section) -->
    <section class="hero">
        <h2>معرض اللوحات الفنية الراقيّة</h2>
        <p>اكتشفوا مجموعة منتقاة من الأعمال التي توازن بين دفء اللون وجرأة التعبير، وتحوّل المكان إلى حكاية فنية نابضة بالإبداع والأصالة.</p>
        <div class="hero-btn-container">
            <a href="#artworks-section" class="btn-explore">استكشف التشكيلة الفنية <span style="font-size: 1.2em;">↓</span></a>
        </div>
    </section>

    <div class="container" id="artworks-section">
        <h2 class="section-title">أحدث اللوحات المضافة</h2>

        <?php if (count($recent_artworks) > 0): ?>
            <div class="products-grid">
                <?php foreach ($recent_artworks as $art): ?>
                    <div class="product-card">
                        <div class="product-img-container">
                            <?php if (!empty($art['image'])): ?>
                                <img src="<?php echo htmlspecialchars($art['image']); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>">
                            <?php else: ?>
                                <img src="images/default-art.jpg" alt="صورة افتراضية">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($art['title']); ?></h3>
                            <p class="product-desc"><?php echo htmlspecialchars(mb_substr($art['description'], 0, 80)); ?>...</p>
                            <div class="product-footer">
                                <span class="product-price">$<?php echo htmlspecialchars($art['price']); ?></span>
                                <a href="order.php?art=<?php echo urlencode($art['title']); ?>&price=<?php echo urlencode($art['price']); ?>" class="buy-btn">اطلب الآن</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #7d6b67; font-size: 1.1em; padding: 40px;">لا توجد لوحات مضافة حالياً في المتجر. يمكنك إضافتها من خلال لوحة التحكم!</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>جميع الحقوق محفوظة &copy; 2026 - Wallé Online Store</p>
    </footer>

</body>
</html>