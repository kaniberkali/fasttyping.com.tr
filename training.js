
const falseWcounterTra =  document.getElementById("falseWcounterTra");
const counterTra = document.getElementById("counterTra");
const trueWcounterTra = document.getElementById("trueWcounterTra");
const textAreaRow = document.getElementById("textAreaTra");
const textInputTra = document.getElementById("textInputTra");
const counterAreaTra = document.getElementById("counterAreaTra");
const downNbr = document.getElementById("downNbr");
var minuteSetTimeOut;
var countSetTimeOut;
let stackCounter = parseInt(gameTime);
let i;
var scoreResult;
var trueLetterTraining;
var falseLetterTraining;

async function trainingStart(){
    trueLetterTraining = 0;
    falseLetterTraining = 0;
    i = 0;
    scoreResult = 0;
    $("#textRow").scrollTop("0px");
    counterTra.innerHTML = "<span class = 'downCount'>5</span>";
    trueWcounterTra.innerHTML = 0;
    falseWcounterTra.innerHTML = 0;
    let areaCon = ' ';
    await postWithFunction("words", function(response){
        response.forEach(e => { areaCon += `<span id = "${e.id}">${e.word}</span>`; });
        textRow.innerHTML = areaCon;
    }, {"count" : parseInt(gameWordCount), "difficulity":gameDifficulty});
    $("#textAreaTra span").css({"font-size":gameFontSize+"px"});
    startBtn.style.display = "none";
    document.getElementById("config").style.display = "none";
    document.querySelectorAll('#textAreaTra span')[0].classList = "first-span";
    addingButtons();
    addTrainingCounterArea();
    if(document.getElementById("drpthema"))
        removeSettingsIcon(document.getElementById("drpthema").parentElement.parentElement, "none");
    countDown();
}

async function trainingStop(){
    window.location.reload();
}

function trainingRestart(){
    if(document.getElementById("stopBtn").children[0].classList == "fa-solid fa-play")
        document.getElementById("stopBtn").innerHTML = `<i class="fa-solid fa-pause">`;
    counterTra.innerHTML = `<div class = "loader"></div>`
    i = 0;
    textInputTra.disabled = true;
    textAreaTra.classList = "card disable";
    if(countSetTimeOut)
        clearTimeout(countSetTimeOut);
    clearTimeout(minuteSetTimeOut);
    trainingStart();
}
async function f_minuteTra(counter){
    if(textInputTra.disabled == true){
        textInputTra.disabled = false;
        textInputTra.focus();
    }
    minuteSetTimeOut = await setTimeout(() => {
        let count = counter;
        if ($(".first-span").length <= 0 || count == 0)
        {
            $("#modal").load("inc/modal/score.php", function(){
                $("#score")[0].textContent = scoreResult;
                blurDiv.classList = "blurDivActive";
                modal.classList = "active-modal";
                getScoreTraining();
            });
            if(document.getElementById("drpthema"))
                removeSettingsIcon(document.getElementById("drpthema").parentElement.parentElement, "none");
            document.getElementById("config").style.display = "block";
            removeButtons();
            textInputTra.value = "";
            startBtn.style.display = "block";
            counterTra.style.display = "none"
            falseWcounterTra.style.display = "none";
            trueWcounterTra.style.display = "none";
            textRow.innerHTML = "";
            if(textInputTra.disabled == false)
                textInputTra.disabled = true;
            textAreaTra.classList = "card disable";
        }
        else
        {
            count -= 1;
            counterTra.innerHTML = `<span id = "countNbr">${count}</span>`;
            f_minuteTra(count);
        }
    },1000);
}

async function countDown(){
    let waitCount = counterTra.children[0].innerHTML;
        countSetTimeOut = await setTimeout(() => {
                if(counterTra.children[0].innerHTML == "Pause")
                {
                    waitCount = -1;
                }
                if(waitCount <= 5 && waitCount >= 1){
                    waitCount -= 1;
                    counterTra.innerHTML = `<span id = "downNbr">${waitCount}</span>`;
                    countDown(waitCount); 
                }
                else if( waitCount == 0)
                {
                    textAreaTra.classList = "card";
                    counterTra.innerHTML = stackCounter;
                    f_minuteTra(stackCounter);
                }
        }, 1000);
}
function addTrainingCounterArea() {
    counterAreaTra.classList = "d-flex align-items-center justify-content-between";
    counterTra.style.display = "block"
    falseWcounterTra.style.display = "block";
    trueWcounterTra.style.display = "block";
}

