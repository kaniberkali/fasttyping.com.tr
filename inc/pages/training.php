<div class = "d-flex flex-column align-items-center justify-content-center w-100">

    <div class  = "d-flex justify-content-center align-items-center">
        <button id="startBtn" class="start-btn" aria-label="Game Start Button" onclick="trainingStart();"><i class="fa-solid fa-play"></i></button>
        <button id="stopBtn" class="start-btn" onclick="trainingStop();" style = "display: none"><i class="fa-solid fa-pause"></i></button>
        <button id="restartBtn" class="start-btn" onclick="trainingRestart();" style = "display: none"><i class="fa-solid fa-rotate-left"></i></button>
        <div id = "config" onclick = "showTrainingSettings();">Özelliştir</div>
    </div>
	<div id="counterAreaTra" class="d-flex align-items-center justify-content-center w-100">
		<div id="falseWcounterTra" class="counterElements bg-danger text-white rounded text-center m-2" style="display: none;">0</div>
		<div id="counterTra" class="text-white rounded text-center card" style = "display : none;">
			<span id = "downNbr">5</span>
		</div>
		<div id="trueWcounterTra" class="counterElements bg-warning text-white rounded text-center m-2" style="display: none;">0</div>
	</div>

	<div class="mt-3 w-50 d-flex flex-column justify-content-center align-item-center">
		<div id="textAreaTra" class = "card disable">
            <div id = "textRow">

            </div>
        </div>
		<input onkeypress="return errorCatch(event);" class="form-control mt-4 card text" id="textInputTra" type="text" disabled>
	</div>
</div>
