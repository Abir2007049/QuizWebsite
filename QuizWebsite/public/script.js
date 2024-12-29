

let questionsEl =
    document.querySelector(
        "#questions"
    );
let timerEl =
    document.querySelector("#timer");
let choicesEl =
    document.querySelector("#options");
let submitBtn = document.querySelector(
    "#submit-score"
);
let startBtn =
    document.querySelector("#start");
let nameEl =
    document.querySelector("#name");
let feedbackEl = document.querySelector(
    "#feedback"
);
let reStartBtn =
    document.querySelector("#restart");


let currentQuestionIndex = 0;
let time ;
let score=0;
let timerId;



function quizStart() {

    shuffle(questions);

    timerId = setInterval(
        clockTick,
        1000
    );
    //timerEl.textContent = time;
    let landingScreenEl =
        document.getElementById(
            "start-screen"
        );
    landingScreenEl.setAttribute(
        "class",
        "hide"
    );
    questionsEl.removeAttribute(
        "class"
    );
    getQuestion();
}


function getQuestion() {
    let currentQuestion = questions[currentQuestionIndex];

    let promptEl = document.getElementById("question-words");
    promptEl.textContent = currentQuestion.text;

    let choicesEl = document.getElementById("options");
    choicesEl.innerHTML = ""; 

    

    for (let i = 1; i <= 4; i++) {
        let optionText = currentQuestion["option" + i];

        let choiceBtn = document.createElement("button");
        choiceBtn.setAttribute("value", optionText);
        choiceBtn.textContent = i + ". " + optionText;

      
        choiceBtn.onclick = function () {
            questionClick(optionText);
        };

        choicesEl.appendChild(choiceBtn);
    }  

    startQuestionTimer(currentQuestion.duration);
    
}



function questionClick(selectedValue) {
    if (
        selectedValue !== questions[currentQuestionIndex]["option" + questions[currentQuestionIndex].right_option]
    ) {
        feedbackEl.textContent = `Wrong!`;
        feedbackEl.style.color = "red";
    } else {
        feedbackEl.textContent = "Correct!";
        feedbackEl.style.color = "green";
        score++;
    }

    feedbackEl.setAttribute("class", "feedback");
    setTimeout(function () {
        feedbackEl.setAttribute("class", "feedback hide");
    }, 2000);

    clearInterval(timerId);

    currentQuestionIndex++;
    if (currentQuestionIndex === questions.length) {
        quizEnd();
    } else {
        getQuestion();
    }
}



function quizEnd() {
    clearInterval(timerId);
    let endScreenEl =
        document.getElementById(
            "quiz-end"
        );
    endScreenEl.removeAttribute(
        "class"
    );
    let finalScoreEl =
        document.getElementById(
            "score-final"
        );
    finalScoreEl.textContent = score;
    questionsEl.setAttribute(
        "class",
        "hide"
    );
}



function clockTick() {
    time--;
    timerEl.textContent = time;
    if (time <= 0) {
        quizEnd();
    }
}



// Fisher-Yates Shuffle Algorithm
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

function startQuestionTimer(duration) {
    if (timerId) {
        clearInterval(timerId);
    }

    let timeLeft = duration; // Set the timer for the current question
    timerEl.textContent = timeLeft;

    timerId = setInterval(() => {
        timeLeft--;
        timerEl.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(timerId);

            feedbackEl.textContent = "Time's up!";
            feedbackEl.style.color = "red";

            setTimeout(function () {
                feedbackEl.setAttribute("class", "feedback hide");
            }, 2000);

            currentQuestionIndex++;
            if (currentQuestionIndex === questions.length) {
                quizEnd();
            } else {
                getQuestion();
            }
        }
    }, 1000);
}







function checkForEnter(event) {
    if (event.key === "Enter") {
       
        alert(
            "Your Score has been Submitted"
        );
    }
}
nameEl.onkeyup = checkForEnter;


startBtn.onclick = quizStart;