function errorCatch(event)
{
    if ($(".first-span").length > 0)
        return (!(gameErrorCatch == "Olsun" && document.querySelector('#textRow .first-span').classList.contains("bg-danger") && event.keyCode != 8));
    else
        return false;
}

if(textInputTra){
    textInputTra.addEventListener('keyup', async function(e) {
        if (errorCatch(e) && $(".first-span").length > 0)
        {
            let firstSpan = document.querySelector('#textRow .first-span');
            if(textRow.innerHTML.length > 0)
            {
                if(textInputTra.value != firstSpan.innerHTML && textInputTra.value != firstSpan.innerHTML + " ")
                    searchInputTra(firstSpan);
                if(e.keyCode == 32 || e.keyCode == 13 || e.keyCode == 16 || e.keyCode == 9)
                {
                    var text = textInputTra.value;
                    textInputTra.value = text.split(String.fromCharCode(e.keyCode))[1] != undefined ? text.split(String.fromCharCode(e.keyCode))[1] : "";
                    if (e.keyCode == 32)
                        text = text.split(' ')[0];
                    scoreCalculator(firstSpan.innerHTML, text.trim());
                    if(text.trim() === firstSpan.innerHTML)
                    {
                        trueWcounterTra.innerHTML = parseInt(trueWcounterTra.innerHTML) + 1;
                        if (gameTrace == "Olsun")
                            $("#textRow span")[$(".first-span").index()].style.color = 'green';
                    }
                    else{
                        falseWcounterTra.innerHTML = parseInt(falseWcounterTra.innerHTML) + 1;
                        if (gameTrace == "Olsun")
                            $("#textRow span")[$(".first-span").index()].style.color = 'red';
                    }
                    deleteSpanTra();
                }
            }
        }
        else
        {
            
        }
    });
}

function deleteSpanTra() {
    if(localStorage.getItem("gameStyle") == "Statik")
    {
        if (textAreaTra != null) {
        	textRow.removeChild(document.querySelector('#textRow span'));
        	if (document.querySelectorAll("#textAreaTra span")[0])
        		document.querySelectorAll("#textAreaTra span")[0].classList = "first-span";
        }   
    }
    else{
        if (document.querySelectorAll("#textAreaTra span")[i] != null)
        {
            document.querySelectorAll("#textAreaTra span")[i].classList = "";
            i += 1;
            if (document.querySelectorAll("#textAreaTra span")[i])
            {
                document.querySelectorAll("#textAreaTra span")[i].classList = "first-span";
                    $(".first-span")[0].scrollIntoView({block: 'center', inline: 'center' });
                    $("#textAreaTra")[0].scrollIntoView({block: 'center', inline: 'center' });
            }
        }
    }
}

function searchInputTra(data) {
    let regex = new RegExp(`^${textInputTra.value}`, "gi");
    if(data.innerHTML.match(regex)){
        document.querySelectorAll('#textAreaTra span')[i].classList = "first-span rounded";
    }else{
        document.querySelectorAll('#textAreaTra span')[i].classList = "bg-danger text-white rounded first-span";
    }
}
function scoreCalculator(text, input){
    if (text == input)
    {
        scoreResult += 11;
    }
    else{
        scoreResult -= 11;
    }
    if (text.startsWith(input)){
        falseLetterTraining += text.length - input.length;
        trueLetterTraining += input.length;
        scoreResult += input.length - (text.length - input.length);
    }
    else{
        console.log(false);
        scoreResult -= text.length;
        falseLetterTraining += input.length;
    }
}

function getScoreTraining(){
    if ($("div[id=scoreCard]").length > 0)
	{
        new Chart(document.getElementById("wordAnalysis"), {
            type: 'doughnut',
            data: {
                datasets: [{
                backgroundColor: ["#5cb85c", "#d9534f"],
                borderColor: ["transparent", "transparent"],
                data: [trueWcounterTra.textContent, falseWcounterTra.textContent,]
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
                data: [trueLetterTraining, falseLetterTraining]
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
        postWithFunction("get-level", async function(data) {
            $("h1[id=levelText]")[0].textContent = "Seviye " + (data.level == undefined ? "0" :data.level);
            $("div[id=level-progress]").css("--value", data.percent);
        });
        document.getElementById("score").innerHTML = scoreResult;
    }
}