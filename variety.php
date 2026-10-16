<?php
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// جلب اللوحات الخاصة بالقسم "variety" من قاعدة البيانات لتطابق ما يتم إدخاله من لوحة التحكم
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
$stmt->execute(['variety']);
$db_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منوعات</title>
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
                        <li><a href="nature-art.php">لوحات من الطبيعة</a></li>
                    </ul>
                </li>
                <li><a href="order.php"> طلب</a></li>
                <li><a href="contact.php"> للتواصل</a></li>
                <li><a href="about-us.php" target="_self"> من نحن</a></li>
            </ul>
        </nav>
        <link rel="stylesheet" href="responsive.css">
    </header>
    
    <section class="gallery-intro">
        <h2>لوحات منوعات</h2>
        <p>تشكيلة متنوعة من الأعمال الفنية، تجمع بين مختلف الأساليب لتناسب ذوقك الفريد.</p>
    </section>

    <div class="container">
        <div class="category">
            <div class="cake-list">
                
                <!-- الصور الثابتة -->
                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/0cb3de16dd0714722e088da00f106134.jpg" alt="Art Mix">
                        <h3>Art Mix :الفنان</h3>
                        <p>لوحة منوعة تعكس أسلوباً فنياً فريداً ومميزاً</p>
                        <p class="price">السعر : 78 دولار</p>
                    </div>
                    <a href="order.php?art=Art%20Mix%20(منوعات)&price=78"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/1b5a9b11b9ab3935d252345f0d3fc530.jpg" alt="Modern Art">
                        <h3>Modern Art :الفنان</h3>
                        <p>تصميم عصري يضفي لمسة جمالية على المكان</p>
                        <p class="price">السعر : 82 دولار</p>
                    </div>
                    <a href="order.php?art=Modern%20Art%20(منوعات)&price=82"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/6e9abd2992e29176edd1eb96267b57c8.jpg" alt="Classic Touch">
                        <h3>Classic Touch :الفنان</h3>
                        <p>لمسة كلاسيكية أنيقة تناسب مختلف الديكورات</p>
                        <p class="price">السعر : 90 دولار</p>
                    </div>
                    <a href="order.php?art=Classic%20Touch%20(منوعات)&price=90"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/008d227b7a53fa615fbafced45982def.jpg" alt="Creative Hub">
                        <h3>Creative Hub :الفنان</h3>
                        <p>لوحة إبداعية تجمع بين الألوان والتفاصيل الهادئة</p>
                        <p class="price">السعر : 75 دولار</p>
                    </div>
                    <a href="order.php?art=Creative%20Hub%20(منوعات)&price=75"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/9b2388781abc79635e398d6bb5fecb33.jpg" alt="Vibe Art">
                        <h3>Vibe Art :الفنان</h3>
                        <p>تصميم فني يعبر عن الحيوية والجمال المعاصر</p>
                        <p class="price">السعر : 88 دولار</p>
                    </div>
                    <a href="order.php?art=Vibe%20Art%20(منوعات)&price=88"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/037ab80b138ef988564a7bd7d97c57a4.jpg" alt="Abstract Mix">
                        <h3>Abstract Mix :الفنان</h3>
                        <p>لوحة تجريدية تمنح المساحة بعداً جمالياً خاصاً</p>
                        <p class="price">السعر : 70 دولار</p>
                    </div>
                    <a href="order.php?art=Abstract%20Mix%20(منوعات)&price=70"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/56da773f9bbdcfd9175602c5eea2f49d.jpg" alt="Dream Art">
                        <h3>Dream Art :الفنان</h3>
                        <p>تكوين فني هادئ ومريح للعين</p>
                        <p class="price">السعر : 85 دولار</p>
                    </div>
                    <a href="order.php?art=Dream%20Art%20(منوعات)&price=85"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/93a8a5e7fcfa2a1550fd07dda1f8cd86.jpg" alt="Color Fusion">
                        <h3>Color Fusion :الفنان</h3>
                        <p>مزيج متناسق من الألوان والتفاصيل الفنية</p>
                        <p class="price">السعر : 92 دولار</p>
                    </div>
                    <a href="order.php?art=Color%20Fusion%20(منوعات)&price=92"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/370eb1725d30d46e1e31ea860dabf35c.jpg" alt="Harmony Art">
                        <h3>Harmony Art :الفنان</h3>
                        <p>لوحة تعبر عن الانسجام والاتزان البصري</p>
                        <p class="price">السعر : 81 دولار</p>
                    </div>
                    <a href="order.php?art=Harmony%20Art%20(منوعات)&price=81"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/625b1576b2e57048426d0c2becf8d6c1.jpg" alt="Urban Style">
                        <h3>Urban Style :الفنان</h3>
                        <p>فن معاصر يناسب الديكورات الحديثة</p>
                        <p class="price">السعر : 77 دولار</p>
                    </div>
                    <a href="order.php?art=Urban%20Style%20(منوعات)&price=77"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/930e1c4bc9fbad311e530251b53f1f91.jpg" alt="Minimalist Art">
                        <h3>Minimalist Art :الفنان</h3>
                        <p>تصميم بسيط وراقي يعتمد على النعومة</p>
                        <p class="price">السعر : 68 دولار</p>
                    </div>
                    <a href="order.php?art=Minimalist%20Art%20(منوعات)&price=68"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/3692ce703e794d3aa64525bd650b9b65.jpg" alt="Chic Art">
                        <h3>Chic Art :الفنان</h3>
                        <p>لوحة أنيقة تضيف طابعاً مميزاً للمكان</p>
                        <p class="price">السعر : 84 دولار</p>
                    </div>
                    <a href="order.php?art=Chic%20Art%20(منوعات)&price=84"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/24204dc6827514f6563f8532700467a0.jpg" alt="Elite Art">
                        <h3>Elite Art :الفنان</h3>
                        <p>تصميم فخم يعكس الذوق الرفيع</p>
                        <p class="price">السعر : 95 دولار</p>
                    </div>
                    <a href="order.php?art=Elite%20Art%20(منوعات)&price=95"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/51186ebc928c450225ee6feffb2f144c.jpg" alt="Gallery Mix">
                        <h3>Gallery Mix :الفنان</h3>
                        <p>لوحة فنية مناسبة لجميع الغرف والمساحات</p>
                        <p class="price">السعر : 73 دولار</p>
                    </div>
                    <a href="order.php?art=Gallery%20Mix%20(منوعات)&price=73"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/638293a28618d788387441c62a45407f.jpg" alt="Soft Touch">
                        <h3>Soft Touch :الفنان</h3>
                        <p>تفاصيل هادئة وناعمة تضفي راحة نفسية</p>
                        <p class="price">السعر : 80 دولار</p>
                    </div>
                    <a href="order.php?art=Soft%20Touch%20(منوعات)&price=80"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/a68c9d83d68ab779589313fa79f3b78b.jpg" alt="Golden Era">
                        <h3>Golden Era :الفنان</h3>
                        <p>لمسات فنية تدمج الأصالة بالحداثة</p>
                        <p class="price">السعر : 89 دولار</p>
                    </div>
                    <a href="order.php?art=Golden%20Era%20(منوعات)&price=89"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/a213d215ab3ef682439c488b42133786.jpg" alt="Pure Art">
                        <h3>Pure Art :الفنان</h3>
                        <p>لوحة نقية بتصميم هادئ ومريح</p>
                        <p class="price">السعر : 74 دولار</p>
                    </div>
                    <a href="order.php?art=Pure%20Art%20(منوعات)&price=74"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/a70195ef3b6c204a3f7f8d99cc9f9803.jpg" alt="Vision Art">
                        <h3>Vision Art :الفنان</h3>
                        <p>رؤية فنية مبتكرة تناسب الأذواق الرفيعة</p>
                        <p class="price">السعر : 86 دولار</p>
                    </div>
                    <a href="order.php?art=Vision%20Art%20(منوعات)&price=86"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/c2e5e8ce99772ce32db246274f857246.jpg" alt="Aura Art">
                        <h3>Aura Art :الفنان</h3>
                        <p>تأثير بصري جذاب يمنح المكان حياة</p>
                        <p class="price">السعر : 82 دولار</p>
                    </div>
                    <a href="order.php?art=Aura%20Art%20(منوعات)&price=82"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/cf7f6566922c835655a2d00e579e36be.jpg" alt="Style Mix">
                        <h3>Style Mix :الفنان</h3>
                        <p>تشكيلة مميزة من الفن المعاصر</p>
                        <p class="price">السعر : 79 دولار</p>
                    </div>
                    <a href="order.php?art=Style%20Mix%20(منوعات)&price=79"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/f409e934eb22b8c29d9954e6be47f8f0.jpg" alt="Serenity Art">
                        <h3>Serenity Art :الفنان</h3>
                        <p>لوحة تمنح المكان شعوراً بالسلام والسكينة</p>
                        <p class="price">السعر : 83 دولار</p>
                    </div>
                    <a href="order.php?art=Serenity%20Art%20(منوعات)&price=83"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/f5261307b25605239a41f0caca668fff.jpg" alt="Dynamic Art">
                        <h3>Dynamic Art :الفنان</h3>
                        <p>تصميم ينبض بالحركة والحيوية البصرية</p>
                        <p class="price">السعر : 87 دولار</p>
                    </div>
                    <a href="order.php?art=Dynamic%20Art%20(منوعات)&price=87"><button class="order-button">اطلب الآن</button></a>
                </div>

                <div class="cake-item">
                    <div>
                        <img src="imagee/Munwa/fd56137827972d80593639fd9801a964.jpg" alt="Prime Art">
                        <h3>Prime Art :الفنان</h3>
                        <p>لوحة فاخرة بتفاصيل دقيقة ومتناسقة</p>
                        <p class="price">السعر : 91 دولار</p>
                    </div>
                    <a href="order.php?art=Prime%20Art%20(منوعات)&price=91"><button class="order-button">اطلب الآن</button></a>
                </div>

                <!-- عرض المنتجات المضافة ديناميكياً من قاعدة البيانات (مطابقة لـ variety) -->
                <?php if (count($db_products) > 0): ?>
                    <?php foreach ($db_products as $product): ?>
                        <div class="cake-item">
                            <div>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="price">السعر : <?php echo htmlspecialchars($product['price']); ?> دولار</p>
                            </div>
                            <a href="order.php?art=<?php echo urlencode($product['title'] . ' (منوعات)'); ?>&price=<?php echo urlencode($product['price']); ?>">
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