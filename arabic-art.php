<?php
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// جلب اللوحات الخاصة بالقسم "لوحات عربية" بجميع احتمالات الأسماء المخزنة في قاعدة البيانات
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? OR category = ? OR category = ? ORDER BY id DESC");
$stmt->execute(['لوحات عربية', 'arabic', 'arabic-art']);
$db_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحات عربية</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ec;
            text-align: center;
            margin: 0;
            padding: 0;
            /* زيادة المسافة العلوية للجسم لتجنب تداخل المحتوى مع الهيدر الثابت */
            padding-top: 150px; 
        }

        /* جعل الهيدر ثابتًا في أعلى الصفحة مع تنسيق متوازن */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #59222c;
            padding: 10px 0 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .container {
            width: 94%;
            margin: auto;
            padding: 20px 0;
        }

        .category {
            margin-bottom: 40px;
            padding: 25px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .gallery-intro {
            background-color: #59222c;
            color: #fcfbfa;
            width: 82%;
            margin: 20px auto 30px auto; /* مسافة مناسبة تفصلها عن الهيدر العلوى */
            padding: 22px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        }

        .gallery-intro h2 {
            color: #fcfbfa;
            font-size: 1.8em;
            margin: 0 0 8px 0;
        }

        .gallery-intro p {
            margin: 0;
            font-size: 1em;
            color: #ebdcd0;
        }

        .cake-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .cake-item {
            width: 260px;
            background: #fffcf8;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #eee4db;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            transition: transform 0.2s;
        }

        .cake-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .cake-item img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            border-radius: 6px;
            border: 1.5px solid #d98894;
            padding: 3px;
            background: #ffffff;
        }

        .cake-item h3 {
            font-size: 1.3em;
            color: #59222c;
            margin: 10px 0 6px 0;
        }

        .cake-item p {
            font-size: 0.95em;
            color: #555;
            margin: 4px 0 10px 0;
            line-height: 1.4;
            flex-grow: 1;
        }

        .price {
            font-weight: bold;
            color: #59222c;
            font-size: 1.15em;
            margin: 5px 0 12px 0;
        }

        .order-button {
            background-color: #59222c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
            transition: background 0.3s;
            margin-top: auto;
        }

        .order-button:hover {
            background-color: #73313d;
        }

        .d {
            font-weight: bold;
            font-size: 180%;
            color: #fcfbfa;
            margin: 0 0 10px 0;
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
            padding: 10px 10px;
            display: inline-block;
            margin: 0;
            font-size: 19px;
            font-weight: bold;
            color: #fcfbfa;
        }

        nav ul li a {
            color: #fcfbfa;
            text-decoration: none;
            display: block;
            padding: 5px 10px;
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
    </style>
    <link rel="stylesheet" href="site.css">
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
                     
                        <li><a href="variety.php"> منوعات </a></li>
                        <li><a href="nature-art.php">لوحات من الطبيعة</a></li>
                    </ul>
                </li>
                <li><a href="order.php"> طلب</a></li>
                <li><a href="contact.php"> للتواصل</a></li>
                <li><a href="about-us.php" target="_self"> من نحن</a></li>
            </ul>
        </nav>
    </header>

    <section class="gallery-intro">
        <h2>لوحات عربية</h2>
        <p>لوحات تعكس أصالة التراث والفن العربي العريق، وتضيف لمسة دافئة وراقية على المكان.</p>
    </section>

    <div class="container">
        <div class="category">
            <div class="cake-list">

                <!-- 1 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/1a0e41596960a892f229ddfb69d70485.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 2 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/1c8da79fc84e0ec8c14f5651f76a031a.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 49 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=49"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 3 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/1e6e9b15cc840c01258ff64602209979.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 48 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=48"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 4 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/24931e33903a207fc79021fb66fd1681.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 55 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=55"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 5 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/136bd023cee1f3bdcfaf7b5405a3787a.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 65 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=65"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 6 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/2d02baefcdefd679ba4e6c1ffcb75cd1.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 66 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=66"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 7 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/3269bf97d5005124c3b28b1e8f4fde3a.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 60 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=60"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 8 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/3549bdf003c59d7d5008fe7038a42f07.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 50 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=50"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 9 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/3fb59c213f72c32787c949aed3893114.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 56 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=56"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 10 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/47701ffa576d9c6cb111f37c008523e3.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 11 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/4dac7cadc830856ccfee6e92063e0314.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 70 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=70"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 12 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/5426918b7ceca979e39858b1a412dd64.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 77 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=77"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 13 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/54baa8673c009811b9cf078a302f0c63.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 54 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=54"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 14 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/55534dd452f767c7717b0d1a9310fc36.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 67 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=67"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 15 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/5b5d8d8e4e5ce29198957cec1dda987b.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 16 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/64bbe9e9a2e9bcb46841a437ca4705eb.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 49 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=49"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 17 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/6600187d849ce153780368908bcef6bb.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 63 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=63"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 18 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/669785d71050fc3f1957e1805fb94faa.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 72 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=72"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 19 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/75682663d96819332eb7b6aa730e3d7e.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 20 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/87b0c04c8edc3699760971170a136525.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 50 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=50"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 21 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/8c95fc4b6dd08527bf7c042cda83483c.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 77 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=77"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 22 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/91530cb79b5ab00b4953ee1ec3b05af3.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 23 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/9a1fbf359e1845a4df3a8b69eb1efd25.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 57 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=57"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 24 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/9add2890f31e63041ab865cb9c20cae8.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 64 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=64"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 25 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/9ba1b18f94b70960c7cffa812f533ac4.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 78 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=78"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 26 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/bc8528e6a9c33e81a59d7899a57ba93a.jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 49 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=49"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 27 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/c14d6a246184c6de75f497d44959a33d.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 44 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=44"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 28 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/cc7fdd74916528f6e104d31a94efe675.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 76 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=76"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 29 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/ce188f86d3755a931761a60dd65b48a1.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 55 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=55"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 30 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/e3967be4fecd56872b798d82828bfbe9 (1).jpg" alt="">
                        <h3>Hassan Art :الفنان</h3>
                        <p>لوحه بخط عربي انيق يجمع بين الاصالة والجمال</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Hassan%20Art%20(لوحة%20عربية)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 31 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/ebe89c8f74357c96932d8c0e4feafda7.jpg" alt="">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة عربية حديثة مستوحاة من الحروف العربية</p>
                        <p class="price">السعر : 61 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(لوحة%20عربية)&price=61"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 32 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/eff07e3fddc559e5b3d309c9ed641907.jpg" alt="">
                        <h3>Ahmed Art :الفنان</h3>
                        <p>لوحة عربية مميزة تعكس جمال الخط العربي باسلوب معاصر</p>
                        <p class="price">السعر : 70 دولار</p>
                    </div>
                    <a href="order.php?art=Ahmed%20Art%20(لوحة%20عربية)&price=70"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 33 -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Arabiah/f7d95bc864341965dee08eea33d7d2a0.jpg" alt="">
                        <h3>Mohammed Art :الفنان</h3>
                        <p>لوحه عربية بتصميم من حروف عربيه</p>
                        <p class="price">السعر : 48 دولار</p>
                    </div>
                    <a href="order.php?art=Mohammed%20Art%20(لوحة%20عربية)&price=48"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- عرض المنتجات المضافة ديناميكياً من قاعدة البيانات -->
                <?php if (count($db_products) > 0): ?>
                    <?php foreach ($db_products as $product): ?>
                        <div class="cake-item">
                            <div>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="price">السعر : <?php echo htmlspecialchars($product['price']); ?> دولار</p>
                            </div>
                            <a href="order.php?art=<?php echo urlencode($product['title']); ?>&price=<?php echo urlencode($product['price']); ?>">
                                <button class="order-button">اطلب الآن</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>