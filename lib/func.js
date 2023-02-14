function alert(message, theme="warning", location=document.body, counter=3){
    if(document.querySelectorAll('.alert')[0]){
		location.removeChild(document.querySelectorAll('.alert')[0]);
	}
    var alert = document.createElement('div');
	alert.classList = `alert alert-${theme}`;
	alert.innerHTML = message;
    location.appendChild(alert);
    setTimeout(() => {
		if(document.querySelectorAll('.alert')[0]){
			location.removeChild(document.querySelectorAll('.alert')[0]);
		}
	},counter * 1000);
}
async function postWithFunction(action, callback, data=null, type="POST")
{
	await $.ajax({
		type:   type,
		url:    "posts.php?action="+action,
		data: data,
		async: true,
		success: function (request) {
			callback(request);
		},
		error: function (request, error) {
			if (request.responseText.length > 1)
				callback(request.responseText);
			else
			{
				alert("Error!");
				//window.location.reload();
			}
		}
	});
}

async function post(action, data=null, type="POST")
{
	var result = "false";
	await $.ajax({
		type:   type,
		url:    "posts.php?action="+action,
		data: data,
		async: true,
		success: function (request) {
			result = request;
		},
		error: function (request, error) {
			if (request.responseText.length > 1)
				result = request.responseText;
			else
			{
				alert("Error!");
				//window.location.reload();
			}
		}
	});
	return result;
}
function deleteSpan(e) {
	if(gameStyle == "Statik")
	{
		if (document.getElementById("textArea") != null) {
			document.getElementById("textArea").removeChild(e);
			if (document.querySelectorAll("#textArea span")[0])
				document.querySelectorAll("#textArea span")[0].classList = "first-span rounded";
		}
	}
	else{
		let i = $(".first-span").index();
		if (document.querySelectorAll("#textArea span")[i] != null)
        {
            document.querySelectorAll("#textArea span")[i].classList = " ";
            i++;
            document.querySelectorAll("#textArea span")[i].classList = "first-span";
            $(".first-span")[0].scrollIntoView({block: 'center', inline: 'center'});
            $("#textArea")[0].scrollIntoView({block: 'center', inline: 'center'});
        }
	}
}

function searchInput(data) {
    let regex = new RegExp(`^${document.getElementById("textInput").value}`, "gi");
    if(data.innerHTML.match(regex)){
        document.querySelectorAll('.first-span')[0].classList = "first-span rounded";
    }else{
        document.querySelectorAll('.first-span')[0].classList = "bg-danger text-white rounded first-span";
    }
}

function addingButtons(){
    document.getElementById('stopBtn').style.display = "block";
    document.getElementById('restartBtn').style.display = "block";
}

function removeButtons(){
    document.getElementById('stopBtn').style.display = "none";
    document.getElementById('restartBtn').style.display = "none";
}

function addingCounterArea() {
    counterArea.classList = "d-flex align-items-center justify-content-between";
    document.getElementById('counter').style.display = "block"
    document.getElementById('falseWcounter').style.display = "block";
    document.getElementById('trueWcounter').style.display = "block";
}

function removeCounterArea() {
    document.getElementById('counter').style.display = "none"
    document.getElementById('falseWcounter').style.display = "none";
    document.getElementById('trueWcounter').style.display = "none";
}
async function f_minute() {
    if(f_minute_setTime)
        clearTimeout(f_minute_setTime);
	f_minute_setTime =  setTimeout(async function() {
	   await postWithFunction("time", function(response){
		if (response >= 0 && response != false)
		{
            if(response >= 60){
                response -= 60;
                textInput.disabled = true;
                counter.innerHTML = response;
            }
            else{
				restartBtn.style.display = "block";
                textArea.classList = "card";
                textInput.disabled = false;
                textInput.focus();
                counter.innerHTML = response;
                if(response == 10)
                    counter.classList = "bg-danger text-white rounded text-center";   
            }
			f_minute();
		}
		else if (response <= 0)
		{
            textArea.classList = "card disable";
            textInput.disabled = true;
			textArea.innerHTML = '';
			$("#modal").load("inc/modal/score.php", function(responseTxt, statusTxt, xhr){
				if (statusTxt == "success")
					getScoreResult();
			});
			blurDiv.classList = "blurDivActive";
			modal.classList = "active-modal";
			startBtn.style.display = "block";
			document.getElementById("config").style.display = "block";
            removeButtons();
            removeCounterArea();
			document.getElementById('trueWcounter').innerHTML = 0;
			document.getElementById('falseWcounter').innerHTML = 0;
			counter.innerHTML = 60;
			counter.classList = "bg-success text-white rounded text-center";
            startBtn.innerHTML = `<i class="fa-solid fa-play"></i>`;
			removeSettingsIcon(document.getElementById("drpthema").parentElement.parentElement, "block");
		}
	   });
   }, 1000);
}

function deleteItems()
{
    if($("#modal #roomMatch").length <= 0)
    {
        [].slice.call(document.getElementById("modal").children).forEach(element => { $(element).remove(); });
        blurDiv.classList = " ";
        modal.classList = " ";
    }
}

async function login()
{
	await postWithFunction("login",function(response){
		if (response == true)
			window.location.reload();
		else
			alert("Giriş yapılamadı.");
	}, 
	{
		"username": $("input[id='login-username']").val(),
		"password": $("input[id='login-password']").val()
	});
}

