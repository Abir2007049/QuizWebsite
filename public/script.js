// script.js
let questionsEl = document.querySelector("#questions");
let timerEl = document.querySelector("#timer");
let choicesEl = document.querySelector("#options");
let startBtn = document.querySelector("#start");
let feedbackEl = document.querySelector("#feedback");

let currentQuestionIndex = 0;
let timerId;
let quizStarted = false;
let quizEnded = false;
let selectedAnswers = [];

// Pull data from Blade
const questions = window.questions || [];
const quizId = window.quizId;
const studentId = window.studentId;

// Start button
startBtn.onclick = quizStart;

function quizStart() {
    if (!questions.length) return alert("No questions available!");
    shuffle(questions);
    quizStarted = true;

    document.getElementById("start-screen").classList.add("hide");
    questionsEl.classList.remove("hide");

    getQuestion();

    // Warn before leaving page
    window.onbeforeunload = () =>
        "Are you sure you want to leave? Your progress will be lost.";

    document.addEventListener("visibilitychange", handleVisibilityChange);
}

function getQuestion() {
    let currentQuestion = questions[currentQuestionIndex];
    let questionEl = document.getElementById("question-words");
    let optionsEl = document.getElementById("options");

    questionEl.innerHTML = "";
    optionsEl.innerHTML = "";

    if (!currentQuestion) {
        questionEl.innerHTML = "No more questions.";
        return;
    }

    // Render question image if exists
    if (currentQuestion.image) {
        console.log("Loading image:", currentQuestion.image);
        let img = document.createElement("img");

        // Laravel storage path handling
        img.src = currentQuestion.image.startsWith("http")
            ? currentQuestion.image
            : "/storage/" + currentQuestion.image;

        img.alt = "Question Image";
        img.style.maxWidth = "400px";
        img.style.marginBottom = "10px";
        img.className = "rounded shadow mb-2";
        questionEl.appendChild(img);
    }

    // Render question text if exists
    if (currentQuestion.text) {
        let textNode = document.createElement("p");
        textNode.textContent = currentQuestion.text;
        textNode.className = "mb-4";
        questionEl.appendChild(textNode);
    }

    // Render only non-null & non-empty options
    for (let i = 1; i <= 4; i++) {
        let optionText = currentQuestion["option" + i];
        if (!optionText || optionText.trim() === "") continue;

        let optionBtn = document.createElement("button");
        optionBtn.textContent = optionText;
        optionBtn.className =
            "block w-full my-2 py-3 px-4 rounded bg-[#27293d] hover:bg-[#3b3f5c] text-gray-200 transition";
        optionBtn.onclick = () => questionClick(i);
        optionsEl.appendChild(optionBtn);
    }

    startQuestionTimer(parseInt(currentQuestion.duration));
}

function questionClick(selectedOptionNumber) {
    Array.from(choicesEl.children).forEach(btn => btn.disabled = true);

    const currentQuestion = questions[currentQuestionIndex];

    selectedAnswers.push({
        question_id: currentQuestion.id,
        selected_option: selectedOptionNumber === 0 ? null : selectedOptionNumber,
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
    quizEnded = true;
    quizStarted = false;

    questionsEl.classList.add("hide");
    document.getElementById("quiz-end").classList.remove("hide");
    window.onbeforeunload = null;

    const answersContainer = document.getElementById("answers-container");
    answersContainer.innerHTML = "";
    selectedAnswers.forEach((ans, index) => {
        answersContainer.innerHTML += `
            <input type="hidden" name="answers[${index}][question_id]" value="${ans.question_id}" />
            <input type="hidden" name="answers[${index}][selected_option]" value="${ans.selected_option !== null ? ans.selected_option : ''}" />
        `;
    });

    document.removeEventListener("visibilitychange", handleVisibilityChange);
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

            questionClick(0);
            if (currentQuestionIndex === questions.length) quizEnd();
        }
    }, 1000);
}

// Fisher-Yates Shuffle
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

// Tab-switch violation tracking
// Tab-switch violation tracking
function handleVisibilityChange() {
    if (!quizStarted || quizEnded) return;

    fetch("/report-tab-switch", {   // <-- updated URL
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            student_id: studentId,
            quiz_id: quizId,
            state: document.hidden ? "hidden" : "visible",
            time: new Date().toISOString()
        })
    }).catch(console.error);
}

