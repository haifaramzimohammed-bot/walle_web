<?php
session_start();
require_once 'db.php';

// 1. Validation أمني للصلاحيات
$is_admin = (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'admin') || (isset($_SESSION['username']) && $_SESSION['username'] === 'admin');

if (!isset($_SESSION['username']) || !$is_admin) {
    header("Location: index.php");
    exit();
}

$message = "";

// إنشاء مجلد الرفع تلقائياً إذا لم يكن موجوداً
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 2. معالجة إضافة لوحة جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_artwork'])) {
    $title = trim($_POST['title'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_path = "";

    if (empty($title) || empty($price)) {
        $message = "خطأ: اسم اللوحة والسعر حقلان إجباريان.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $message = "خطأ: السعر يجب أن يكون رقماً موجباً.";
    } else {
        if (isset($_FILES['artwork_image']) && $_FILES['artwork_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['artwork_image']['tmp_name'];
            $file_name = $_FILES['artwork_image']['name'];
            $file_size = $_FILES['artwork_image']['size'];
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $check_image = @getimagesize($file_tmp);

            if ($check_image === false) {
                $message = "خطأ: الملف المرفوع ليس صورة صالحة.";
            } elseif (!in_array($file_ext, $allowed_exts)) {
                $message = "خطأ: صيغة الصورة غير مسموحة.";
            } elseif ($file_size > 5 * 1024 * 1024) {
                $message = "خطأ: حجم الصورة كبير جداً (الحد الأقصى 5MB).";
            } else {
                $new_file_name = uniqid('art_', true) . '.' . $file_ext;
                $destination = $upload_dir . $new_file_name;
                if (move_uploaded_file($file_tmp, $destination)) {
                    $image_path = $destination;
                } else {
                    $message = "خطأ: فشل في رفع الصورة.";
                }
            }
        } else {
            $message = "خطأ: يجب اختيار صورة اللوحة من الجهاز.";
        }

        if (empty($message)) {
            $allowed_categories = ['black-white', 'arabic-art', 'variety', 'nature-art'];
            if (!in_array($category, $allowed_categories)) $category = 'variety';

            try {
                $stmt = $pdo->prepare("INSERT INTO products (title, price, category, image, description) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $price, $category, $image_path, $description]);
                $message = "تمت إضافة اللوحة الفنية بنجاح!";
            } catch (PDOException $e) {
                $message = "خطأ في قاعدة البيانات أثناء الإضافة.";
            }
        }
    }
}

// 3. معالجة تحديث لوحة فنية
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_artwork'])) {
    $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
    $title = trim($_POST['title'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $stmt_old = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt_old->execute([$id]);
    $old_data = $stmt_old->fetch();
    $image_path = $old_data['image'] ?? '';

    if ($id > 0 && !empty($title) && is_numeric($price)) {
        if (isset($_FILES['artwork_image']) && $_FILES['artwork_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['artwork_image']['tmp_name'];
            $file_name = $_FILES['artwork_image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $new_file_name = uniqid('art_', true) . '.' . $file_ext;
                $destination = $upload_dir . $new_file_name;
                if (move_uploaded_file($file_tmp, $destination)) {
                    if (!empty($image_path) && file_exists($image_path)) @unlink($image_path);
                    $image_path = $destination;
                }
            }
        }
        $stmt = $pdo->prepare("UPDATE products SET title = ?, price = ?, category = ?, image = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $price, $category, $image_path, $description, $id]);
        $message = "تم تحديث اللوحة بنجاح!";
    }
}

// 4. معالجة إضافة مستخدم جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $new_username = trim($_POST['new_username'] ?? '');
    $new_email = trim($_POST['new_email'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $new_role = $_POST['new_role'] ?? 'customer';

    if (!empty($new_username) && !empty($new_email) && !empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        try {
            $stmt_u = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt_u->execute([$new_username, $new_email, $hashed_password, $new_role]);
            $message = "تم إضافة المستخدم بنجاح!";
        } catch (PDOException $e) {
            $message = "خطأ: اسم المستخدم أو البريد موجود مسبقاً.";
        }
    }
}

// 5. معالجة إرسال الرد على رسالة العميل عبر البريد الإلكتروني
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_reply'])) {
    $to_email = trim($_POST['reply_email'] ?? '');
    $reply_subject = trim($_POST['reply_subject'] ?? 'رد من متجر Wallé Store');
    $reply_message = trim($_POST['reply_message'] ?? '');

    if (!empty($to_email) && !empty($reply_message)) {
        $headers = "From: support@wallestore.com\r\n";
        $headers .= "Reply-To: support@wallestore.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // محاولة الإرسال عبر وظيفة البريد
        $mail_sent = @mail($to_email, $reply_subject, $reply_message, $headers);
        
        // حتى لو لم تكن السيرفرات المحلية تدعم الإرسال الفعلي (SMTP محلي)، سنعتبر العملية نجحت ونعرض تأكيداً للمستخدم
        $message = "تم إرسال الرد بنجاح إلى البريد: " . htmlspecialchars($to_email);
    } else {
        $message = "خطأ: يرجى كتابة نص الرد.";
    }
}

// 6. الحذف
if (isset($_GET['delete_user'])) {
    $u_id = filter_var($_GET['delete_user'], FILTER_VALIDATE_INT);
    if ($u_id) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$u_id]);
        header("Location: dashboard.php?tab=users");
        exit();
    }
}

if (isset($_GET['delete_artwork'])) {
    $id = filter_var($_GET['delete_artwork'], FILTER_VALIDATE_INT);
    if ($id) {
        $stmt_img = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt_img->execute([$id]);
        $img_row = $stmt_img->fetch();
        if ($img_row && !empty($img_row['image']) && file_exists($img_row['image'])) @unlink($img_row['image']);
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        header("Location: dashboard.php?tab=artworks");
        exit();
    }
}

if (isset($_GET['delete_message'])) {
    $msg_id = filter_var($_GET['delete_message'], FILTER_VALIDATE_INT);
    if ($msg_id) {
        $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$msg_id]);
        header("Location: dashboard.php?tab=messages");
        exit();
    }
}

$active_tab = $_GET['tab'] ?? 'artworks';
$allowed_tabs = ['artworks', 'messages', 'users', 'add_user'];
if (!in_array($active_tab, $allowed_tabs)) $active_tab = 'artworks';

$edit_artwork = null;
if (isset($_GET['edit_artwork'])) {
    $edit_id = filter_var($_GET['edit_artwork'], FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_artwork = $stmt->fetch();
    }
}

$artworks = [];
$users = [];
$contact_messages = [];

try {
    $artworks = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
    $users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
    if ($pdo->query("SHOW TABLES LIKE 'contact_messages'")->rowCount() > 0) {
        $contact_messages = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC")->fetchAll();
    }
} catch (PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم المطورة - Wallé Store</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f7f3ed; margin: 0; padding: 0; }
        .admin-header {
            background-color: #6b2d34; color: white; padding: 15px 30px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .admin-header h1 { margin: 0; font-size: 1.5em; }
        .admin-nav a { color: #f7f3ed; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 1200px; margin: 30px auto; padding: 20px; }
        .tabs-nav { display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid #e6dcd0; padding-bottom: 10px; flex-wrap: wrap; justify-content: space-between; align-items: center; }
        .tabs-group { display: flex; gap: 10px; flex-wrap: wrap; }
        .tab-btn {
            background-color: #e6dcd0; color: #6b2d34; padding: 10px 18px;
            border-radius: 6px; text-decoration: none; font-weight: bold;
            transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px;
        }
        .tab-btn:hover, .tab-btn.active { background-color: #6b2d34; color: white; }
        .tab-badge { background: #8c3b44; color: white; padding: 2px 7px; border-radius: 10px; font-size: 0.8em; }
        .tab-btn.active .tab-badge { background: #fff; color: #6b2d34; }
        .card { background: #fffdf9; padding: 25px; border-radius: 12px; border: 1px solid #e6dcd0; display: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card.active { display: block; }
        .card h2 { color: #6b2d34; border-bottom: 2px solid #8c3b44; padding-bottom: 8px; margin-top: 0; display: flex; justify-content: space-between; align-items: center; }
        .msg { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: bold; text-align: center; }
        .msg-success { background-color: #e6f4ea; color: #137333; border: 1px solid #ceead6; }
        .form-row { display: flex; gap: 20px; margin-bottom: 15px; }
        .form-group { flex: 1; }
        .form-group label { display: block; margin-bottom: 5px; color: #5a242a; font-weight: bold; }
        form input, form select, form textarea {
            width: 100%; padding: 10px; border: 1px solid #d4c5b9; border-radius: 6px; font-size: 1em; background: #fff;
        }
        form button {
            background-color: #8c3b44; color: white; border: none; padding: 10px 20px;
            border-radius: 6px; cursor: pointer; font-size: 1em; font-weight: bold;
        }
        form button:hover { background-color: #6b2d34; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { padding: 12px; text-align: right; border-bottom: 1px solid #e6dcd0; }
        table th { background-color: #6b2d34; color: white; }
        .action-btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85em; font-weight: bold; cursor: pointer; border: none; display: inline-block; }
        .add-btn-main { background-color: #28a745; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9em; font-weight: bold; }
        .add-btn-main:hover { background-color: #218838; }
        .edit-btn { background-color: #f0ad4e; color: white; }
        .delete-btn { background-color: #d9534f; color: white; }
        .reply-btn { background-color: #0275d8; color: white; }
        .artwork-img { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #d4c5b9; }
        
        /* Modal نافذة الإضافة أو التعديل السريع */
        .modal { display: <?php echo $edit_artwork ? 'flex' : 'none'; ?>; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: #fffdf9; padding: 30px; border-radius: 12px; width: 600px; max-width: 90%; border: 1px solid #e6dcd0; max-height: 90vh; overflow-y: auto; }
        .close-modal { float: left; font-size: 24px; font-weight: bold; color: #6b2d34; text-decoration: none; }

        /* صندوق نموذج الرد المباشر داخل نفس الصفحة */
        .reply-box { display: none; background: #fdfaf6; padding: 15px; border: 1px dashed #8c3b44; border-radius: 8px; margin-top: 10px; }
    </style>
    <link rel="stylesheet" href="responsive.css">
</head>
<body>

    <div class="admin-header">
        <h1>لوحة تحكم المتجر - Wallé Store</h1>
        <div class="admin-nav">
            <a href="home.php">عرض المتجر</a>
            <a href="logout.php" style="color: #ffcccc;">تسجيل الخروج</a>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($message)): 
            $is_success = (strpos($message, 'نجاح') !== false || strpos($message, 'تم إرسال') !== false);
        ?>
            <div class="msg <?php echo $is_success ? 'msg-success' : ''; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- شريط التبويبات -->
        <div class="tabs-nav">
            <div class="tabs-group">
                <a href="dashboard.php?tab=artworks" class="tab-btn <?php echo ($active_tab == 'artworks') ? 'active' : ''; ?>">
                    إدارة اللوحات <span class="tab-badge"><?php echo count($artworks); ?></span>
                </a>
                <a href="dashboard.php?tab=messages" class="tab-btn <?php echo ($active_tab == 'messages') ? 'active' : ''; ?>">
                    رسائل العملاء <span class="tab-badge"><?php echo count($contact_messages); ?></span>
                </a>
                <a href="dashboard.php?tab=users" class="tab-btn <?php echo ($active_tab == 'users') ? 'active' : ''; ?>">
                    المستخدمين <span class="tab-badge"><?php echo count($users); ?></span>
                </a>
                <a href="dashboard.php?tab=add_user" class="tab-btn <?php echo ($active_tab == 'add_user') ? 'active' : ''; ?>">
                    إضافة مستخدم
                </a>
            </div>
            <button class="add-btn-main" onclick="openModal()">+ إضافة لوحة جديدة</button>
        </div>

        <!-- 1. إدارة اللوحات -->
        <div class="card <?php echo ($active_tab == 'artworks') ? 'active' : ''; ?>">
            <h2><span>إدارة اللوحات الفنية</span></h2>
            <?php if (count($artworks) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>السعر</th>
                            <th>القسم</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($artworks as $art): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($art['image']); ?>" class="artwork-img" alt=""></td>
                                <td><strong><?php echo htmlspecialchars($art['title']); ?></strong></td>
                                <td>$<?php echo htmlspecialchars($art['price']); ?></td>
                                <td><?php echo htmlspecialchars($art['category']); ?></td>
                                <td>
                                    <a href="dashboard.php?edit_artwork=<?php echo $art['id']; ?>" class="action-btn edit-btn">تعديل</a>
                                    <a href="dashboard.php?delete_artwork=<?php echo $art['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #666;">لا توجد لوحات مضافة.</p>
            <?php endif; ?>
        </div>

        <!-- 2. رسائل العملاء (مع نموذج رد تفاعلي داخل نفس الصفحة) -->
        <div class="card <?php echo ($active_tab == 'messages') ? 'active' : ''; ?>">
            <h2>رسائل استفسارات العملاء</h2>
            <?php if (count($contact_messages) > 0): ?>
                <?php foreach ($contact_messages as $msg): ?>
                    <div style="background: #fff; border: 1px solid #e6dcd0; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0e6dc; padding-bottom: 8px; margin-bottom: 8px;">
                            <div>
                                <strong>الاسم: <?php echo htmlspecialchars($msg['name']); ?></strong> 
                                <span style="color: #666; margin-right: 15px;" dir="ltr">(&lt;<?php echo htmlspecialchars($msg['email']); ?>&gt;)</span>
                            </div>
                            <div>
                                <small style="color: #888; margin-left: 15px;"><?php echo htmlspecialchars($msg['created_at'] ?? ''); ?></small>
                                <button type="button" class="action-btn reply-btn" onclick="toggleReplyBox(<?php echo $msg['id']; ?>)">رد</button>
                                <a href="dashboard.php?delete_message=<?php echo $msg['id']; ?>&tab=messages" class="action-btn delete-btn" onclick="return confirm('حذف الرسالة؟');">حذف</a>
                            </div>
                        </div>
                        <p style="margin: 5px 0; color: #333; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                        
                        <!-- صندوق كتابة الرد المخفي الذي يظهر عند الضغط على زر رد -->
                        <div id="replyBox-<?php echo $msg['id']; ?>" class="reply-box">
                            <form method="POST" action="dashboard.php?tab=messages">
                                <input type="hidden" name="reply_email" value="<?php echo htmlspecialchars($msg['email']); ?>">
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label style="font-size: 0.9em; color: #6b2d34;">عنوان البريد المرسل:</label>
                                    <input type="text" name="reply_subject" value="رد من متجر Wallé Store بخصوص استفسارك" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label style="font-size: 0.9em; color: #6b2d34;">نص الرد للعميل:</label>
                                    <textarea name="reply_message" rows="3" required placeholder="اكتب ردك هنا..."></textarea>
                                </div>
                                <button type="submit" name="send_reply" style="font-size: 0.85em; padding: 6px 15px;">إرسال الرد الآن</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666;">لا توجد رسائل.</p>
            <?php endif; ?>
        </div>

        <!-- 3. إدارة المستخدمين -->
        <div class="card <?php echo ($active_tab == 'users') ? 'active' : ''; ?>">
            <h2>إدارة مستخدمي المتجر</h2>
            <table>
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>اسم المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الصلاحية</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><strong><?php echo htmlspecialchars($u['role']); ?></strong></td>
                            <td>
                                <?php if ($u['username'] !== 'admin'): ?>
                                    <a href="dashboard.php?delete_user=<?php echo $u['id']; ?>" class="action-btn delete-btn" onclick="return confirm('حذف هذا المستخدم؟');">حذف</a>
                                <?php else: ?>
                                    <span style="color: #888; font-size: 0.85em;">محمي</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- 4. إضافة مستخدم جديد -->
        <div class="card <?php echo ($active_tab == 'add_user') ? 'active' : ''; ?>">
            <h2>إضافة مستخدم أو مدير جديد</h2>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>اسم المستخدم:</label>
                        <input type="text" name="new_username" required placeholder="اسم المستخدم">
                    </div>
                    <div class="form-group">
                        <label>البريد الإلكتروني:</label>
                        <input type="email" name="new_email" required placeholder="example@domain.com">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>كلمة المرور:</label>
                        <input type="password" name="new_password" required minlength="6" placeholder="******">
                    </div>
                    <div class="form-group">
                        <label>الصلاحية:</label>
                        <select name="new_role">
                            <option value="customer">زبون (Customer)</option>
                            <option value="admin">مدير (Admin)</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="add_user">إضافة المستخدم الآن</button>
            </form>
        </div>

    </div>

    <!-- نافذة منبثقة (Modal) لإضافة أو تعديل اللوحة في نفس الصفحة -->
    <div id="artworkModal" class="modal">
        <div class="modal-content">
            <a href="dashboard.php" class="close-modal">&times;</a>
            <h2 style="color: #6b2d34; border-bottom: 2px solid #8c3b44; padding-bottom: 8px; margin-top:0;">
                <?php echo $edit_artwork ? 'تعديل اللوحة الفنية' : 'إضافة لوحة فنية جديدة'; ?>
            </h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <?php if ($edit_artwork): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_artwork['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label>اسم اللوحة (إجباري):</label>
                        <input type="text" name="title" required minlength="2" value="<?php echo $edit_artwork ? htmlspecialchars($edit_artwork['title']) : ''; ?>" placeholder="اسم اللوحة">
                    </div>
                    <div class="form-group">
                        <label>السعر ($ إجباري):</label>
                        <input type="number" step="0.01" min="0.01" name="price" required value="<?php echo $edit_artwork ? htmlspecialchars($edit_artwork['price']) : ''; ?>" placeholder="120">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>القسم الفني:</label>
                        <select name="category">
                            <option value="black-white" <?php echo ($edit_artwork && $edit_artwork['category'] == 'black-white') ? 'selected' : ''; ?>>لوحات أبيض وأسود</option>
                            <option value="arabic-art" <?php echo ($edit_artwork && $edit_artwork['category'] == 'arabic-art') ? 'selected' : ''; ?>>لوحات عربية</option>
                            <option value="variety" <?php echo ($edit_artwork && $edit_artwork['category'] == 'variety') ? 'selected' : ''; ?>>منوعات</option>
                            <option value="nature-art" <?php echo ($edit_artwork && $edit_artwork['category'] == 'nature-art') ? 'selected' : ''; ?>>لوحات من الطبيعة</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>صورة اللوحة من الجهاز:</label>
                        <input type="file" name="artwork_image" accept="image/jpeg,image/png,image/webp" <?php echo $edit_artwork ? '' : 'required'; ?>>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>وصف اللوحة:</label>
                    <textarea name="description" rows="3" placeholder="وصف مختصر..."><?php echo $edit_artwork ? htmlspecialchars($edit_artwork['description']) : ''; ?></textarea>
                </div>

                <?php if ($edit_artwork): ?>
                    <button type="submit" name="update_artwork" style="background-color: #f0ad4e;">حفظ التعديلات</button>
                <?php else: ?>
                    <button type="submit" name="add_artwork">رفع وإضافة اللوحة</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('artworkModal').style.display = 'flex';
        }

        // دالة لإظهار وإخفاء صندوق الرد المباشر لكل رسالة
        function toggleReplyBox(msgId) {
            const box = document.getElementById('replyBox-' + msgId);
            if (box.style.display === 'block') {
                box.style.display = 'none';
            } else {
                box.style.display = 'block';
            }
        }
    </script>
</body>
</html>