async function register()
{
    var username = $("input[id='register-username']").val();
    var password = $("input[id='register-password']").val();
    var passwordAgain = $("input[id='register-password-again']").val();
    var email = $("input[id='register-email']").val();
    if(!(username.length >= 5 && username.length <= 100) || !/^[a-z]+$/.test(username))
        alert("Kullanıcı Adı 5 karakterden büyük, 100 karakterden küçük ve küçük harflerden oluşmalı.");
    else if(!(password.length >= 5 && password.length <= 100))
        alert("Şifre 5 karakterden büyük 100 karakterden küçük olmalı.");
    else if(passwordAgain != password)
        alert("Girdiğinz şifreler uyuşmamaktadır.");
    else if(!(email.length >= 5 && email.length <= 100) || !/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(email))
        alert("Eposta adresi veya kullanıcı adı daha önceden alınmış.");
    else{
        await postWithFunction("register", function(response){
            if (response == false)
                alert("Hesap oluşturulken bir hatayla karşılaşıldı.");
            else if (response == true)
                window.location.reload();
            else
                alert(response);	
        }, 
        {
            "username": $("input[id='register-username']").val(),
            "password": $("input[id='register-password']").val(),
            "passwordAgain": $("input[id='register-password-again']").val(),
            "mail": $("input[id='register-email']").val()
        });
    }
}

async function verifyAccount()
{
	await post("verify-account", {code: $("input[id='verify-code']").val() });
	window.location.reload();
}

async function changeVerifyCheck()
{
	var response = await post("check-change-verify", {code: $("input[id='verify-code']").val()});
	if (response == true)
	{
		document.getElementById("checkVerifyCode").classList = "btn mt-5 deactive";
		document.getElementById("change-password").classList = "btn mt-5";
		document.querySelectorAll('.changePassDiv')[0].classList = "changePassDiv deactive";
		document.querySelectorAll('.changePassDiv')[1].classList = "changePassDiv";
		document.querySelectorAll('.changePassDiv')[2].classList = "changePassDiv";
	}
	else
		alert("Doğrulama kodu yalnış.");
}

async function openChangeModal()
{
	$("#modal").load("inc/modal/change-password.php", async function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
		await sendChangeCheck();
	});
}

async function sendChangeCheck()
{
	const passModal = document.getElementById("changePass");
	var response = await post("send-change-password-verify");
	if (response == true)
        alert("Doğrulama kodu gönderildi", "success", passModal);
	else
        alert(`${response} saniye sonra tekrar deneyin.`)
}

async function changePassword()
{
	var response = await post("change-password", {code: $("input[id='verify-code']").val(), password: $("input[id='new-password']").val(),  passwordAgain: $("input[id='new-password-again']").val() });
	if (response == false)
		alert("Şifre değiştirilemedi.");
	else
	{
		alert("Şifre başarıyla değiştirildi.");
		window.location.reload();
	}
}

async function changePasswordCancel()
{
	await post("change-password-cancel");
	window.location.reload();
}

async function resendEmail()
{
	const verification = document.getElementById("verificationCon");
	var response = 	await post("resend-email");
	if(document.querySelectorAll('.alert')[0]){
		verification.removeChild(document.querySelectorAll('.alert')[0]);
	}
	if (response == true)
	{
		var alert = document.createElement('div');
		alert.classList = "alert alert-success";
		alert.innerHTML = "	Doğrulama kodu gönderildi.";
		verification.appendChild(alert);
	}
	else if (response == false)
	{
		var alert = document.createElement('div');
		alert.classList = "alert alert-danger";
		alert.innerHTML = "	Doğrulama kodu gönderilemedi.";
		verification.appendChild(alert);
	}
	else
	{
		var alert = document.createElement('div');
		alert.classList = "alert alert-warning";
		alert.innerHTML = `${response} saniye sonra tekrar deneyin.`;
		verification.appendChild(alert);
	}
	setTimeout(() => {
		if(document.querySelectorAll('.alert')[0]){
			verification.removeChild(document.querySelectorAll('.alert')[0]);
		}
	},3000);
}

async function deleteAccount()
{
	await post("delete-account");
	window.location.reload();
}

async function logout()
{
	await post("logout");
	window.location.reload();
}

async function addFriend(event)
{
	await post("add-friend", { "friend_id": event });
	window.location.reload();
}

async function removeFriend(event)
{
	await post("remove-friend", { "friend_id": event });
	window.location.reload();
}

async function acceptFriend(event)
{
	await post("accept-friend", { "friend_id": event });
	window.location.reload();
}

function showNotification() {
	notificationDrop.classList.toggle("showItem");
}

function showSettings() {
	$("#modal").load("inc/modal/settings.php?type=all", function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
        getSettings();
	});
}

function showTrainingSettings(){
	$("#modal").load("inc/modal/settings.php?type=training", function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
        getSettings();
	});
}

function showGeneralSettings(){
	$("#modal").load("inc/modal/settings.php?type=general", function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
        getSettings();
	});
}

function showThemas() {
	$("#themaDrop")[0].classList.toggle("showItem");
}

function showMenu() {
	const moveResponsive = document.getElementById("moveResponsive");
    const responsiveContainer = document.getElementById("responsiveContainer");
	if(moveResponsive.innerHTML.length > 0){
		responsiveContainer.innerHTML = moveResponsive.innerHTML;
		moveResponsive.innerHTML = "";
	}
	else if(moveResponsive.innerHTML == 0){
		moveResponsive.innerHTML = responsiveContainer.innerHTML;
		responsiveContainer.innerHTML = "";
	}
}

function isChangeHtml(html1, html2)
{
	return (html1.replace(/[\r\n\t ]/g, "").toString() != html2.replace(/[\r\n\t ]/g, "").toString());
}

async function getFriends()
{
	if ($("#friends-table").length > 0)
	{
		await postWithFunction("get-friends", function(friends){
			var html = "";
			if(friends.length == 0 || friends == false)
			{
				html = `<div class = "friends-empty">
					<p class = "friends-empty-message text">Şu anda hiç arkadaşın yok.</p>
				</div>`;
				if (isChangeHtml($("#friends-table").html(), html))
					$("#friends-table").html(html);
			}
			else
			{
				friends.forEach(friend => {
					html += `
					<tr class="score-top">
						<th class="text-center text">${friend.level}</th>
						<td class="text-center text"><a href="/profile/${friend.username}" class="text" >${friend.username}</a></td>
						<td class="text-center text"><i class="${friend.is_online == 1 ? "fa-solid fa-signal-bars" : "fa-duotone fa-signal-slash" }"></i></td>
					</tr>`;
				});
				if (isChangeHtml($("#friends-list-body").html(), html))
					$("#friends-list-body").html(html);
			}
		});
	}
}

