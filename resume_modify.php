<?php
session_start();
if (intval($_SESSION['uid']) < 1) {
    header("Location:user_login.php");
    if (intval($_SESSION['uid']) < 1) die("请先<a href='user_login.php'>登录</a>再添加简历");
}
$id = intval($_REQUEST['id']);
if ($id < 1) die("错误的简历ID");
try {
    $dbh = new PDO('mysql:host=localhost;dbname=resumedatabase', 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM `resume` WHERE `id`=? LIMIT 1";

    $sth = $dbh->prepare($sql);
    $ret = $sth->execute([$id]);
    $resume = $sth->fetch(PDO::FETCH_ASSOC);
    if ($resume['uid'] != $_SESSION['uid']) die("只能修改自己的简历");
} catch (Exception $Exception) {
    die($Exception->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <script src="jquery-3.1.0.min.js"></script>
    <script src="main.js"></script>
    <title>修改简历</title>
</head>
<body>
<div class="container">
    <form action="resume_update.php" method="post" id="form_resume" onsubmit="send_form('form_resume');return false;">
        <div id="form_resume_notice" class="form_info full"></div>
        <p><input type="text" name="title" placeholder="简历名称" class="full" value="<?= $resume['title'] ?>"></p>
        <p><textarea name="content" id="" class="full"
                     placeholder="简历类容，支持 Markdown 语法"><?= htmlspecialchars($resume['content']) ?></textarea></p>
        <input type="hidden" name="id" value="<?= $resume['id'] ?>">
        <p><input type="submit" value="更新简历" class="middle-button"><input type="button"value="返回" class="middle-button cancel-button" onclick="history.back(1);void (0);"></p>
    </form>
</div>
</body>
</html>
