<div id = "settingsModal" class = "card">
    <?php if ($_GET["type"] == "all" || $_GET["type"] == "training") { ?>
    <div class  = "settingsGroup">
        <h3 class = "w-100 text-center text">Egzersiz</h3>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameTime" class = "text">Oyun Süresi</label>
            <select name = "gameTime" id = "gameTime" class = "text">
                <?php for ($i=1;$i<=10;$i++)
                {
                    echo '<option value="'.($i*30).'">'.($i*30).'</option>';
                } ?>
            </select>
        </div>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameDifficulty" class = "text">Oyun Kolaylığı</label>
            <select name = "gameDifficulty" id = "gameDifficulty" class  = "text">
                <option value="Kolay">Kolay</option>
                <option value="Orta">Orta</option>
                <option value="Zor">Zor</option>
            </select>
        </div>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameWordCount" class = "text">Kelime sayısı</label>
            <select name = "gameWordCount" id = "gameWordCount" class = "text">
                <?php for ($i=1;$i<=10;$i++) {
                    echo '<option value="'.($i*50).'">'.($i * 50).'</option>';
                } ?>
            </select>
        </div>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameErrorCatch" class = "text">Hata Yakalama</label>
            <select name = "gameErrorCatch" id = "gameErrorCatch" class = "text">
                <option value="Olsun">Olsun</option>
                <option value="Olmasın">Olmasın</option>
            </select>
        </div>
    </div>
    <?php } ?>
    <div class = "settingsGroup">
        <h3 class = "w-100 text-center text">Genel</h3>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameFontSize" class = "text">Yazı Boyutu</label>
            <select name = "gameFontSize" id = "gameFontSize" class = "text">
                <?php for ($i=10;$i<=100;$i++)
                {
                    echo '<option value="'.$i.'">'.$i.'</option>';
                } ?>
            </select>
        </div>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameStyle" class = "text">Oyun Stili</label>
            <select name = "gameStyle" id = "gameStyle" class = "text">
                <option value="Statik">Statik</option>
                <option value="Dinamik">Dinamik</option>
            </select>
        </div>
        <div class = "d-flex justify-content-evenly align-items-center p-3">
            <label for="gameTrace" class = "text">İz</label>
            <select name = "gameTrace" id = "gameTrace" class = "text">
                <option value="Olsun">Olsun</option>
                <option value="Olmasın">Olmasın</option>
            </select>
        </div>
    <?php if ($_GET["type"] == "all") { ?>
    <div id = "themaChanger" onclick = "showThemas()" class = "dropdown-item themaList btn btn-white w-100">Tema Değiştir</div>
    <ul id = "themaDrop" class = "dropdown-content" style = "top:auto;right:auto;">
        <li class="dropdown-item gryffindorThemaBtn" onclick = "<?php echo $user["theme"] != "gryffindor" ? "setTheme(1)" : "javascrit:void(0);"?>" ><?php echo $user["theme"] == "gryffindor" ? "> " : ""?>Gryffindor Thema</li>
        <li class="dropdown-item ravenclawThemaBtn" onclick = "<?php echo $user["theme"] != "ravenclaw" ? "setTheme(2)" : "javascrit:void(0);"?>" ><?php echo $user["theme"] == "ravenclaw" ? "> " : ""?>Ravenclaw Thema</li>
        <li class="dropdown-item slytherinThemaBtn" onclick = "<?php echo $user["theme"] != "slytherin" ? "setTheme(3)" : "javascrit:void(0);"?>" ><?php echo $user["theme"] == "slytherin" ? "> " : ""?>Slytherin Thema</li>
        <li class="dropdown-item hufflepuffThemaBtn" onclick = "<?php echo $user["theme"] != "hufflepuff" ? "setTheme(4)" : "javascrit:void(0);"?>" ><?php echo $user["theme"] == "hufflepuff" ? "> " : ""?>Hufflepuff Thema</li>
        <li class="dropdown-item dementorThemaBtn" onclick = "<?php echo $user["theme"] != "dementor" ? "setTheme(5)" : "javascrit:void(0);"?>" ><?php echo $user["theme"] == "dementor" ? "> " : ""?>Dementor Thema</li>
    </ul>
    <div class = "dropdown-item btn btn-white" onclick="openChangeModal()">Şifre Değiştir</div>
    <?php } ?>
    </div>
    <div class = "d-flex justify-content-evenly align-items-center w-25">
        <div class = "btn btn-success mt-4" onclick = "setSettings();">Kaydet</div>
        <div class = "btn btn-danger mt-4" onclick = "deleteItems();">İptal</div>
    </div>
</div>