<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>من نحن - Wallé Store</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ed;
            margin: 0;
            padding: 0;
            color: #333;
            /* مساحة من الأعلى لكي لا يختبئ المحتوى تحت الهيدر الثابت */
            padding-top: 130px;
        }

        /* تنسيق الهيدر الثابت والمرتب في المنتصف تماماً مثل الصورة */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #6b2d34;
            color: white;
            padding: 15px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        header h1.d {
            margin: 0 0 10px 0;
            font-size: 1.6em;
            color: #fff;
            letter-spacing: 0.5px;
            text-align: center;
        }
        header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        header nav ul li {
            position: relative;
        }
        header nav ul li a {
            color: #f7f3ed;
            text-decoration: none;
            font-weight: 600;
            padding: 6px 12px;
            display: block;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.95em;
        }
        header nav ul li a:hover {
            background-color: #8c3b44;
            color: #fff;
        }
        
        /* القائمة المنسدلة للأقسام */
        header nav ul li ul {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #fffdf9;
            min-width: 180px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            border-radius: 8px;
            z-index: 1100;
            list-style: none;
            padding: 8px 0;
            margin-top: 5px;
            border: 1px solid #e6dcd0;
        }
        header nav ul li:hover ul {
            display: block;
        }
        header nav ul li ul li a {
            color: #6b2d34;
            padding: 10px 18px;
            white-space: nowrap;
            border-radius: 0;
            text-align: right;
        }
        header nav ul li ul li a:hover {
            background-color: #f7f3ed;
            color: #6b2d34;
        }

        /* المحتوى الرئيسي */
        main {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .gallery-intro {
            text-align: center;
            background-color: #6b2d34;
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 35px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .gallery-intro h2 {
            margin-top: 0;
            font-size: 2em;
            color: #fff;
        }
        .gallery-intro p {
            font-size: 1.1em;
            color: #f7f3ed;
            margin-bottom: 0;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .about-card {
            background: #fffdf9;
            border: 1px solid #e6dcd0;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            transition: transform 0.3s;
        }
        .about-card:hover {
            transform: translateY(-3px);
        }
        .about-card h3 {
            color: #6b2d34;
            margin-top: 0;
            border-bottom: 2px solid #8c3b44;
            padding-bottom: 8px;
            font-size: 1.2em;
        }
        .about-card p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 0;
        }

        footer {
            background-color: #6b2d34;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="responsive.css">
</head>

<body>

    <header>
        <h1 class="d">Wallé Online Store</h1>
        <nav>
            <ul>
                
                <li><a href="home.php" title="الصفحة الرئيسية">الرئيسية</a></li>
                <li><a href="#">الاقسام</a>
                    <ul>
                        <li><a href="black-white.php">لوحات ابيض واسود</a></li>
                        <li><a href="arabic-art.php">لوحات عربية</a></li>
                        <li><a href="variety.php">منوعات</a></li>
                        <li><a href="nature-art.php">لوحات من الطبيعة</a></li>
                    </ul>
                </li>
                
                <li><a href="contact.php">للتواصل</a></li>
                <li><a href="about-us.php" style="background-color: #8c3b44;">من نحن</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="gallery-intro">
            <h2>نبذة جمالية عن معرض اللوحات الفنية</h2>
            <p>مساحة تجمع اللون والفكرة والذاكرة، لنمنح كل جدار لوحة تحمل إحساسًا خاصًا وتفاصيل تستحق التأمل.</p>
        </section>

        <div class="about-grid">
            <div class="about-card">
                <h3>من هو متجرنا؟</h3>
                <p>مرحبًا بكم في متجرنا، وجهتكم المفضلة لعالم اللوحات الرائعة! نحن هنا لنقدم لكم تجربة تسوق فريدة من نوعها، حيث يجتمع الشغف بالجودة مع حب الابتكار.</p>
            </div>

            <div class="about-card">
                <h3>رؤيتنا</h3>
                <p>نسعى لأن نكون المتجر الرائد في مجال اللوحات، نقدم لكم مجموعة متنوعة من اللوحات المرسومة بأعلى معايير الجودة.</p>
            </div>

            <div class="about-card">
                <h3>فريقنا</h3>
                <p>يعمل لدينا فريق من الرسامين الموهوبين الذين يتمتعون بخبرة واسعة في فنون الرسم. نحن نستخدم ألوان طبيعية عالية الجودة، مع التركيز على تقديم اللوحات التي لا تُنسى.</p>
            </div>

            <div class="about-card">
                <h3>تجربة تسوق فريدة</h3>
                <p>من خلال متجرنا الإلكتروني، يمكنكم استعراض مجموعتنا الكاملة من اللوحات وطلب ما يناسبكم بسهولة ويسر. نحن نقدم خدمات شحن سريعة لضمان وصول لوحاتكم في أسرع وقت.</p>
            </div>
        </div>

        <div class="about-card" style="text-align: center; background-color: #f1eae1; max-width: 100%;">
            <h3 style="display: inline-block; border-bottom: none;">انضموا إلينا!</h3>
            <p style="max-width: 700px; margin: 0 auto;">نحن ندعوكم لتكونوا جزءًا من مجتمعنا المحب للوحات. تابعونا على وسائل التواصل الاجتماعي لتكونوا على اطلاع دائم بأحدث العروض والمستجدات.</p>
        </div>
    </main>

    <footer>
        <p>حقوق الطبع والنشر محفوظة لدى Wallé Online Store</p>
    </footer>

</body>

</html>