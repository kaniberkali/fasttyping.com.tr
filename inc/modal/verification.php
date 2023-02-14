<?php

include $_SERVER['DOCUMENT_ROOT'] ."/lib/config.php";

if ((int)$_GET["code"] == $user["approval"] && (int)$_GET["code"] > 0)
{
	$key = randomUserKey();
	p2a("UPDATE users SET approval=1, user_key=".$key. " WHERE id=".$user["id"], $db);
	setcookie("key", $key, time()+365*24*60*60, "/" , "fasttyping.com.tr");
    header('Location: https://fasttyping.com.tr');
}
?>

<div id = "verificationCon" class = "d-flex align-items-center login-containerActive justify-content-center flex-column card">
    <div class = "d-flex w-100 justify-content-end">
        <i class="fa-solid fa-xmark text-white me-2 mt-2" style = "cursor:pointer;"  onclick='deleteItems();'></i>
    </div>
    <form class="d-flex flex-column justify-content-center align-items-center mb-3">
        <h6 class="text-white text-center" id="verification-username"><?php echo isset($user["username"]) ? $user["username"] : "username"; ?></h6>
        <h6 class="text-white mt-2 text-center" id="verification-mail"><?php echo isset($user["mail"]) ? $user["mail"] : "username@mail.com"; ?></h6>
        <h6 class="text-white mt-2 text-center">Fast Typing oyununa online erişmek istiyorsanız epostanızı onaylamanız gerekmektedir.</h6>
        <div class = "changePassDiv">
                <input id = "verify-code" class = "form-control rounded" placeholder = "Doğrulama Kodu">
                <a id = "resend-account" href = "javascript:resendEmail();" class = "text-white"><i class="fa-solid fa-arrow-rotate-right"></i></a>
        </div>
	</form>
    <button id = "verify-account" class = "btn mt-4"  onclick="verifyAccount();">Hesabımı Doğrula</button>
	<button id = "delete-account" class = "btn"  onclick="deleteAccount();">Hesabımı Sil</button>
</div>