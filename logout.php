<?php
session_start(); // بدء الجلسة الحالية
session_unset(); // تفريغ جميع متغيرات الجلسة
session_destroy(); // تدمير الجلسة بالكامل

// إعادة توجيه المستخدم إلى الصفحة الرئيسية أو صفحة تسجيل الدخول
header("Location: index.php");
exit();
?>