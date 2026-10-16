<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $error = "اسم المستخدم أو البريد الإلكتروني مسجل مسبقاً، يرجى اختيار آخر.";
            } else {
                // تسجيل الزائر الجديد بصلاحية customer افتراضياً
                $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
                $insertStmt->execute([$username, $email, $password]);

                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'customer';
                
                header("Location: home.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "حدث خطأ في قاعدة البيانات، يرجى المحاولة لاحقاً.";
        }
    } else {
        $error = "الرجاء ملء جميع الحقول المطلوبة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - Wallé Store</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f3ed;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            background: #fffdf9;
            width: 380px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(115, 61, 61, 0.1);
            text-align: center;
            border: 1px solid #e6dcd0;
        }
        .register-box h2 {
            color: #6b2d34;
            margin-bottom: 5px;
            font-size: 1.9em;
            border-bottom: 3px solid #8c3b44;
            display: inline-block;
            padding-bottom: 5px;
        }
        .subtitle {
            font-size: 0.9em;
            color: #7d6b67;
            margin: 15px 0 25px 0;
        }
        .register-box input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #d4c5b9;
            border-radius: 6px;
            font-size: 1em;
            text-align: right;
            outline: none;
            background-color: #fff;
            transition: border-color 0.3s;
        }
        .register-box input:focus { border-color: #8c3b44; }
        .register-box button {
            background-color: #8c3b44;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        .register-box button:hover { background-color: #6b2d34; }
        .login-link {
            margin-top: 20px;
            font-size: 0.9em;
            color: #7d6b67;
        }
        .login-link a {
            color: #8c3b44;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover { text-decoration: underline; }
        .error {
            color: #8c3b44;
            background-color: #fce8e6;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9em;
            margin-bottom: 20px;
            font-weight: bold;
            border: 1px solid #f5c2c7;
            text-align: right;
        }
    </style>
    <link rel="stylesheet" href="responsive.css">
</head>
<body>
    <div class="register-box">
        <h2>Wallé Store</h2>
        <div class="subtitle">إنشاء حساب جديد للمتجر</div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="اسم المستخدم" required autocomplete="off">
            <input type="email" name="email" placeholder="البريد الإلكتروني (Gmail)" required autocomplete="off">
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">تسجيل الحساب</button>
        </form>

        <div class="login-link">
            لديك حساب مسبقاً؟ <a href="index.php">سجل دخولك هنا</a>
        </div>
    </div>
</body>
</html>