async function getNotifications()
{
	if ($("#notificationDrop").length > 0)
	{
		var nullController = false;
		await postWithFunction("get-friends-approval", function(friends){
			var html = "";
			if(friends.length == 0 || friends == false)
				nullController = true;
			else
			{
				friends.forEach(friend => {
					html += `<li class = "dropdown-item">
					<div class="d-flex align-items-center justify-content-center w-100">
						<h6 class = "pe-5">${friend.username}</h6>
						<button class="btn btn-success me-2" onclick="acceptFriend(${friend.id});">Kabul Et</button>
						<button class="btn btn-primary" onclick="removeFriend(${friend.id});">Reddet</button>
					</div>
				</li>`;
				});
				if (isChangeHtml($("#notificationDrop").html(),html))
				{
                    if ($("#notificationDrop li").length > 0)
                    {
                        $("#new-alert").html(`<i id="drpbtn" onclick="showNotification()" class="fa-solid fa-bell dropbtn text"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                        </span>`);
                        alert("Arkadaşlık isteği aldınız. Zil tuşuna tıklayarak oyunu kabul edebilir veya reddedebilirsiniz.","success");
                    }
                    else
                        $("#new-alert").html("");
					$("#notificationDrop").html(html);
				}
                else
                    $("#new-alert").html("");
			}
		});
		await postWithFunction("get-invites", function(invites){
			var html = "";
			if(invites.length == 0 || invites == false && nullController)
			{
				html = `<li class = "dropdown-deactive-item">
				<p class = "dropdown-message">Burası şu anlık sessiz.</p>
				<p class = "dropdown-message">¯\\_(ツ)_/¯</p>
				</li>`;
				if (isChangeHtml($("#notificationDrop").html(), html))
					$("#notificationDrop").html(html);
			}
			else
			{
				invites.forEach(invite => {
					html += `<li class = "dropdown-item">
					<div class="d-flex align-items-center justify-content-center w-100">
						<h6 class = "pe-5">${invite.username} seni bir oyuna davet ediyor.</h6>
						<button class="btn btn-success me-2" onclick="acceptGame(${invite.code}, '${invite.password}', ${invite.room_id});">Kabul Et</button>
						<button class="btn btn-primary" onclick="denyGame(${invite.room_id});">Reddet</button>
					</div>
				</li>`;
				});
				if (isChangeHtml($("#notificationDrop").html(),html))
				{
                    if ($("#notificationDrop li").length > 0)
                    {
                        $("#new-alert").html(`<i id="drpbtn" onclick="showNotification()" class="fa-solid fa-bell dropbtn text"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                        </span>`);
                        alert("Bir oyuna davet edildiniz. Zil tuşuna tıklayarak oyunu kabul edebilir veya reddedebilirsiniz.","success");
                    }
                    else
                        $("#new-alert").html("");
					$("#notificationDrop").html(html);
				}
                else
                    $("#new-alert").html("");
			}
		});
	}
}

async function acceptGame(code, password, room_id)
{
	await post("room-password-checker", {id: parseInt(code), password:password});
    await post("accept-game", {room_id: room_id});
    if (password == "")
        password = "undefined";
	window.location.href = `/room/${code}/${password}`;
}

async function denyGame(id)
{
	await post("deny-game", {"room_id": id});
    getNotifications();
}

async function invites(){
    $("#modal").load("inc/modal/invite.php", function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
	});
    var html = "";
    var data = await post("get-friends-onlines");
	if(data.length > 0){
		data.forEach((user) => {
			html += `<div class="text usersPlay d-flex justify-content-between align-items-center p-3">
			<h6>${user.username}</h6>
			<button class = "btn btn-success" onclick = "setInvite(${user.id});">Davet Gönder</button>
		</div>`
		})
	}
	else{
		html = `<div class = 'd-flex justify-content-center align-items-center w-100'>
        <p class = "dropdown-message text">Burası şu anlık boş</p>
   	 </div>`;
	}
    $("#inviteList").html(html);

}

async function setInvite(id)
{
	var response = await post("set-invite", {"invite_id" : id});
    if(response == false)
        alert("Davet Gönderilemedi");
    else
	{
		deleteItems();
		alert("Davet Gönderildi","success");
	}
}

async function getUsers()
{
	if ($('#users-table').length > 0)
	{
		await postWithFunction("get-users", async function(users){
			var html = "";
			users.forEach(user => {
				html += `
				<tr>
					<th scope="row" class="text">${user.id}</th>
					<td class="text">${user.level}</td>
					<td class="text"><a href="/profile/${user.username}" class="text" >${user.username}</a></td>
					<td class="text">${user.total_score}</td>
					<td class="text">${user.maximum_score}</td>
				`;
				html += '<td >';
				if (user.is_friend == 1 && user.approval == 1)
					html += `<button class = "btn bg-danger text-white" onclick="removeFriend(${user.id});">Sil</button>`;
				else if (user.is_friend == 1)
					html += `<button class = "btn bg-warning text-white" onclick="removeFriend(${user.id});">Onay Bekleniyor</button>`;
				else if (user.is_friend == 0)
					html += `<button class = "btn bg-success" onclick="addFriend(${user.id});">Ekle</button>`;
				else
					html += `<a href="/profile"><button class = "btn bg-success">Profil</button></a>`;
				html += '</td></tr>';
			});
			if (isChangeHtml($("#users-list-body").html(), html))
			{
				$('#users-table').DataTable().destroy();
				$("#users-list-body").html(html);
				$('#users-table').DataTable({"language": { "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Turkish.json"},"responsive": true, "lengthChange": false, "autoWidth": false});
			}
			else if (html == "")
				$('#users-table').DataTable({"language": { "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Turkish.json"},"responsive": true, "lengthChange": false, "autoWidth": false});
		});
	}
}

