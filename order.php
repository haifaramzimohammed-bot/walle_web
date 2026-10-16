<?php
// استقبال بيانات اللوحة والسعر القادمة عبر الرابط (GET)
$art_name = isset($_GET['art']) ? htmlspecialchars($_GET['art'], ENT_QUOTES, 'UTF-8') : 'لوحة غير مسماة';
$price    = isset($_GET['price']) ? htmlspecialchars($_GET['price'], ENT_QUOTES, 'UTF-8') : '0';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام طلب اللوحة - Wallé Online Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ec;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #59222c;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }

        .d {
            font-weight: bold;
            font-size: 190%;
            color: #fcfbfa;
            margin: 0 0 15px 0;
        }

        nav ul {
            background-color: #59222c;
            display: inline-block;
            width: 97%;
            height: auto;
            text-align: center;
            margin: 0;
            padding: 0;
            position: relative;
        }

        nav ul li {
            position: relative;          
            padding: 15px 10px;
            display: inline-block;
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #fcfbfa;
        }

        nav ul li a {
            color: #fcfbfa;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        nav ul li:hover > a {
            background: #73313d;
            border-radius: 4px;
        }

        nav ul li ul {
            position: absolute;
            right: 0;
            top: 100%;
            width: 170px;
            display: none;
            z-index: 100;
            background: #4a1b23;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            text-align: right;
            padding: 5px 0;
        }

        nav ul li:hover ul {
            display: block;
        }

        nav ul li ul li {
            border: none;
            display: block;
            margin: 0;
        } 

        nav ul li ul li a {
            color: #fcfbfa;
            padding: 10px 15px;
            font-size: 15px;
        }

        nav ul li ul li:hover {
            background: #73313d;
        }

        .order-container {
            width: 50%;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: right;
            border: 1px solid #eee4db;
        }

        .order-container h2 {
            text-align: center;
            color: #59222c;
            margin-bottom: 25px;
        }

        .product-info {
            background: #f7f3ec;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            color: #59222c;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #59222c;
            font-weight: bold;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d98894;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
            background-color: #fffcf8;
        }

        .form-group input:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: #59222c;
            box-shadow: 0 0 5px rgba(89, 34, 44, 0.2);
        }

        .submit-btn {
            background-color: #59222c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
            transition: background 0.3s;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #73313d;
        }
    </style>
    <link rel="stylesheet" href="responsive.css">
</head>
<body>

    <header>
      <h1 class="d">Wallé Online Store</h1>
        <nav>
            <ul>
                 <li><a href="home.php">الرئيسية</a></li>
                  <li><a href="#"> الاقسام ▾</a>
                    <ul>
                        <li><a href="black-white.php">لوحات ابيض واسود</a></li>
                        <li><a href="arabic-art.php"> لوحات عربية </a></li>
                        <li><a href="variety.php"> منوعات </a></li>
                        <li><a href="nature-art.php">لوحات من الطبيعة</a></li>
                    </ul>
                </li>
                <li><a href="about-us.php" target="_self"> من نحن</a></li>
               
                <li><a href="contact.php"> للتواصل</a></li>
            </ul>
        </nav>
    </header>

    <div class="order-container">
        <h2>إتمام طلب اللوحة</h2>

        <!-- نموذج إرسال الطلب إلى ملف send.php مع طريقة POST -->
        <form action="send.php" method="POST">
            
            <!-- حقول مخفية لتمرير اسم اللوحة وسعرها مع الطلب -->
            <input type="hidden" name="art" value="<?php echo $art_name; ?>">
            <input type="hidden" name="price" value="<?php echo $price; ?>">

            <div class="product-info">
                <p>اللُوحة المطلوبة: <?php echo $art_name; ?></p>
                <p>السعر: <?php echo $price; ?> دولار</p>
            </div>

            <div class="form-group">
                <label for="fullname">الاسم الكامل:</label>
                <input type="text" id="fullname" name="fullname" placeholder="أدخل اسمك الكامل" required>
            </div>

            <div class="form-group">
                <label for="phone">رقم الهاتف:</label>
                <input type="text" id="phone" name="phone" placeholder="أدخل رقم هاتفك" required>
            </div>

            <!-- الحقل الجديد المضاف لعنوان أو موقع التوصيل -->
            <div class="form-group">
                <label for="address">عنوان التوصيل (الموقع):</label>
                <input type="text" id="address" name="address" placeholder="أدخل المدينة والحي أو العنوان بالتفصيل" required>
            </div>

            <div class="form-group">
                <label for="notes">ملاحظات إضافية (اختياري):</label>
                <textarea id="notes" name="notes" rows="4" placeholder="أي تفاصيل إضافية للطلب..."></textarea>
            </div>

            <button type="submit" class="submit-btn">تأكيد وإرسال الطلب</button>
        </form>
    </div>

</body>
</html>