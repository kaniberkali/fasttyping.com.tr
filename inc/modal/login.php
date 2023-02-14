<div id = "login-container" class = "d-flex flex-column justify-content-between login-containerActive">
    <div id = "close-conteiner" class = "d-flex w-100 justify-content-end">
        <i id= "close-mark-login" class="fa-solid fa-xmark text-white me-2 mt-2" style = "cursor:pointer;" onclick='deleteItems();'></i>
    </div>
        <div id = "signForm" class = "d-flex w-50 justify-content-center flex-column">
            <h3 class = "m-auto mb-5">Giriş Yap</h3>
            <form class = "d-flex flex-column justify-content-center align-items-center">
                <div class = "content-container d-flex justify_content-between align-items-center">
                    <i class="fa-solid fa-user text-white"></i>
                    <input id = "login-username" class = "form-control rounded w-25" type="" onkeypress="Javascript: if (event.keyCode==13) login();" placeholder="Kullanıcı Adı">
                </div>
                <div class = "content-container d-flex justify_content-between align-items-center">
                    <i class="fa-solid fa-key text-white"></i>
                    <input id = "login-password" class = "form-control rounded w-25" type="password" onkeypress="Javascript: if (event.keyCode==13) login();" placeholder="Şifre">
                </div>
            </form>
            <button id = "login" class = "btn mb-2 w-75 m-auto mt-4" onclick="login()">Giriş</button>
        </div>
</div>