async function getUserStatistics(userName)
{
	if ($("#myChart").length)
	{
	await postWithFunction("get-user-statistics", async function(datas){
		var xValues = datas.map(item => {
			const date = new Date(item.end_time);
			const year = date.getFullYear().toString().slice(2);
			const month = date.getMonth() + 1;
			const day = date.getDate();
			const hours = date.getHours();
			const minutes = date.getMinutes();
			return `${year}-${month.toString().padStart(2, "0")}-${day.toString().padStart(2, "0")} ${hours.toString().padStart(2, "0")}:${minutes.toString().padStart(2, "0")}`;
		  });
		new Chart("myChart", {
		type: "line",
		data: {
			labels: xValues,
			datasets: [{ 
			data: datas.map(item => item.score),
			borderColor: window.getComputedStyle(document.getElementById("nav")).getPropertyValue("background-color"),
			fill: false
			}
		]
		},
		options: {
			responsive: true,
			legend: {display: false},
			scales: {
				yAxes: [{
					ticks: {
						fontColor: 'white'
					},
				}],
				xAxes: [{
					display: false
				}]
				}
			}
		});
		var html = "";
		datas.forEach(data => {
			html += `
			<tr>
			<th class="text-center">${data.end_time}</th>
			<th class="text-center">${data.score}</th>
			<th class="text-center">${data.true_word}</th>
			<th class="text-center">${data.false_word}</th>
			<th class="text-center">${data.true_letter}</th>
			<th class="text-center">${data.false_letter}</th>
			</tr>`;
		});
		$("#lastGames-tBody").html(html);
		$('#lastGamesTable').DataTable({"language": { "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Turkish.json"},"responsive": true, "lengthChange": false, "autoWidth": false});
		new Chart(document.getElementById("totalWordAnalysis"), {
			type: 'doughnut', 
			data: {
				datasets: [{
				backgroundColor: ["#5cb85c", "#d9534f"],
				borderColor: ["transparent", "transparent"],
				data: [datas.reduce((acc, item) => acc + parseInt(item.true_word), 0),datas.reduce((acc, item) => acc + parseInt(item.false_word), 0)]
				}],
				labels: ['Doğru Kelime', 'Yanlış Kelime']
			},
			options: {
				title: {
				display: true,
				text: 'Kelime Analizi',
				fontColor: "white"
				},
				legend: {
				display: false
				},
				tooltips: {
				callbacks: {
					title: function(tooltipItem, data) {
					return data.labels[tooltipItem[0].index];
					},
					label: function(tooltipItem, data) {
					return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					}
				}
				}
			}
		});
		new Chart(document.getElementById("totalLetterAnalysis"), {
			type: 'doughnut',
			data: {
				datasets: [{
				backgroundColor: ["#5cb85c", "#d9534f"],
				borderColor: ["transparent", "transparent"],
				data: [datas.reduce((acc, item) => acc + parseInt(item.true_letter), 0),datas.reduce((acc, item) => acc + parseInt(item.false_letter), 0)]
				}],
				labels: ['Doğru Harf', 'Yanlış Harf']
			},
			options: {
				title: {
				display: true,
				text: 'Harf Analizi',
				fontColor : "white"
				},
				legend: {
				display: false
				},
				tooltips: {
				callbacks: {
					title: function(tooltipItem, data) {
					return data.labels[tooltipItem[0].index];
					},
					label: function(tooltipItem, data) {
					return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					}
				}
				}
			}
		});
		$("#highScore")[0].textContent = datas.sort(function(b,a) { return parseInt(a.score) - parseInt(b.score); })[0].score;
	}, {username : userName});
	postWithFunction("get-user-details", async function(data) {
        var time = (await post("get-current-time")).split(" ")[0];
        var lastTime = data.last_time.split(" ");
        if(lastTime[0] == time){
            $("#lastTime")[0].textContent = lastTime[1];
        }
        else{
            $("#lastTime")[0].textContent = lastTime[0];
        }
        $("#levelRank")[0].textContent = data.level_rank;
        $("#scoreRank")[0].textContent = data.score_rank;
		$("h1[id=profile-level]")[0].textContent = "Seviye " + data.level;
		$("div[id=profile-progress]").css("--value", data.percent);
		$("h1[id=profile-username]")[0].textContent = userName;
	}, {username : userName});
	}
}

async function getTopList()
{
	if ($("#toplist-table").length > 0)
	{
        await postWithFunction("top-list", function(response){
			var html = "";
            if(response.length == 0 || response == false)
            {
				html = `<div class = "toplist-empty">
					<p class = "toplist-empty-message text p-5">Bugün hiç kimse oyun oynamadı</p>
				</div>`;
				if (isChangeHtml($("#topList").html(), html))
                	$("#topList").html(html);
            }
            else
            {
				response.forEach((item, index)=>{
						html += `
						<tr class=" score-top">
						<th class="text-center">${index + 1}</th>
						<th class="text-center">${item.level}</th>
						<td class="text-center"><a href="/profile/${item.username}" class="text" >${item.username}</a></td>
						<td class="text-center">${item.score}</td>
						<td class="text-center"><i class="${item.is_online == 1 ? "fa-solid fa-signal-bars" : "fa-duotone fa-signal-slash" }"></i></td>
						</tr>`;
					});
			if (isChangeHtml($("#toplist-table").html(), html))
				$("#toplist-table").html(html);
            }
        });
	}
}

