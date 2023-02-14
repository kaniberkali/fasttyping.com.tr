<nav id="nav" class="container-fluid section">
    <div class=" container d-flex flex-column">
        <div class = "d-flex justify-content-between align-item-center">
            <div>
            <a href = "/"><h2 class="display-5 text mt-2">
                 <img class = "mt-3" src="./files/<?php echo ($user ? $user["theme"] : "hufflepuff");?>-logo.png" alt="" style = "width: 200px;height:100px">
            </a>
            </div>
            <div id = "topnav" class="topnav d-flex align-items-center w-50">
                <ul id = "nav-list" class ="nav-item m-auto" >
                    <div id = "moveResponsive">
                        <?php
							if (!$user)
							{
								if ($parameters[0] != "training")
									echo '<li class = "nav-item"><a class = "text" href="/training">Egzersiz</a></li>';
							}
                            if ($user && $user["approval"] == 1)
                            {
                                if ($parameters[0] != "game")
                                    echo '<li class = "nav-item"><a class = "text" href="/game">Oyun</a></li>';
                                if ($parameters[0] != "training")
                                    echo '<li class = "nav-item"><a class = "text" href="/training">Egzersiz</a></li>';
                                if ($parameters[0] != "rooms")
                                    echo '<li class = "nav-item"><a class = "text" href="/rooms">Odalar</a></li>';
                                if ($parameters[0] != "users")
                                    echo '<li class = "nav-item"><a class = "text" href="/users">Kullanıcılar</a></li>';
                                if ($parameters[0] != "profile" || $parameters[2])
                                    echo '<li class = "nav-item"><a class = "text" href="/profile">Profil</a></li>';
                            ?>
                    </div>
                        <li class="dropdown d-flex justify-content-center align-items-center nav-con nav-item">
                            <div id="new-alert">
                                    <i id="drpbtn" onclick = "showNotification()" class="fa-solid fa-bell dropbtn text"></i>
                            </div>
                            <ul id = "notificationDrop" class = "dropdown-content">
                                <li class = "dropdown-deactive-item">
                                    <p class = "dropdown-message">Burası şu anlık sessiz.</p>
                                    <p class = "dropdown-message">¯\_(ツ)_/¯</p>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown d-flex justify-content-center align-items-center nav-con nav-item">
                            <div id = "settings-con">
                                <i id = "drpthema" onclick = "showSettings()" class="fa fa-gear dropthema text" ></i>
                            </div>
                        </li>
                        <li class = "me-1" style = "cursor : pointer;">
                            <a onclick="logout();"><i class="fa-solid fa-right-from-bracket text"></i></a>
                        </li>
                        <li class = "ms-1">
                            <a href="javascript:void(0);" id="menuIcon" class="icon" onclick="showMenu()"><i class="fa fa-bars"></i></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class ="border-b pt-2 mb-0" theme = "primary"></div>
    <div id = "responsiveContainer">
    </div>
</nav>
