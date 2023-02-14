<?php
include $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
$currentKey = (int)$_COOKIE["key"];
if ($currentKey > 0)
{
	$newKey = randomUserKey();
	p2a("UPDATE users SET user_key=" . $newKey . " WHERE user_key=" . $currentKey, $db);
	setcookie("key", strval($newKey), time() + 365 * 24 * 60 * 60, "/", "fasttyping.com.tr");
}
header('Access-Control-Allow-Origin: *');
$parameters = explode("/", $_GET["parameters"]);
if (!isset($_COOKIE["AliKaniberk"]) || !isset($_COOKIE["MuhammedEminAyar"])) {
    setcookie("AliKaniberk", "Backend", time() + 365 * 24 * 60 * 60, "/", "fasttyping.com.tr");
    setcookie("MuhammedEminAyar", "Frontend", time() + 365 * 24 * 60 * 60, "/", "fasttyping.com.tr");
}
$words = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-5XLM59F388');
</script>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Fasttyping.com.tr, kullanıcıların klavye hızını test etmelerine ve antreman yapmalarına olanak sağlayan bir online platformdur. Kullanıcılar, kendi ayarlarını seçerek doğru ve yanlış harf ve kelime sayılarını görüntüleyebilirler. Ayrıca, hesap oluşturarak en çok skor yapanlar listesine girme şansı elde edebilir ve tüm oyunlarının verilerini profile sayfasından inceleyebilirler. Sitede, diğer kullanıcılarla arkadaşlık kurma ve oda oluşturarak birden fazla kişiyle yarışma imkanı da bulunmaktadır.">
<meta name="keywords" content="klavye hız testi, online klavye antremanı, puanlama skor, klavye hızı artırma, arkadaşlık, oda oluşturma, yarışma">
<meta name="author" content="Ali Kanıberk, Muhammed Emin Ayar">
<meta name="copyright" content="Ali Kanıberk, Muhammed Emin Ayar">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="revisit-after" content="1 hour">
<meta name="content-language" content="tr, en">
<meta name="resource-type" content="software">
<meta name="distribution" content="global">
<meta name="rating" content="10-70">
<?php 
    echo '<meta property="og:image" content="https://fasttyping.com.tr/files/'. ($user ? $user["theme"] : "hufflepuff").'-computer.png">';
?>
<meta property="og:image:alt" content="FastTyping Icon">
<title>Klavye Hızını Artır - FastTyping</title>
<base href="https://fasttyping.com.tr/"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="lib/css/all.css">
<?php
echo '<link rel="shortcut icon" id="logo" href="files/'. ($user ? $user["theme"] : "hufflepuff").'.png" type="image/x-icon">';
echo '<link rel="stylesheet" href="lib/css/themes/'.($user ? $user["theme"] : "hufflepuff").'.css">';
?>
<link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.3.1/css/rowReorder.dataTables.min.css">
</head>