async function getScoreResult()
{
	if ($("div[id=scoreCard]").length > 0)
	{
		await postWithFunction("score-result", async function(response){
			new Chart(document.getElementById("wordAnalysis"), {
				type: 'doughnut',
				data: {
					datasets: [{
					backgroundColor: ["#5cb85c", "#d9534f"],
					borderColor: ["transparent", "transparent"],
					data: [response.true_word, response.false_word]
					}],
					labels: ['Doğru Kelime', 'Yanlış Kelime']
				},
				options: {
					title: {
					display: true,
					text: 'Kelime Alanizi'
					},
					legend: {
					display: false
					},
					tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
						return data.labels[tooltipItem[0].index];
						},
						label: function(tooltipItem, data) {
						return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						}
					}
					}
				}
			});
			new Chart(document.getElementById("letterAnalysis"), {
				type: 'doughnut',
				data: {
					datasets: [{
					backgroundColor: ["#5cb85c", "#d9534f"],
					borderColor: ["transparent", "transparent"],
					data: [response.true_letter, response.false_letter]
					}],
					labels: ['Doğru Harf', 'Yanlış Harf']
				},
				options: {
					title: {
					display: true,
					text: 'Harf Analizi'
					},
					legend: {
					display: false
					},
					tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
						return data.labels[tooltipItem[0].index];
						},
						label: function(tooltipItem, data) {
						return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						}
					}
					}
				}
			});
			await postWithFunction("get-level", async function(data) {
				$("h1[id=levelText]")[0].textContent = "Seviye " + data.level;
				$("div[id=level-progress]").css("--value", data.percent);
			});
			document.getElementById("score").innerHTML = response.score;
		});
		await post("set-level");
		getTopList();
	}
}
async function getRooms(){
	if ($('#roomsTable').length > 0)
	{
		await postWithFunction("get-rooms", async function(rooms){
			var html = "";
			rooms.forEach(room => {
				html += `
				<tr>
					<th scope="row" class="text">${room.id}</th>
					<th scope="row" class="text"><a href="/profile/${room.username}" class="text">${room.username}</a></th>
					<th scope="row" class="text">${room.name}</th>
					<td class="text">${room.current_size}/${room.size}</a></td>
					<td class="text">${room.create_time}</td>
					<td class = "roomsTableLastColumn">
				`;
				if (room.is_lock == "1")
					html += `<input type = "password" class="rommPass" id="${room.code}">`;
				html += `</i><button class = "btn btn-join" onclick="roomJoin('${room.code}');" >Join</button></td></td></tr>`;
				$("#rooms-list-body").append(html);
			});
			if (isChangeHtml($("#rooms-list-body").html(), html))
			{
                if (html != "")
                {
                    $('#roomsTable').DataTable().destroy();
                    $("#rooms-list-body").html(html);
                    $('#roomsTable').DataTable({"language": { "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Turkish.json"},"responsive": true, "lengthChange": false, "autoWidth": false});
                }
			}
			else if (html == "")
				$('#roomsTable').DataTable({"language": { "url": "https://cdn.datatables.net/plug-ins/1.10.22/i18n/Turkish.json"},"responsive": true, "lengthChange": false, "autoWidth": false});
		});
	}
}

async function setTheme(id)
{
	await post("set-theme", {id:id});
	window.location.reload();
}

async function gameStart()
{
    textInput.disabled = false;
	falseWord.textContent = "0";
    trueWord.textContent = "0";
    let areaCon = '';
    await postWithFunction("offline-start", function(response){
		response.forEach(e => { areaCon += `<span id = "${e.id}">${e.word}</span>`; });
		textArea.innerHTML = areaCon;
	}, {"count" : 150 });
    $("#textArea span").css({"font-size":gameFontSize+"px"});
    addingButtons();
    addingCounterArea();
    inputText.value = "";
    f_minute();
    document.querySelectorAll('#textArea span')[0].classList = "first-span";
    startBtn.style.display = "none";
	restartBtn.style.display = "none";
	removeSettingsIcon(document.getElementById("drpthema").parentElement.parentElement, "none");
	document.getElementById("config").style.display = "none";
    $("#textArea")[0].scrollIntoView({block: 'center', inline: 'center' });
}

async function gameStop()
{
	await post("offline-stop");
	window.location.reload();
}

async function gameRestart()
{
	textArea.classList = "card disable";
	textInput.disabled = true;
	textArea.innerHTML = '';
	await post("offline-stop");
	gameStart();
}

async function createRoom()
{
	if ($("input[id='roomName']").val().length > 2)
	{
		var result = await post("create-room", {name: $("input[id='roomName']").val(), size:$("select[id='roomSize']").val(), password:$("input[id='roomPass']").val()});
		if (result != false)
			window.location.href = "/room/" + result + "/" + $("input[id='roomPass']").val();
	}
	else
		alert('Odanın ismi en az 3 karakter olmalı.');
}

async function roomJoin(code){
	var password = $(`input[id='${code}']`).val();
	var response = await post("room-password-checker", {id: parseInt(code), password:password});
	if (response == true)
		window.location.href = "/room/" + code + "/"+ password;
    else
		alert("Odaya bağlanılamadı. Girmiş olduğunuz şifre hatalı veya oda dolu.");
}


