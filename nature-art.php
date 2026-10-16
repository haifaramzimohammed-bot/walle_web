<?php
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// جلب اللوحات الخاصة بالقسم سواء تم تخزينه بالاسم العربي أو الإنجليزي في لوحة التحكم
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? OR category = ? ORDER BY id DESC");
$stmt->execute(['لوحات من الطبيعة', 'nature-art']);
$db_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحات من الطبيعة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ec;
            text-align: center;
            margin: 0;
            padding: 0;
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
            margin: 30px auto;
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
            font-size: 190%;
            color: #fcfbfa;
            margin: 0 0 15px 0;
        }

        header {
            background-color: #59222c;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
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
                        <li><a href="arabic-art.php"> لوحات عربية </a></li>
                        <li><a href="variety.php"> منوعات </a></li>
                      
                    </ul>
                </li>
                <li><a href="order.php"> طلب</a></li>
                <li><a href="contact.php"> للتواصل</a></li>
                <li><a href="about-us.php" target="_self"> من نحن</a></li>
            </ul>
        </nav>
    </header>

    <section class="gallery-intro">
        <h2>لوحات من الطبيعة</h2>
        <p>لوحات مستوحاة من سحر الطبيعة، تمنح المكان إحساساً بالهدوء والانسجام.</p>
    </section>

    <div class="container">
        <div class="category">
            <div class="cake-list">

                <!-- 1. اللوحة الأولى -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/0cf52c131021821ca9dc6b99d3ebdd14.jpg" alt="Ansel Art">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 75 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%20(الطبيعة)&price=75"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 2. اللوحة الثانية -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/114bf5a34b34f0eb077c53abbfd0b578.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 85 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%20(الطبيعة)&price=85"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 3. اللوحة الثالثة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/156847a8bedc370f712ae0d2f9bfb74e.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 87 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(الطبيعة)&price=87"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 4. اللوحة الرابعة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/18f3125d31468732f80fec50f43c405d.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة بتفاصيل ناعمه وهادئه باسلوب معاصر</p>
                        <p class="price">السعر : 80 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%20(الطبيعة)&price=80"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 5. اللوحة الخامسة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/1d1245e6c28a030477c5948ea81115a8.jpg" alt="Ali Art">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة فنيه تعكس جمال الطبيعة</p>
                        <p class="price">السعر : 74 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%20(الطبيعة)&price=74"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 6. اللوحة السادسة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/3418cfc9fd7030df38e83ef543b6723d.jpg" alt="Ansel Art">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 70 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%202%20(الطبيعة)&price=70"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 7. اللوحة السابعة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/3f783bd80ca4255a856d2ecf213c3409.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 65 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%202%20(الطبيعة)&price=65"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 8. اللوحة الثامنة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/4e5e9c93576454ed40f782c751647622.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 76 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%202%20(الطبيعة)&price=76"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 9. اللوحة التاسعة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/4f5600126df988c91cbc47ad7ab877f1.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة بتفاصيل ناعمه وهادئه باسلوب معاصر</p>
                        <p class="price">السعر : 91 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%202%20(الطبيعة)&price=91"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 10. اللوحة العاشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/5d0929402f386059aed21df81ebadb17.jpg" alt="Ali Art">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة فنيه تعكس جمال الطبيعة</p>
                        <p class="price">السعر : 76 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%202%20(الطبيعة)&price=76"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 11. اللوحة الحادية عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/5dc5c7349a53eaea2a8c217680b861b2.jpg" alt="Ansel Art">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 82 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%203%20(الطبيعة)&price=82"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 12. اللوحة الثانية عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/6cdc7900e4ef194af1c42d56ccf51d27.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 84 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%203%20(الطبيعة)&price=84"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 13. اللوحة الثالثة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/793773026de3618efeb424661c4e127e.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 77 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%203%20(الطبيعة)&price=77"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 14. اللوحة الرابعة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/7bdcb05de4c173220fe043009db71248.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة بتفاصيل ناعمه وهادئه باسلوب معاصر</p>
                        <p class="price">السعر : 75 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%203%20(الطبيعة)&price=75"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 15. اللوحة الخامسة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/7e1cd685abc42c00921641475a097f75.jpg" alt="Ali Art">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة فنيه تعكس جمال الطبيعة</p>
                        <p class="price">السعر : 65 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%203%20(الطبيعة)&price=65"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 16. اللوحة السادسة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/80891c7094d2decb402c62bb3da7e17b.jpg" alt="Ansel Art">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 67 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%204%20(الطبيعة)&price=67"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 17. اللوحة السابعة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/89f1274c3adce46bebdf957b4606b8f7.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 94 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%204%20(الطبيعة)&price=94"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 18. اللوحة الثامنة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/8e607fa1f1656b57a12b9df88381dcf0.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 88 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%204%20(الطبيعة)&price=88"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 19. اللوحة التاسعة عشرة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/95c7258d9909c8f7c9a3deed4a7f0c29.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة بتفاصيل ناعمه وهادئه باسلوب معاصر</p>
                        <p class="price">السعر : 73 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%204%20(الطبيعة)&price=73"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 20. اللوحة العشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/9b19669c3a6b2edf36f6160bf625dc3a.jpg" alt="Ali Art">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة فنيه تعكس جمال الطبيعة</p>
                        <p class="price">السعر : 75 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%204%20(الطبيعة)&price=75"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 21. اللوحة الحادية والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/a297bd11bd9c7487cd12273b011d694e.jpg" alt="Ansel Art">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 66 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%205%20(الطبيعة)&price=66"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 22. اللوحة الثانية والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/ba501f82709812d3eab89c26e1b69ab7.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 76 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%205%20(الطبيعة)&price=76"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 23. اللوحة الثالثة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/c0fa8cc64717fdcf0b7d6101132bcfbb.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 64 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%205%20(الطبيعة)&price=64"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 24. اللوحة الرابعة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/c6ecb4e4f7bc66ff82cc5294b3f8accd.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة بتفاصيل ناعمه وهادئه باسلوب معاصر</p>
                        <p class="price">السعر : 67 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%205%20(الطبيعة)&price=67"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 25. اللوحة الخامسة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/ca82f214974f06a896ea093dafbb576f.jpg" alt="Ali Art">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة فنيه تعكس جمال الطبيعة</p>
                        <p class="price">السعر : 76 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%205%20(الطبيعة)&price=76"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 26. اللوحة السادسة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/ca9e88877e6c309b138fcac67229452b.jpg" alt="Ansel Art">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 88 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%206%20(الطبيعة)&price=88"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 27. اللوحة السابعة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/d41aff99795840e7a00ebf2da5a8fb9d.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 90 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%206%20(الطبيعة)&price=90"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 28. اللوحة الثامنة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/df22b0c09efebf47372efb87b5ecd773.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 92 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%206%20(الطبيعة)&price=92"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 29. اللوحة التاسعة والعشرون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/e6820719c1afb46d6f37fdf7784d7eee.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة بتفاصيل ناعمه وهادئه باسلوب معاصر</p>
                        <p class="price">السعر : 84 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%206%20(الطبيعة)&price=84"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 30. اللوحة الثلاثون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/e740e4f886613c8e78a75a70293e18ee.jpg" alt="Ali Art">
                        <h3>Ali Art :الفنان</h3>
                        <p>لوحة فنيه تعكس جمال الطبيعة</p>
                        <p class="price">السعر : 81 دولار</p>
                    </div>
                    <a href="order.php?art=Ali%20Art%206%20(الطبيعة)&price=81"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 31. اللوحة الحادية والثلاثون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/e8514177cc093938efef53a0305dcf75.jpg" alt="Frank Art">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحة مستوحاة من الطبيعة , تمنح المكان احساسا بالهدوء والانسجام</p>
                        <p class="price">السعر : 77 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%207%20(الطبيعة)&price=77"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 32. اللوحة الثانية والثلاثون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/f3dfbb4d691f4472a899664b76fb5a06.jpg" alt="Mono Art">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحة طبيعيه وناعمة بالوان متدرجة</p>
                        <p class="price">السعر : 66 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%207%20(الطبيعة)&price=66"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- 33. اللوحة الثالثة والثلاثون -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Tabaeah/fe8938a110e2604637d62499c0491bf8.jpg" alt="Lina Art">
                        <h3>Lina Art :الفنان</h3>
                        <p>لوحة طبيعيه مناسبة لاماكن الاسترخاء</p>
                        <p class="price">السعر : 70 دولار</p>
                    </div>
                    <a href="order.php?art=Lina%20Art%207%20(الطبيعة)&price=70"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- عرض المنتجات المضافة ديناميكياً من قاعدة البيانات لقسم الطبيعة -->
                <?php if (count($db_products) > 0): ?>
                    <?php foreach ($db_products as $product): ?>
                        <div class="cake-item">
                            <div>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="price">السعر : <?php echo htmlspecialchars($product['price']); ?> دولار</p>
                            </div>
                            <a href="order.php?art=<?php echo urlencode($product['title'] . ' (الطبيعة)'); ?>&price=<?php echo urlencode($product['price']); ?>">
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