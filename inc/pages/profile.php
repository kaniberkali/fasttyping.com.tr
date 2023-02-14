<div id = "profile-Card" class = "row card">
    <div class = "d-flex flex-column justify-content-center align-items-center text">
        <h1 id="profile-username"></h1>
        <h2 id = "lastTime" class = "mt-4" style = "letter-spacing: 2px;"></h2>
    </div>
    <div id = "level-Card" class = "col-12">
        <div class = "d-flex justify-content-between">
            <div class = "w-50 p-0 h-100" style = "position : relative;">
                <canvas id="totalLetterAnalysis" width="350"></canvas>
            </div>
            <div class = "d-flex flex-column justify-content-center align-items-center">
            <div role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="--value:0" id="profile-progress"></div>
            <h1 class="text-white" id="profile-level">Seviye</h1>
        </div>
            <div class = "w-50 p-0 h-100" style = "position : relative;">
                <canvas id="totalWordAnalysis" width="350"></canvas>
            </div>
        </div>
    </div>
	<div class="w-100 d-flex text" style="justify-content: space-around;">
		<div>
			<h2 style="font-size : 1.2rem">Seviye Sırası <span id="levelRank"></span></h2>
		</div>
        <div>
            <h2 style="font-size : 1.2rem">En Yüksek Skor <span id="highScore"></span></h2>
        </div>
		<div>
			<h2 style="font-size : 1.2rem">Skor Sırası <span id="scoreRank"></span></h2>
		</div>
	</div>
    <div id = "analyst-field" class = "col-12">
        <canvas id="myChart" class ="p-5"></canvas>
    </div>
    <div id = "last-Games" class = "col-12 section text">
        <table id="lastGamesTable" class="display data-table text">
            <thead id = "lastGames-tHead">
                <tr class = "text">
                    <th>Tarih</th>
					<th>Skor</th>
                    <th>Doğru Kelime</th>
                    <th>Yanlış Kelime</th>
                    <th>Doğru Harf</th>
                    <th>Yanlış Harf</th>
                </tr>
            </thead>
            <tbody id="lastGames-tBody" class = "text">
            </tbody>
        </table>
    </div>
</div>