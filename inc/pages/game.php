<div class = "d-flex flex-column align-items-center justify-content-center w-100">
    <div class  = "d-flex justify-content-center align-items-center">
        <button id="startBtn" class="start-btn" onclick="gameStart();"><i class="fa-solid fa-play"></i></button>
        <button id="stopBtn" class="start-btn" onclick="gameStop();" style = "display: none"><i class="fa-solid fa-pause"></i></button>
        <button id="restartBtn" class="start-btn" onclick="gameRestart();" style = "display: none"><i class="fa-solid fa-rotate-left"></i></button>
        <div id = "config" onclick = "showGeneralSettings();">Özelliştir</div>
    </div>
	<div id="counterArea" class="d-flex align-items-center justify-content-center w-100">
		<div id="falseWcounter" class="counterElements bg-danger text-white rounded text-center m-2" style="display: none;">0</div>
		<div id="counter" class="text-white rounded text-center card" style = "display : none;">
			5
		</div>
		<div id="trueWcounter" class="counterElements bg-warning text-white rounded text-center m-2" style="display: none;">0</div>
	</div>

	<div class="mt-3 w-50 d-flex flex-column justify-content-center align-item-center">
		<div id="textArea" class = "card disable"></div>
		<input class="form-control mt-4 card text" id="textInput" type="text">
	</div>
</div>
