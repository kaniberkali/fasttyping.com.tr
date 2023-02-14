<div class = "d-flex justify-content-between align-items-center flex-column contentMain">
	<div>
		<button class = "btn section text" onclick = "roomMatch()">Hızlı Oyun Bul</button>
	</div>
	<div id = "roomsContainer" class="contentSection container card text">
	<div id = "createRooms" class = "w-100 d-flex justify-content-evenly m-1 flex-wrap">
		<div>
			<label for="roomName">Oda İsmi</label>
			<input id = "roomName" type="text" required>
		</div>
		<div>
			<label for="roomSize">Oda Boyutu </label>
			<select name="roomSize" id="roomSize">
			<?php
				for($i = 2; $i <= 100; $i++)
                {
                    if ($i == 20)
				        echo '<option value="'. $i . '" selected>'.$i.'</option>';
                    else
                        echo '<option value="'. $i . '">'.$i.'</option>';
                }
			?>
			</select>
		</div>
		<div>
			<label for="roomPass" class = "disabled">Oda Şifresi</label>
			<input id = "roomPass" type="password" placeholder = "Zorunlu değildir">
		</div>
		<button class = "btn btn-join" onclick="createRoom();">Oluştur</button>
	</div>
	<hr>
	<table id="roomsTable" class="display">
		<thead>
			<tr>
				<th>ID</th>
				<th>Admin</th>
				<th>İsim</th>
				<th>Boyut</th>
				<th>Oluşturulma Tarihi</th>
				<th>Etkileşim</th>
			</tr>
		</thead>
		<tbody id="rooms-list-body">

		</tbody>
	</table>
	</div>
</div>