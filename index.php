<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
$is_login = intval($_SESSION['uid']) < 1 ? false : true;

try {
//链接数据库
    $dbh = new PDO('mysql:host=localhost;dbname=resumedatabase', 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT `id`,`uid`,`title`,`created_at` FROM `resume` WHERE `is_deleted`!=1";
    $sth = $dbh->prepare($sql);
    $ret = $sth->execute([intval($_SESSION['uid'])]);
    $resume_list = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $exception) {
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
    <title>简历系统</title>
</head>
<body>
<div class="container">
    <?php include 'header.php'; ?>
    <h1>最新简历</h1>
    <?php if ($resume_list): ?>
    <ul class="resume_list">
        <?php foreach ($resume_list as $item): ?>
            <li id="rlist-<?= $item['id'] ?>">
                <span class="menu_square_large"></span>
                <a href="resume_detail.php?id=<?= $item['id'] ?>" target="_blank"
                   class="title middle"><?= $item['title'] ?></a>
                <a href="resume_detail.php?id=<?= $item['id'] ?>" target="_blank"><img src="image/open_in_new.png"
                                                                                       alt="查看"></a>
            </li>
        <?php endforeach; ?>
        <?php endif; ?>
</div>
</body>
</html>