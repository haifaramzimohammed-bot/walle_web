<?php
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

$success = false;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. استقبال البيانات وتنظيفها (حماية ضد XSS)
    $art_name = isset($_POST['art']) ? htmlspecialchars(trim($_POST['art']), ENT_QUOTES, 'UTF-8') : 'لوحة غير مسماة';
    $price    = isset($_POST['price']) ? htmlspecialchars(trim($_POST['price']), ENT_QUOTES, 'UTF-8') : '0';
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $phone    = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address  = isset($_POST['address']) ? trim($_POST['address']) : ''; // استقبال العنوان الجديد
    $notes    = isset($_POST['notes']) ? htmlspecialchars(trim($_POST['notes']), ENT_QUOTES, 'UTF-8') : '';

    $errors = [];

    // 2. التحقق من صحة المدخلات (Validation)
    if (empty($fullname)) {
        $errors[] = "حقل الاسم الكامل مطلوب.";
    } elseif (mb_strlen($fullname) < 3) {
        $errors[] = "الاسم قصير جداً، يجب ألا يقل عن 3 أحرف.";
    }

    if (empty($phone)) {
        $errors[] = "حقل رقم الهاتف مطلوب.";
    }

    if (empty($address)) {
        $errors[] = "حقل عنوان التوصيل مطلوب.";
    }

    if (empty($errors)) {
        try {
            // 3. إدخال الطلب مع العنوان باستخدام Prepared Statements
            $stmt = $pdo->prepare("INSERT INTO orders (art_name, price, fullname, phone, address, notes, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$art_name, $price, $fullname, $phone, $address, $notes]);
            $success = true;
        } catch (PDOException $e) {
            $error_message = "تعذر حفظ الطلب في قاعدة البيانات. تأكد من إضافة عمود address في جدول orders.";
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
} else {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة الطلب - Wallé Online Store</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f3ec;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(89, 34, 44, 0.1);
            text-align: center;
            max-width: 450px;
            width: 90%;
            border: 1px solid #eee4db;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        h2 {
            color: #59222c;
            margin-bottom: 15px;
            font-size: 24px;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .details-box {
            background: #f7f3ec;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: right;
            color: #59222c;
            font-size: 14px;
        }

        .btn {
            background-color: #59222c;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s, transform 0.2s;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #73313d;
            transform: translateY(-2px);
        }

        .error-card {
            border-top: 5px solid #d9534f;
        }

        .success-card {
            border-top: 5px solid #59222c;
        }
    </style>
    <link rel="stylesheet" href="responsive.css">
</head>
<body>

    <?php if ($success): ?>
        <div class="card success-card">
            <div class="icon">🎉</div>
            <h2>تم إرسال طلبك بنجاح!</h2>
            <p>شكراً لكِ يا <b><?php echo htmlspecialchars($fullname); ?></b>، تم تسجيل طلبك وإضافته إلى نظام المتجر بنجاح.</p>
            
            <div class="details-box">
                <p style="margin: 0 0 8px 0; color: #59222c;"><b>تفاصيل الطلب:</b></p>
                <p style="margin: 0 0 5px 0;">• اللوحة: <?php echo $art_name; ?></p>
                <p style="margin: 0 0 5px 0;">• السعر: <?php echo $price; ?> دولار</p>
                <p style="margin: 0 0 5px 0;">• الهاتف: <?php echo $phone; ?></p>
                <p style="margin: 0;">• العنوان: <?php echo htmlspecialchars($address); ?></p>
            </div>

            <a href="home.php" class="btn">العودة للرئيسية</a>
        </div>
    <?php else: ?>
        <div class="card error-card">
            <div class="icon">⚠️</div>
            <h2 style="color: #d9534f;">عذراً، حدث خطأ!</h2>
            <p><?php echo $error_message; ?></p>
            <br>
            <a href="javascript:history.back()" class="btn" style="background-color: #d9534f;">الرجوع لتصحيح البيانات</a>
        </div>
    <?php endif; ?>

</body>
</html>