async function getRoomUsers()
{
	if ($('#beforeGamesUsers').length > 0)
	{
		await postWithFunction("room-get-users", async function(users){
			var admin = users.find(e => e.is_you == '1' && e.is_admin == '1');
			var thisAdmin = users.find(e => e.is_admin == '1');
            if (!thisAdmin)
                window.location.reload();
			var html = "";
			html = `<h6 class = "level rounded-circle">${thisAdmin.level}</h6>
			<h4>${thisAdmin.username}</h4>
			<h4>${thisAdmin.max_score}</h4>`;
			if (isChangeHtml(html,$("#room-admin-card").html()))
				$("#room-admin-card").html(html);
            html = `<p class = "text text-center">${thisAdmin.username} oyunu başlatması bekleniyor.</p><div class = "loader"></div><div class = "d-flex flex-column justify-content-evenly align-items-center mt-3">
            <button class = "btn btn-success w-100" onclick="invites()">Davet Et</button>
        </div>`;
			if (admin)
			{
				html = `
				<p class = "text text-center">Diğer kullanıcılar oyunu başlatmanızı bekliyor.</p>
                <div class = "d-flex flex-column justify-content-evenly align-items-center mt-3">
                        <button class = "btn btn-success w-100" onclick="invites()">Davet Et</button>
                        <button id = "startAdmin" class = "w-100 mt-1" onclick="roomGameStart()">StartGame</button>
                    </div>
				`;
				if (isChangeHtml($("#loadMessage").html(), html))
					$("#loadMessage").html(html);
				html = "";
				await postWithFunction("room-get-ban-users", function(users){
					users.forEach(user => {
						html += `<div class = "card">
									<div class = "smProfile text banned-user">
										<h6 class = "level rounded-circle">${user.level}</h6>
										<h4>${user.username}</h4>
										<h4>${user.max_score}</h4>
										<div class = "p-auto removeBan">
											<i class="fa-solid fa-rotate-left" onclick="unbanUser(${user.id})"></i>
										</div>
									</div>
								</div>`;
					});
				});
				users.filter(e => e.is_you == "0" && e.is_admin != 1).forEach(user =>
				{
					html += `<div class = "card">
						<div class = "smProfile text">
							<h6 class = "level rounded-circle">${user.level}</h6>
							<h4>${user.username}</h4>
							<h4>${user.max_score}</h4>
							<button type="button" class="btn-close" aria-label="Close" onclick="banUser(${user.id})"></button>
						</div>
					</div>`;
				});
			}
			else
			{
                if (isChangeHtml($("#loadMessage").html(), html))
                    $("#loadMessage").html(html);
                    html = "";
				users.filter(e => e.is_admin == "0").forEach(user =>
				{
					html += `
					<div class = "card">
						<div class = "smProfile text">
							<h6 class = "level rounded-circle">${user.level}</h6>
							<h4>${user.username}</h4>
							<h4>${user.max_score}</h4>
						</div>
					</div>`;
				});
			}
			if (isChangeHtml($("#beforeGamesUsers").html(), html))
				$("#beforeGamesUsers").html(html);
		});
	}
}

async function getRoomMessages()
{
	if ($('#beforeGamesUsers').length > 0)
	{
		var messages = await post("room-get-messages");
		if (messages != false)
		{
			var html = "";
			messages.forEach(message =>
			{
				html += message.is_you == "1" ? `<span class="my-message message text">${message.message}</span>` : `<span class="message text">${message.username} : ${message.message}</span>`;
			});
			if (isChangeHtml($("#chatRoom .card").html(), html))
				$("#chatRoom .card").html(html);
		}
	}
}

async function sendMessage()
{
	if ($('#beforeGamesUsers').length > 0)
	{
		if(document.getElementById("room-chat").value.replace(/^\s+|\s+$/g, "").length > 0)
		{
			const chatContainer = document.querySelector("#chatRoom .card");
			await post("room-send-message", {message: $("input[id=room-chat]").val()});
			await getRoomMessages();
			chatContainer.scrollTop += chatContainer.scrollHeight + 10;
		}
		document.getElementById("room-chat").value = "";
	}
}

async function banUser(id)
{
	if ($('#beforeGamesUsers').length > 0)
	{
		await post("ban-player", {id: id});
		getRoomUsers();
	}
}

async function unbanUser(id)
{
	if ($('#beforeGamesUsers').length > 0)
	{
		await post("unban-player", {id: id});
		getRoomUsers();
	}
}

async function checkBan()
{
	if ($('#beforeGamesUsers').length > 0)
	{
		var response = await post("check-ban");
		if (response == true)
			window.location.href = "/rooms";
	}
}

