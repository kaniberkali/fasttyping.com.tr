<div id = "register-container" class = "d-flex align-items-center login-containerActive justify-content-center flex-column">
    <div class = "d-flex w-100 justify-content-end">
        <i class="fa-solid fa-xmark text-white me-2 mt-2" style = "cursor:pointer;"  onclick='deleteItems();'></i>
    </div>
    <form class = "d-flex flex-column justify-content-center align-items-center">
        <h3 class = "m-auto mb-5 text-white">Kayıt Ol</h3>
        <div class = "content-container d-flex justify_content-between align-items-center mt-3">
            <input id = "register-username" class = "form-control rounded" placeholder = "Kullanıcı Adı" onkeypress="Javascript: if (event.keyCode==13) register();">
        </div>
        <div class = "content-container d-flex justify_content-between align-items-center mt-3">
            <input id = "register-password" class = "form-control rounded" type="password" placeholder = "Şifre" onkeypress="Javascript: if (event.keyCode==13) register();">
        </div>
        <div class = "content-container d-flex justify_content-between align-items-center mt-3">
            <input id = "register-password-again" class = "form-control rounded" type="password" placeholder = "Şifre tekrar" onkeypress="Javascript: if (event.keyCode==13) register();">
        </div>
        <div class = "content-container d-flex justify_content-between align-items-center mt-3">
            <input id="register-email" class = "form-control rounded" type="email" placeholder = "Email" onkeypress="Javascript: if (event.keyCode==13) register();">
        </div>
    </form>
    <button id = "register" class = "btn" onclick="register()">Kayıt Ol</button>
</div>