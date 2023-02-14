<?php include $_SERVER['DOCUMENT_ROOT'] ."/lib/config.php"; ?>
<div id = "changePass" class = "card">
    <div class = "d-flex w-100 justify-content-end">
        <i class="fa-solid fa-xmark text-white me-2 mt-2" style = "cursor:pointer;"  onclick='changePasswordCancel();'></i>
    </div>
    <form>
        <h6 class="text-white text-center ps-4 pe-4" id="verification-username"><?php echo isset($user["username"]) ? $user["username"] : "username"; ?></h6>
        <h6 class="text-white mt-2 text-center ps-4 pe-4" id="verification-mail"><?php echo isset($user["mail"]) ? $user["mail"] : "username@mail.com"; ?></h6>
        <h6 class="text-white mt-2 text-center ps-5 pe-5">Şifrenizi değiştirmek istiyorsanız epostanıza gelen kodu girmeniz gerekmektedir.</h6>
		<div class = "d-flex flex-column justify-content-center align-items-center">
            <div class = "changePassDiv">
                <input id = "verify-code" class = "form-control rounded" maxlength="6" placeholder = "Doğrulama Kodu">
                <a href = "javascript:sendChangeCheck()" class = "text-white"><i class="fa-solid fa-arrow-rotate-right"></i></a>
            </div>
            <div class = "changePassDiv deactive">
                <input id = "new-password" class = "form-control rounded" placeholder = "Yeni Şifre" type = "password">
            </div>
            <div class = "changePassDiv deactive">
                <input id = "new-password-again" class = "form-control rounded" placeholder = "Yeni Şifre Tekrar" type = "password">
            </div>
        </div>
	</form>
    <div>
        <button id = "checkVerifyCode" class = "btn mt-5" onclick = "changeVerifyCheck();">Doğrula</button>
        <button id = "change-password" class = "btn mt-5 deactive" onclick="changePassword();">Şifre Değiştir</button>
        <button id = "change-password-cancel" class = "btn" onclick="changePasswordCancel();">İptal</button>
    </div>
</div>