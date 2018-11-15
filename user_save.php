<?php
error_reporting(E_ALL & ~E_NOTICE);
//获取参数
$email = trim($_REQUEST['email']);
$password = trim($_REQUEST['password']);
$password2 = trim($_REQUEST['password2']);

//参数检查
if (strlen($email) < 1) die("Email 地址不能为空");
if (mb_strlen($password) < 6 || mb_strlen($password) > 12) die("密码不符合6～12位");
if (strlen($password2) < 1) die("重复密码不能为空");
if ($password != $password2) die("两次密码不一致");
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email 地址错误");
}
try {
//die("数据OK");
//链接数据库
    $dbh = new PDO('mysql:host=localhost;dbname=resumedatabase', 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `user`(`email`,`password`,`created_at`) VALUES (?,?,?)";
    $sth = $dbh->prepare($sql);
    $ret = $sth->execute([$email, password_hash($password, PASSWORD_DEFAULT), date("Y-m-d H:i:s")]);
    die("注册成功<script>location='user_login.php'</script>");

} catch (PDOException $Exception) {
    print_r($sth->errorInfo());
    $errorInfo=$sth->errorInfo();
    if ($errorInfo()[1] == 1062) {
        die("Email地址已被注册");
    } else {
        die($Exception->getMessage());
    }
}