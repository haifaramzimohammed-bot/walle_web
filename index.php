<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user) {
                // مطابقة كلمة المرور (يمكنك استبدالها بـ password_verify إن كنتِ تستخدمين التشفير)
                if ($password === $user['password']) {
                    $_SESSION['username'] = $user['username'];
                    // استخدام trim لضمان عدم وجود مسافات زائدة قد تعطل الصلاحية
                    $_SESSION['role'] = trim($user['role']);

                    // التوجيه الذكي حسب الصلاحية
                    if (strtolower($_SESSION['role']) === 'admin') {
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        header("Location: home.php");
                        exit();
                    }
                } else {
                    $error = "كلمة المرور غير صحيحة، يرجى المحاولة مرة أخرى.";
                }
            } else {
                $error = "اسم المستخدم غير موجود، يرجى إنشاء حساب جديد.";
            }
        } catch (PDOException $e) {
            $error = "حدث خطأ في الاتصال بقاعدة البيانات.";
        }
    } else {
        $error = "الرجاء إدخال اسم المستخدم وكلمة المرور.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Wallé Store</title>
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
        .login-box {
            background: #fffdf9;
            width: 380px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(115, 61, 61, 0.1);
            text-align: center;
            border: 1px solid #e6dcd0;
        }
        .login-box h2 {
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
        .login-box input {
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
        .login-box input:focus { border-color: #8c3b44; }
        .login-box button {
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
        .login-box button:hover { background-color: #6b2d34; }
        .register-link {
            margin-top: 20px;
            font-size: 0.9em;
            color: #7d6b67;
        }
        .register-link a {
            color: #8c3b44;
            text-decoration: none;
            font-weight: bold;
        }
        .register-link a:hover { text-decoration: underline; }
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
    <div class="login-box">
        <h2>Wallé Store</h2>
        <div class="subtitle">تسجيل الدخول إلى حسابك</div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="اسم المستخدم" required autocomplete="off">
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">تسجيل الدخول</button>
        </form>

        <div class="register-link">
            ليس لديك حساب؟ <a href="register.php">أنشئ حساباً جديداً</a>
        </div>
    </div>
</body>
</html>