async function roomGameChanger()
{
    if ($("#game").length > 0)
    {
        var response = await post("room-is-start", {id:pages[1]});
        if (response == false)
        {
            if ($("#beforeGame").length <= 0)
            {
                var html = `<div id = "beforeGame" class = "parent">
                        <div id = "hostSection" class = "div1">
                            <div id = "gameHost">
                                <div class = "card rounded">
                                        <div class = "smProfile text" id="room-admin-card">
                                        </div>
                                        <div id = "loadMessage">
                                            <div class = "loader"></div>
                                            <div class = "d-flex justify-content-evenly align-items-center mt-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id = "chatRoom" class = "div2">
                            <div class = "card">
                            </div>
                            <div class = "d-flex">
                                <input type="text" class = "w-100 mt-1" placeholder = "Chat..." id="room-chat" onkeypress="Javascript: if (event.keyCode==13) sendMessage();">
                                <i class="fa-solid fa-paper-plane-top text m-auto ps-1" onclick = "sendMessage();"></i>
                            </div>
                        </div>
                        <div id = "beforeGamesUsers" class = "div3">
                        </div>
                    </div>`;
                $("#game").html(html);
            }
        }
        else if (response > 60 && $('#isGame').length <= 0)
        {
			removeSettingsIcon(document.getElementById("drpthema").parentElement.parentElement, "none");
            var html = `<div id = "isGame">
                <div class = "d-flex flex-column align-items-center justify-content-center w-100">
                    <div id="counterArea" class="d-flex align-items-center justify-content-center w-100">
                        <div id="falseWcounter" class="counterElements bg-danger text-white rounded text-center m-2">0</div>
                            <div id="counter" class="text-white rounded text-center card">
                                5
                            </div>
                        <div id="trueWcounter" class="counterElements bg-warning text-white rounded text-center m-2">0</div>
                    </div>
                    <div class="mt-3 w-50 d-flex flex-column justify-content-center align-item-center">
                        <div id="textArea" class = "card disable">`;
            await postWithFunction("current-game-words", function(response){
                response.forEach(e => { html += `<span>${e}</span>`; });
            }, {"count" : 150 });
            html += `</div><input class="form-control mt-4 card text" id="textInput" type="text" onkeyup='checkWord(event);'></div></div><div id="chart" class="w-50 m-auto"></div></div>`;
            $("#game").html(html);
            $("#textArea span")[0].classList = "first-span";
            $("#textArea span").css({"font-size":gameFontSize+"px"});
            await roomsStartChart();
            await roomGameController();
        }
        else if (response > 0 && response <= 60  && $('#isGame').length <= 0)
        {
            var html = `
            <div class="watchGame" id="watchGame">
                <div class="watchGameDiv1">
                    <div class = "chart" id="chart">
            
                    </div>
                </div>
                <div class="watchGameDiv2">
                    <div class = "card">
                        <div class="text usersPlay d-flex justify-content-between align-items-center p-3">
                            <h5 class="watchGameUsers">Seviye</h5>
                            <h5 class="watchGameUsers">Kullanıcı Adı</h5>
                            <h5 class="watchGameUsers">Doğru Kelime</h5>
                            <h5 class="watchGameUsers">Yanlış Kelime</h5>
                            <h5 class="watchGameUsers">Doğru Harf</h5>
                            <h5 class="watchGameUsers">Yanlış Harf</h5>
                            <h5 class="watchGameUsers">Skor</h5>
                        </div>
                        <div class = "card watchGameList" id="watchGameList">
                        </div>
                    </div>
                </div>
            </div>`;
            if ($('#watchGame').length <= 0)
            {
                $("#game").html(html);
                await roomsStartChart();
            }
            await watchGameController();
        }
        else if (response <= 0 && $("#watchGame").length <= 0)
        {
			removeSettingsIcon(document.getElementById("drpthema").parentElement.parentElement, "block");
            var response = await post("room-is-admin");
            if (response == true)
            {
                html = `<div class = " w-100 d-flex justify-content-between align-items-center mb-3" id="watchEnd">
                <button class = "btn section text" onclick="exitRoom();">Odayı Kapat</button>
                <button class = "btn section text" onclick="nextRoom();">Odayı Tekrar Oluştur</button>
                </div>`;
            }
            else
            {
                html = `<div class=" w-100 d-flex justify-content-between align-items-center mb-3" id="watchEnd">
                        <button class="btn section text" onclick="exitRoom();">Odadan Çık</button>
                        <div class="d-flex align-items-center text-white">${response.username} odayı tekrar kurması bekleniyor...<div class="ms-2 loader"></div></div>
                    </div>`;
            }
            html += `<div>
            <div>
            <div class="watchGame" id="watchGame">
                <div class="watchGameDiv1">
                    <div class="chart" id="chart">
                    </div>
                </div>
                <div class="watchGameDiv2">
                    <div class = "card">
                        <div class="text usersPlay d-flex justify-content-between align-items-center p-3">
                            <h5 class="watchGameUsers">Seviye</h5>
                            <h5 class="watchGameUsers">Kullanıcı Adı</h5>
                            <h5 class="watchGameUsers">Doğru Kelime</h5>
                            <h5 class="watchGameUsers">Yanlış Kelime</h5>
                            <h5 class="watchGameUsers">Doğru Harf</h5>
                            <h5 class="watchGameUsers">Yanlış Harf</h5>
                            <h5 class="watchGameUsers">Skor</h5>
                        </div>
                        <div class = "card watchGameList" id="watchGameList">
                        </div>
                    </div>
                </div>
            </div>`;
            $("#game").html(html);
            await roomsStartChart();
            await watchGameController();
        }
    }
}

function exitRoom()
{
	window.location.href = "/rooms";
}

function nextRoom()
{
	post("room-next-start");
}

async function watchGameController()
{
	setTimeout(async function() {
        postWithFunction("room-get-users-scores", function(users){
            var html = "";
            var data = [];
            users.sort(function(b, a) {
                return parseInt(a.score) - parseInt(b.score);
            });
            users.forEach(user =>{
                if (parseInt(user.score) > 0)
                { 
                    if (chart.w.config.series.find(s => s.name == user.username))
                        data.push({name:user.username, data: [...chart.w.config.series.find(s => s.name == user.username).data, parseInt(user.score)]});
                    else
                        data.push({name:user.username, data: [parseInt(user.score)]});
                }
                html += `<div class="text d-flex justify-content-between align-items-center p-3">
                    <h6 class="watchGameUsers">${user.level}</h6>
                    <h5 class = "watchGameUsers">${user.username}</h5>
                    <h6 class = "watchGameUsers">${user.true_word}</h6>
                    <h6 class = "watchGameUsers">${user.false_word}</h6>
                    <h6 class = "watchGameUsers">${user.true_letter}</h6>
                    <h6 class = "watchGameUsers">${user.false_letter}</h6>
                    <h6 class = "watchGameUsers">${user.score}</h6>
                </div>`;
            });
            chart.updateSeries(data);
            if (isChangeHtml($("#watchGameList").html(), html))
                $("#watchGameList").html(html);
        });
	},1000);
}

async function roomGameStart()
{
    if ($("#beforeGamesUsers .card").length > 0)
	    await post("room-start");
    else
        alert("Oyunu başlatabilmeniz için 1 kişiye daha ihtiyacınız var.");
}

async function roomsStartChart()
{
	var options = {
		series: [],
		chart: {
		    id: 'realtime',
            height: 350,
            type: 'line',
            animations: {
                enabled: true,
                easing: 'linear',
                dynamicAnimation: {
                    speed: 1000
                }
		    },
		toolbar: {
		  show: false
		},
		zoom: {
		  enabled: false
		}
	  },
	  dataLabels: {
		enabled: false
	  },
	  stroke: {
		curve: 'smooth'
	  },
	  grid: {
		row: {
		  colors: ['transparent', 'transparent']
		  },
	  },
	  legend:{
		  labels: {
		  colors:"white"
		  }
	  },
	  xaxis: {
		  labels: {
			  show: false
		  }
	  },
	  yaxis: {
		  labels: {
			  style: {
			  colors: 'white'
			  }
		  }
	  }
	  };
	  await postWithFunction("room-get-users-scores", function(users){
        users.forEach(user =>{
            options.series.push({name:user.username, data:[0]});
        });
	});
	  chart = new ApexCharts(document.querySelector("#chart"), options);
	  chart.render();
}

