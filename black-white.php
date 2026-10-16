<?php
session_start();
require_once 'db.php';

// جلب لوحات قسم الأبيض والأسود الإضافية من قاعدة البيانات
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
    $stmt->execute(['black-white']);
    $black_white_artworks = $stmt->fetchAll();
} catch (PDOException $e) {
    $black_white_artworks = [];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحات أبيض وأسود</title>
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

        /* تعديل الهيدر ليصبح ثابتاً (Sticky Header) */
        header {
            background-color: #59222c;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
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
                      
                        <li><a href="arabic-art.php"> لوحات عربية </a></li>
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
        <h2>لوحات ابيض واسود</h2>
        <p>تكوينات بالأبيض والأسود تكشف قوة الخط والظل، وتمنح المساحات أناقة هادئة لا تنتهي.</p>
    </section>

    <div class="container">
        <div class="category">
            <div class="cake-list">
                
                <!-- اللوحات المضافة عبر قاعدة البيانات (إن وجدت) -->
                <?php if (count($black_white_artworks) > 0): ?>
                    <?php foreach ($black_white_artworks as $art): ?>
                        <div class="cake-item">
                            <div>
                                <?php if (!empty($art['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($art['image']); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>">
                                <?php else: ?>
                                    <img src="imagee/default-art.jpg" alt="صورة افتراضية">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($art['title']); ?></h3>
                                <p><?php echo htmlspecialchars($art['description']); ?></p>
                                <p class="price">السعر : <?php echo htmlspecialchars($art['price']); ?> دولار</p>
                            </div>
                            <a href="order.php?art=<?php echo urlencode($art['title']); ?>&price=<?php echo urlencode($art['price']); ?>">
                                <button class="order-button">اطلب الآن</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- الصور واللوحات القديمة الثابتة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/0076bee121c5db676b645de2fce65735.jpg" alt="">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(أبيض%20وأسود)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/0c6b4d7fa8d5f2eaf8b9e8fa5ed11a42.jpg" alt="">
                        <h3>Black Art :الفنان</h3>
                        <p>لوحه تعتمد على التباين بين الابيض والاسود باسلوب فني راقٍ</p>
                        <p class="price">السعر : 55 دولار</p>
                    </div>
                    <a href="order.php?art=Black%20Art%20(أبيض%20وأسود)&price=55"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/0f618a98109233c4af1c34b7cdb09443.jpg" alt="">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحه فنيه تعكس الجمال في البساطه باستخدام اللونين الابيض والاسود</p>
                        <p class="price">السعر : 50 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%20(أبيض%20وأسود)&price=50"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/1b747da752b5091b800ca43893ed5141.jpg" alt="">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق يناسب المساحات الحديثه</p>
                        <p class="price">السعر : 47 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%20(أبيض%20وأسود)&price=47"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/1e3f1d068027e8841af77a77c451134f.jpg" alt="">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحه تعتمد على التباين بين الابيض والاسود باسلوب فني راقٍ</p>
                        <p class="price">السعر : 49 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(أبيض%20وأسود)&price=49"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">  
                    <div>
                        <img src="imagee/Blak and white/2cff0a6f6595672e8f16dc4636487148.jpg" alt="">
                        <h3>Black Art :الفنان</h3>
                        <p>لوحه فنيه تعكس الجمال في البساطه باستخدام اللونين الابيض والاسود</p>
                        <p class="price">السعر : 44 دولار</p>
                    </div>
                    <a href="order.php?art=Black%20Art%20(أبيض%20وأسود)&price=44"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/3ab700e1e003084543e70e541a1f593e.jpg" alt="">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق</p>
                        <p class="price">السعر : 65 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%20(أبيض%20وأسود)&price=65"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/5a8075350ee74f1fea09b7c15da32619.jpg" alt="">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق يناسب المساحات الحديثه</p>
                        <p class="price">السعر : 55 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%20(أبيض%20وأسود)&price=55"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/5caf0c8971c60c6f68c96b427edd3861.jpg" alt="">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحه تعتمد على التباين بين الابيض والاسود باسلوب فني راقٍ</p>
                        <p class="price">السعر : 68 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(أبيض%20وأسود)&price=68"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/62dc291f0de69867036256d19afd242f.jpg" alt="">
                        <h3>Black Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق يناسب المساحات الحديثه</p>
                        <p class="price">السعر : 54 دولار</p>
                    </div>
                    <a href="order.php?art=Black%20Art%20(أبيض%20وأسود)&price=54"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/7476b8fd75939605c60d99fbef89a7dc.jpg" alt="">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحه تعتمد على التباين بين الابيض والاسود باسلوب فني راقٍ</p>
                        <p class="price">السعر : 56 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%20(أبيض%20وأسود)&price=56"><button class="order-button">اطلب الآن</button></a>
                </div> 

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/7bd86955b534451c4836db891cb6eed4.jpg" alt="">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق</p>
                        <p class="price">السعر : 59 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%20(أبيض%20وأسود)&price=59"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/7d883697ce96ab0624b0f8a35f0ff9b3.jpg" alt="">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحه فنيه تعكس الجمال في البساطه باستخدام اللونين الابيض والاسود</p>
                        <p class="price">السعر : 49 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(أبيض%20وأسود)&price=49"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/96e636ae63540b8ab94fa5eeb21f8dc1.jpg" alt="">
                        <h3>Black Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق يناسب المساحات الحديثه</p>
                        <p class="price">السعر : 46 دولار</p>
                    </div>
                    <a href="order.php?art=Black%20Art%20(أبيض%20وأسود)&price=46"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/a864a6352b79f65719543f65f7f92d29.jpg" alt="">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحه تعتمد على التباين بين الابيض والاسود باسلوب فني راقٍ</p>
                        <p class="price">السعر : 75 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%20(أبيض%20وأسود)&price=75"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/c4bb2951e1f6caf5ccf3c9263f639c3b.jpg" alt="">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق</p>
                        <p class="price">السعر : 70 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%20(أبيض%20وأسود)&price=70"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/cc3d0a63336cf9ab4b19bcf36e5b0246.jpg" alt="">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحه فنيه تعكس الجمال في البساطه باستخدام اللونين الابيض والاسود</p>
                        <p class="price">السعر : 73 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(أبيض%20وأسود)&price=73"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/e9fd784fbe9c34ed4a92b7c847450b41.jpg" alt="">
                        <h3>Black Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق يناسب المساحات الحديثه</p>
                        <p class="price">السعر : 56 دولار</p>
                    </div>
                    <a href="order.php?art=Black%20Art%20(أبيض%20وأسود)&price=56"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/ecaa78e4dbef93256e1499960e266c6d.jpg" alt="">
                        <h3>Ansel Art :الفنان</h3>
                        <p>لوحه تعتمد على التباين بين الابيض والاسود باسلوب فني راقٍ</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Ansel%20Art%20(أبيض%20وأسود)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/ed8cf0fed7c2e618f661352e36d2b0be.jpg" alt="">
                        <h3>Frank Art :الفنان</h3>
                        <p>لوحه فنيه تعكس الجمال في البساطه باستخدام اللونين الابيض والاسود</p>
                        <p class="price">السعر : 51 دولار</p>
                    </div>
                    <a href="order.php?art=Frank%20Art%20(أبيض%20وأسود)&price=51"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Blak and white/ff54968d7ad10b37568d5772541b1905.jpg" alt="">
                        <h3>Mono Art :الفنان</h3>
                        <p>لوحه بالابيض والاسود بيصميم بسيط وانيق يناسب المساحات الحديثه</p>
                        <p class="price">السعر : 45 دولار</p>
                    </div>
                    <a href="order.php?art=Mono%20Art%20(أبيض%20وأسود)&price=45"><button class="order-button">اطلب الآن</button></a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>