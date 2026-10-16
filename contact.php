<?php
session_start();
require_once 'db.php';

// التحقق من صلاحية الأدمن
$is_admin = (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'admin') || (isset($_SESSION['username']) && $_SESSION['username'] === 'admin');

$msg_status = "";
$error_msg = "";
$name = "";
$email = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال وتنظيف المدخلات
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // تطبيق شروط الـ Validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_msg = "الرجاء تعبئة جميع الحقول المطلوبة.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "البريد الإلكتروني المدخل غير صحيح، يرجى التأكد منه.";
    } elseif (mb_strlen($message) < 10) {
        $error_msg = "عذراً، يجب أن تكون الرسالة أكثر من 10 أحرف لكي نتمكن من فهم استفسارك بدقة.";
    } else {
        try {
            // حفظ الرسالة في قاعدة البيانات فعلياً
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $message]);

            $msg_status = "شكراً لك يا " . htmlspecialchars($name) . "! تم إرسال رسالتك وحفظها بنجاح، وسنتواصل معك في أقرب وقت.";
            
            // تفريغ الحقول بعد النجاح
            $name = "";
            $email = "";
            $message = "";
        } catch (PDOException $e) {
            $error_msg = "حدث خطأ أثناء حفظ الرسالة، يرجى المحاولة لاحقاً.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل معنا | Wallé Online Store</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fcfbfa;
            color: #333;
        }

        /* --- الهيدر الاحترافي --- */
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

        .admin-nav-btn {
            background-color: #ffd700 !important;
            color: #6b2d34 !important;
            font-weight: bold;
        }

        /* --- محتوى الصفحة --- */
        .contact-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .page-title {
            text-align: center;
            color: #6b2d34;
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .page-subtitle {
            text-align: center;
            color: #7d6b67;
            font-size: 1.1em;
            margin-bottom: 40px;
        }

        /* بطاقات التواصل السريع مرتبة ومضبوطة بالاتجاة الصحيح */
        .contact-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 50px;
            direction: ltr; /* لضمان الترتيب السليم من اليمين لليسار في الشاشات الكبيرة */
        }

        .contact-card {
            background: white;
            padding: 25px 15px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e6dcd0;
            box-shadow: 0 4px 12px rgba(107, 45, 52, 0.05);
            transition: transform 0.3s;
            text-decoration: none;
            color: #333;
            direction: rtl; /* إعادة النص عربي داخل البطاقة */
        }

        .contact-card:hover {
            transform: translateY(-5px);
            border-color: #ffd700;
        }

        .contact-card span.icon {
            font-size: 2.5em;
            display: block;
            margin-bottom: 12px;
        }

        .contact-card h3 {
            color: #6b2d34;
            font-size: 1.05em;
            margin-bottom: 5px;
        }

        .contact-card p {
            color: #7d6b67;
            font-size: 0.85em;
        }

        /* استجابة الشاشات الصغيرة */
        @media (max-width: 768px) {
            .contact-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* نموذج المراسلة المباشرة */
        .contact-form-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            border: 1px solid #e6dcd0;
            box-shadow: 0 6px 20px rgba(107, 45, 52, 0.08);
        }

        .contact-form-box h3 {
            color: #6b2d34;
            font-size: 1.5em;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #6b2d34;
            font-weight: bold;
            font-size: 0.95em;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e6dcd0;
            border-radius: 8px;
            font-size: 1em;
            background-color: #fcfbfa;
            color: #333;
        }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #6b2d34;
            background-color: #fff;
        }

        .submit-btn {
            background-color: #6b2d34;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        .submit-btn:hover {
            background-color: #8c3b44;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
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

            <nav>
                <ul>
                    <li><a href="home.php">الرئيسية</a></li>
                    <li><a href="home.php#artworks-section">الأقسام ▾</a></li>
                    <li><a href="contact.php" style="color: #ffd700;">تواصل معنا</a></li>
                    <li><a href="about-us.php">من نحن</a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="dashboard.php" class="admin-nav-btn">⚙️ لوحة التحكم</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="contact-container">
        <h2 class="page-title">نحن هنا لخدمتك</h2>
        <p class="page-subtitle">تواصل معنا عبر قنوات الاتصال المباشرة أو اترك لنا رسالة وسنقوم بالرد عليك في أقرب وقت.</p>

        <!-- بطاقات التواصل السريع المرتبة (واتساب - اتصال - إيميل - انستغرام) -->
        <div class="contact-cards">
            <!-- 1. إنستغرام -->
            <a href="https://instagram.com/h._.a._._r" target="_blank" class="contact-card">
                <span class="icon">📸</span>
                <h3>إنستغرام</h3>
                <p>تابع جديدنا الفني</p>
            </a>

            <!-- 2. البريد الإلكتروني -->
            <a href="mailto:aliaramzimohammed78@gmail.com" class="contact-card">
                <span class="icon">✉️</span>
                <h3>البريد الإلكتروني</h3>
                <p style="font-size: 0.75em; direction: ltr;">aliaramzimohammed78@gmail.com</p>
            </a>

            <!-- 3. الاتصال الهاتفي -->
            <a href="tel:+967715526096" class="contact-card">
                <span class="icon">📞</span>
                <h3>الاتصال الهاتفي</h3>
                <p>خدمة العملاء</p>
            </a>

            <!-- 4. واتساب -->
            <a href="https://wa.me/967715526096" target="_blank" class="contact-card">
                <span class="icon">💬</span>
                <h3>واتساب</h3>
                <p>راسلنا مباشرة</p>
            </a>
        </div>

        <!-- نموذج المراسلة -->
        <div class="contact-form-box">
            <h3>أرسل استفسارك أو طلبك الخاص</h3>
            
            <?php if (!empty($msg_status)): ?>
                <div class="success-msg"><?php echo $msg_status; ?></div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="error-msg"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>الاسم الكريم</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($name); ?>" placeholder="أدخل اسمك الكامل">
                </div>
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>" placeholder="example@domain.com">
                </div>
                <div class="form-group">
                    <label>الرسالة أو تفاصيل الطلب</label>
                    <textarea name="message" rows="5" required placeholder="اكتب استفسارك أو تفاصيل اللوحة الخاصة التي ترغب بطلبها (10 أحرف على الأقل)..."><?php echo htmlspecialchars($message); ?></textarea>
                </div>
                <button type="submit" class="submit-btn">إرسال الرسالة</button>
            </form>
        </div>
    </div>

    <footer>
        <p>جميع الحقوق محفوظة &copy; 2026 - Wallé Online Store</p>
    </footer>

</body>
</html>