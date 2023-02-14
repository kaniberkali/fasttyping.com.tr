if (registerCon && registerCon)
{
	registerCon.addEventListener('click', function() {
		$("#modal").load("inc/modal/register.php");
		modal.classList = "active-modal";
		blurDiv.classList = "blurDivActive";
	});
	loginCon.addEventListener('click', function() {
		$("#modal").load("inc/modal/login.php");
		modal.classList = "active-modal";
		blurDiv.classList = "blurDivActive";
	});
}

document.body.addEventListener('click', function(e) {
    if (e.target == modal)
        deleteItems();
});

$(document).ready(async function() {
	var userData = await post("get-user");
	if (userData["approval"] != 1 && userData != false)
	{
		$("#modal").load("inc/modal/verification.php");
		modal.classList = "active-modal";
		blurDiv.classList = "blurDivActive";
	}
	else
	{
		post("set-last-time");
		getFriends();
		getNotifications();
		getUsers();
		getTopList();
		setInterval(async function() {
            if(new Date().getTime() > (lastActivity + 150000)) {
				if($("#modal #afkModal").length <= 0)
				{
					$("#modal").load("inc/modal/afk.php");
					modal.classList = "active-modal";
					blurDiv.classList = "blurDivActive";
				}
            }
            else
            {
                await post("set-last-time");
                getTopList();
                getFriends();
                getNotifications();
                getRooms();
            }
		}, 10000);
        if (!window.location.href.includes("/room/"))
        {
			post("remove-temp");
        }
		else
		{
			var response = await post("room-lock-control", {id: pages[1]});
			if (response == true)
			{            
                if ((await post("room-password-checker", {id: pages[1], password: pages[2]})) == false)
                {
                    var password = prompt('Enter password to view content',' ');
                    await postWithFunction("room-password-checker", function(response) {
                        if (response == false)
                            window.location.href = "/rooms";
                    }, {id: pages[1], password: password});
                }
			}
			else if (response == "refresh")
				window.location.href = "/rooms";
            else
                await post("room-password-checker", {id: pages[1]});
            getRoomUsers();
			getRoomMessages();
		}
		setInterval(async function() {
            if(new Date().getTime() <= (lastActivity + 150000)) {
                if ($('#beforeGamesUsers').length > 0)
                {
                    checkBan();
                    getRoomUsers();
                    getRoomMessages();
                }
                if ($('#isGame').length > 0 || $('#watchGame').length > 0 || $('#watchEnd').length > 0 || $("beforeGame").length <= 0)
                {
                    roomGameChanger();
                }
            }
		}, 1000);
        getUserStatistics(pages[1]);
		getRooms();
		if(document.querySelector("#chatRoom .card")){
			const chatContainer = document.querySelector("#chatRoom .card");
			chatContainer.scrollTop = chatContainer.scrollHeight;
		}
	}
	window.addEventListener('resize', function(event) {
		const moveResponsive = document.getElementById("moveResponsive");
		const responsiveContainer = document.getElementById("responsiveContainer");
		if (window.innerWidth > 850)
		{
			if(moveResponsive.innerHTML == 0){
				moveResponsive.innerHTML = responsiveContainer.innerHTML;
				responsiveContainer.innerHTML = "";
			}
		}
	}, true);
});

window.onclick = function (e) {
	if (!e.target.matches('.dropbtn')) {
		const dropDown = document.getElementById("notificationDrop");
        if(dropDown != null){
            if( dropDown.classList.contains('showItem')){
                dropDown.classList.remove('showItem');
            }
        }
	}
    if (!e.target.matches('.dropthema')) {
		const dropDown = document.getElementById("settingsDrop");
        if(dropDown != null){
            if( dropDown.classList.contains('showItem')){
                dropDown.classList.remove('showItem');
            }
        }
	}
    if (!e.target.matches('.themaList')) {
		const dropDown = document.getElementById("themaDrop");
        if(dropDown != null){
            if( dropDown.classList.contains('showItem')){
                dropDown.classList.remove('showItem');
            }
        }
	}
}


if (topListCon && topList)
{
	topListCon.addEventListener('click' , function() {
		topListCon.style.zIndex = "-99";
		topListCon.style.display = "none";
		topList.style.left = "0";
        topList.style.zIndex = "99";
	});

	topList.addEventListener('click' , function() {
		topListCon.style.zIndex = "99";
		topListCon.style.display = "flex";
		topList.style.left = "100%";
	});
}

if (friendsCon)
{
	friendsCon.addEventListener('click' , function() {
		friendsCon.style.right = "300rem";
		friendsTable.style.right = "0";
	});

	friendsTable.addEventListener('click' , function() {
		friendsCon.style.right = "0";
		friendsTable.style.right = "100%";
	});
}

if (inputText)
inputText.addEventListener('keyup', async function(e) {
    checkWord(e);
});

let lastActivity = new Date().getTime();
document.onmousemove = function() {
    lastActivity = new Date().getTime();
	if($("#modal #afkModal").length > 0){
		deleteItems();
	}
}
document.onkeypress = function() {
    lastActivity = new Date().getTime();
	if($("#modal #afkModal").length > 0){
		deleteItems();
	}
}
