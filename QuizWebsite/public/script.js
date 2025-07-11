
let questionsEl = document.querySelector("#questions");
let timerEl = document.querySelector("#timer");
let choicesEl = document.querySelector("#options");
let submitBtn = document.querySelector("#submit-score");
let startBtn = document.querySelector("#start");
let feedbackEl = document.querySelector("#feedback");
let reStartBtn = document.querySelector("#restart");

let currentQuestionIndex = 0;
let timerId;

startBtn.onclick = quizStart;

function quizStart() {
    shuffle(questions);

    document.getElementById("start-screen").classList.add("hide");
    questionsEl.classList.remove("hide");

    getQuestion();
}

function getQuestion() {
    let currentQuestion = questions[currentQuestionIndex];
    let questionEl = document.getElementById("question-words");
    let optionsEl = document.getElementById("options");

    questionEl.innerHTML = "";
    optionsEl.innerHTML = "";

    // ✅ Image rendering
    if (currentQuestion.image) {
        let img = document.createElement("img");
        img.src = currentQuestion.image;
        img.alt = "Question Image";
        img.style.maxWidth = "400px";
        img.style.marginBottom = "10px";
        questionEl.appendChild(img);
    }
    

    // ✅ Text rendering
    if (currentQuestion.text) {
        let textNode = document.createElement("p");
        textNode.textContent = currentQuestion.text;
        questionEl.appendChild(textNode);
    }

    // ✅ Option buttons
    for (let i = 1; i <= 4; i++) {
        let optionText = currentQuestion["option" + i];
        let optionBtn = document.createElement("button");
        optionBtn.textContent = `${i}. ${optionText}`;
        optionBtn.onclick = () => questionClick(i); // Pass option number
        optionsEl.appendChild(optionBtn);
    }

    // ✅ Timer per question
    startQuestionTimer(parseInt(currentQuestion.duration));
}

let selectedAnswers = [];

function questionClick(selectedOptionNumber) {
    // Disable buttons immediately to prevent multiple clicks
    Array.from(choicesEl.children).forEach(btn => btn.disabled = true);

    const currentQuestion = questions[currentQuestionIndex];

    selectedAnswers.push({
        question_id: currentQuestion.id,
        selected_option: selectedOptionNumber
    });

    currentQuestionIndex++;

    if (currentQuestionIndex === questions.length) {
        quizEnd();
    } else {
        getQuestion();
    }
}


function quizEnd() {
    clearInterval(timerId);
    questionsEl.classList.add("hide");
    document.getElementById("quiz-end").classList.remove("hide");

    const answersContainer = document.getElementById("answers-container");
    selectedAnswers.forEach((ans, index) => {
        answersContainer.innerHTML += `
            <input type="hidden" name="answers[${index}][question_id]" value="${ans.question_id}" />
            <input type="hidden" name="answers[${index}][selected_option]" value="${ans.selected_option}" />
        `;
    });
}


function startQuestionTimer(duration) {
    if (timerId) clearInterval(timerId);
    let timeLeft = isNaN(duration) ? 30 : duration;

    timerEl.textContent = timeLeft;

    timerId = setInterval(() => {
        timeLeft--;
        timerEl.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(timerId);
            feedbackEl.textContent = "Time's up!";
            feedbackEl.style.color = "red";
            feedbackEl.classList.remove("hide");
            setTimeout(() => feedbackEl.classList.add("hide"), 1500);

            currentQuestionIndex++;
            if (currentQuestionIndex === questions.length) {
                quizEnd();
            } else {
                getQuestion();
            }
        }
    }, 1000);
}

// Fisher-Yates Shuffle Algorithm
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}  

// ✅ Violation tracking
document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        sendViolationReport();
    }
});

function sendViolationReport() {
    let studentIdEl = document.getElementById("student_id");
    if (!studentIdEl) return;
    let studentId = studentIdEl.value;

    fetch("/report-violation", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ student_id: studentId })
    })
    .then(response => response.json())
    .then(data => console.log(data.message))
    .catch(error => console.error("Violation report failed:", error));
}