async function roomGameController() {
	setTimeout(async function() {
	   await postWithFunction("room-time", function(response){
		if (response >= 0 && response != false)
		{
            if(response >= 60){
                response -= 60;
                textInput.disabled = true;
                $("div[id=counter]")[0].innerHTML = response;
            }
            else{
                $("div[id=textArea]")[0].classList = "card";
                textInput.disabled = false;
                textInput.focus();
                $("div[id=counter]")[0].innerHTML = response;
                if(response == 10)
					$("div[id=counter]")[0].classList = "bg-danger text-white rounded text-center";
                postWithFunction("room-get-users-scores", function(users){
					var data = [];
					users.forEach(user =>{
                        if (parseInt(user.score) > 0)
                        { 
                            if (chart.w.config.series.find(s => s.name == user.username))
                                data.push({name:user.username, data: [...chart.w.config.series.find(s => s.name == user.username).data, parseInt(user.score)]});
                            else
                                data.push({name:user.username, data: [parseInt(user.score)]});
                        }
					});
					chart.updateSeries(data);
				});
            }
			roomGameController();
		}
		else
		{
			post("score-result");
			post("set-level");
		}
	   });
   }, 1000);
}
async function checkWord(e)
{
    let firstSpan = document.querySelectorAll('.first-span')[0];
    var text = $("input[id='textInput']")[0].value;
	if (text.trim().length)
	{
		if(text != firstSpan.innerHTML && text != firstSpan.innerHTML + " ")
			searchInput(firstSpan);
		if (e.keyCode == 32 || e.keyCode == 13 || e.keyCode == 16 || e.keyCode == 9)
		{
			if (gameTrace == "Olsun")
			{
				if (text.split(String.fromCharCode(e.keyCode))[0] == firstSpan.innerHTML)
					$("#textArea span")[$(".first-span").index()].style.color = 'green';
				else
					$("#textArea span")[$(".first-span").index()].style.color = 'red';
			}
			$("input[id='textInput']")[0].value = text.split(String.fromCharCode(e.keyCode))[1] != undefined ? text.split(String.fromCharCode(e.keyCode))[1] : "";
			deleteSpan(firstSpan);
			postWithFunction("score", function(response){
				document.getElementById('falseWcounter').innerHTML = response.false_word;
				document.getElementById('trueWcounter').innerHTML = response.true_word;
			}, { "input": text.split(String.fromCharCode(e.keyCode))[0].toLowerCase() });
		}
	}
	else
		$("input[id='textInput']")[0].value = "";
}

async function roomMatch(){
	$("#modal").load("inc/modal/room-match.php", async function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
	});
    roomsMatchController();
}

async function roomsMatchController()
{
    setTimeout( async function() {
    var res = await post("rooms-match");
        if(parseInt(res) > 0){
            if($("#roomMatchCounter").length > 0)
                $("#roomMatchCounter")[0].textContent = `${res} `;
            else
            {
                $("#roomMatch").html(`<div class = "loader"></div>
                <h4>Oyun aranıyor...</h4>
                <h6 style = "opacity: .5;"><span id = "roomMatchCounter"></span> kişi oyun arıyor</h6>
                <button class = "btn btn-danger mt-3" onclick = "cancelMatch();">Karşılaşmayı İptal Et</button>`);
            }
            roomsMatchController();
        }
        else
        {
            $("#roomMatch").html(    `        <div class = "loader mb-3"></div>
            <h4>Oyun bulundu...</h4>
            <h6 class = "mt-3" style = "opacity: .5;">Yönlendiriliyorsunuz ...</h6>`);
            window.location.href = `/room/${res.key}`;
        }
    }, 1000);
}

async function cancelMatch()
{
    window.location.href = "/rooms";
}

function configButton(){
    $("#modal").load("inc/modal/configuration.php", function(){
		blurDiv.classList = "blurDivActive";
		modal.classList = "active-modal";
	});
}

function setSettings(){
    if ($("#gameTime").length > 0)
        gameTime = $("#gameTime")[0].value;
    if ($("#gameDifficulty").length > 0)
        gameDifficulty = $("#gameDifficulty")[0].value;
    if ($("#gameWordCount").length > 0)
        gameWordCount = $("#gameWordCount")[0].value;
    if ($("#gameFontSize").length > 0)
        gameFontSize = $("#gameFontSize")[0].value;
    if ($("#gameStyle").length > 0)
        gameStyle = $("#gameStyle")[0].value;
    if ($("#gameTrace").length > 0)
        gameTrace = $("#gameTrace")[0].value;
    if ($("#gameErrorCatch").length > 0)
        gameErrorCatch = $("#gameErrorCatch")[0].value;
    localStorage.setItem("gameTime", gameTime ?? "60");
    localStorage.setItem("gameDifficulty", gameDifficulty ?? "Orta");
    localStorage.setItem("gameWordCount", gameWordCount ?? "150");
    localStorage.setItem("gameFontSize", gameFontSize ?? "32");
    localStorage.setItem("gameStyle", gameStyle ?? "Dinamik");
    localStorage.setItem("gameTrace", gameTrace ?? "Olsun");
    localStorage.setItem("gameErrorCatch", gameErrorCatch ?? "Olmasın");
    deleteItems();
    window.location.reload();
}

function getSettings()
{
    if ($("#gameTime").length > 0)
        $("#gameTime")[0].value = gameTime;
    if ($("#gameDifficulty").length > 0)
        $("#gameDifficulty")[0].value = gameDifficulty;
    if ($("#gameWordCount").length > 0)
    $("#gameWordCount")[0].value = gameWordCount;
    if ($("#gameFontSize").length > 0)
        $("#gameFontSize")[0].value = gameFontSize;
    if ($("#gameStyle").length > 0)
        $("#gameStyle")[0].value = gameStyle;
    if ($("#gameTrace").length > 0)
        $("#gameTrace")[0].value = gameTrace;
    if ($("#gameErrorCatch").length > 0)
        $("#gameErrorCatch")[0].value = gameErrorCatch;
}

function pageExit()
{
    if (!window.location.href.includes("rooms"))
        post("remove-temp");
}

function removeSettingsIcon(element, property){
	element.classList.toggle("d-flex");
	element.style.display = property;
}

function focusMode(){
    nav.style.opacity = "0.1";
}