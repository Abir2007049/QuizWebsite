let questionsEl = document.querySelector("#questions");
let timerEl = document.querySelector("#timer");
let choicesEl = document.querySelector("#options");
let submitBtn = document.querySelector("#submit-score");
let startBtn = document.querySelector("#start");
let nameEl = document.querySelector("#name");
let feedbackEl = document.querySelector("#feedback");
let reStartBtn = document.querySelector("#restart");

let currentQuestionIndex = 0;
let time;
let score = 0;
let timerId;

startBtn.onclick = quizStart;

function quizStart() {
    console.log("Quiz started"); // Debugging line
    shuffle(questions); // Randomize questions if needed

    // Hide the start screen
    let landingScreenEl = document.getElementById("start-screen");
    landingScreenEl.setAttribute("class", "hide");

    // Show the question screen
    questionsEl.removeAttribute("class");

    // Start the first question
    getQuestion();
}

function getQuestion() {
    console.log("Fetching question"); // Debugging message
    let currentQuestion = questions[currentQuestionIndex];
    let questionEl = document.getElementById("question-words");
    questionEl.textContent = currentQuestion.text;

    let optionsEl = document.getElementById("options");
    optionsEl.innerHTML = ""; // Clear previous options

    // Create option buttons
    for (let i = 1; i <= 4; i++) {
        let optionText = currentQuestion["option" + i];
        let optionBtn = document.createElement("button");
        optionBtn.textContent = `${i}. ${optionText}`;
        optionBtn.onclick = () => questionClick(optionText);
        optionsEl.appendChild(optionBtn);
    }

    // Start the timer for the current question (assuming 30 seconds for each question)
    startQuestionTimer(currentQuestion.duration); // Set the timer for the current question
}

function questionClick(selectedValue) {
    if (selectedValue !== questions[currentQuestionIndex]["option" + questions[currentQuestionIndex].right_option]) {
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
    let endScreenEl = document.getElementById("quiz-end");
    endScreenEl.removeAttribute("class");
    let finalScoreEl = document.getElementById("score-final");
    finalScoreEl.textContent = score;

    // Populate the hidden input with the final score
    let finalScoreInput = document.getElementById("final-score");
    finalScoreInput.value = score;

    questionsEl.setAttribute("class", "hide");
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
        alert("Your Score has been Submitted");
    }
}
nameEl.onkeyup = checkForEnter;

document.addEventListener("DOMContentLoaded", function() {
    startBtn = document.querySelector("#start");
    startBtn.onclick = quizStart;
});


document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        // Student switched the tab
        sendViolationReport();
    }
});

function sendViolationReport() {
    let studentId = document.getElementById("student_id").value; // Assuming there's an input field for student ID

    fetch("/report-violation", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ student_id: studentId })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message); // Success or error message
    })
    .catch(error => console.error("Error:", error));
}



