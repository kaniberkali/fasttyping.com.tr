<div id = "scoreCard" class = " d-flex flex-column justify-content-center align-items-center active-score card">
    <div class = "d-flex justify-content-between align-items-center">
       
        <div class = "d-flex flex-column justify-content-center align-items-center">
            <div role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="--value:0" id="level-progress"></div>
            <h1 id = "levelText"></h1>
        </div>
    </div>
    <div class = "d-flex flex-row">
        <div class = "w-50 p-0" style = "position : relative;">
            <canvas id="letterAnalysis" width="300"></canvas>
        </div>
        <div class = "w-50 p-0" style = "position : relative;">
            <canvas id="wordAnalysis" width="300"></canvas>
        </div>
    </div>
    <h3>Score : <span id = "score">0</span></h3>
    <button id = "okBtn" class = "btn btn-success mb-2" onclick = "deleteItems();">Tamam</button>
</div>