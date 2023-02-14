<?php
$title = "FastTyping";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/header.php";
?>
<body class="d-flex flex-column justify-content-between" onbeforeunload="pageExit()">
<div id="modal"></div>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/inc/nav.php";
?>
<div id="blurDiv"></div>

<div class = "d-flex justify-content-between contentMain">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/inc/top-users.php"; ?>
    <div id= "topList-con" class = ".topList-con con" >
        <i class="fa-solid fa-ranking-star"></i>
    </div>
	<div id = "showSection" class="contentSection container">
		<?php 
		if ($parameters[0] == "users" && $user && $user["approval"] == 1)
			include $_SERVER['DOCUMENT_ROOT'] . "/inc/pages/users.php";
		else if ($parameters[0] == "profile" && $user && $user["approval"] == 1)
			include $_SERVER['DOCUMENT_ROOT'] . "/inc/pages/profile.php";
        else if ($parameters[0] == "rooms" && $user && $user["approval"] == 1)
            include $_SERVER["DOCUMENT_ROOT"] . "/inc/pages/rooms.php";
        else if ($parameters[0] == "room" && $user && $user["approval"] == 1)
            include $_SERVER["DOCUMENT_ROOT"] . "/inc/pages/room.php";
		else if ($parameters[0] == "")
			include $_SERVER['DOCUMENT_ROOT'] . "/inc/pages/home.php";
        else if ($parameters[0] != "training" && $user && $user["approval"] == 1)
			include $_SERVER['DOCUMENT_ROOT'] . "/inc/pages/game.php";
		else
            include $_SERVER["DOCUMENT_ROOT"]. "/inc/pages/training.php";
		?>
	</div>
	<?php if ($user)
			include $_SERVER['DOCUMENT_ROOT'] . "/inc/friends.php"; ?>
    <div id = "con-div">
        <?php
        if ($user) {
         ?>
        <div id = "friends-con" class = "con">
            <i class="fa-duotone fa-user-group"></i>
        </div>
        <?php } else { ?>
        <div id ="login-con" class = "d-flex justify-content-center align-items-center con">
            <i class="fa-solid fa-user"></i>
        </div>
        <div id= "register-con" class = "d-flex justify-content-center align-items-center con">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <?php } ?>
    </div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/inc/footer.php"; ?>
