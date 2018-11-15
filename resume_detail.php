<?php

$id = intval($_REQUEST['id']);
if ($id < 1) die("错误的简历ID");

try {
//die("数据OK");
//链接数据库
    $dbh = new PDO('mysql:host=localhost;dbname=resumedatabase', 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM `resume` WHERE `id` = ? AND `is_deleted`!=1";
    $sth = $dbh->prepare($sql);
    $ret = $sth->execute([$id]);
    $resume = $sth->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $Exception) {
    die($Exception->getMessage());
}
include "lib/Parsedown.php";
$md = new Parsedown();
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
    <title><?= $resume['title'] ?></title>
</head>
<body>
<div class="container">
    <div class="content">
        <?= $md->text($resume['content']) ?>
    </div>
</div>
</body>
</html>
