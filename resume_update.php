<?php
session_start();
if (intval($_SESSION['uid']) < 1) {
    header("Location:user_login.php");
    if (intval($_SESSION['uid']) < 1) die("请先<a href='user_login.php'>登录</a>再添加简历");
}

error_reporting(E_ALL & ~E_NOTICE);
//获取参数
$id = intval($_REQUEST['id']);
$title = trim($_REQUEST['title']);
$content = trim($_REQUEST['content']);

//参数检查
if (strlen($id) < 1) die("简历ID不能为空");
if (strlen($title) < 1) die("简历名称不能为空");
if (mb_strlen($content) < 10) die("简历不能短于10个字符");

try {
//die("数据OK");
//链接数据库
    $dbh = new PDO('mysql:host=localhost;dbname=resumedatabase', 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE `resume` SET `title`=?,`content`=? WHERE `id`=? AND `uid`=? LIMIT 1";

    $sth = $dbh->prepare($sql);
    $ret = $sth->execute([$title, $content, $id, intval($_SESSION['uid'])]);
    die("简历更新成功<script>location='resume_list.php'</script>");

}catch( PDOException $Exception )
{
    $errorInfo = $sth->errorInfo();
    if( $errorInfo[1] == 1062 )
    {
        die($Exception->getMessage() . "简历名称已存在");
    }
    else
    {
        die( $Exception->getMessage() );
    }
}
catch( Exception $Exception )
{
    die( $Exception->getMessage() );
}