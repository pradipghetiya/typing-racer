const sampleText = document.getElementById('sample-text');
const userInput = document.getElementById('user-input');
const startStopBtn = document.getElementById('start-stop-btn');
const wpmDisplay = document.getElementById('wpm');
const accuracyDisplay = document.getElementById('accuracy');
const timeDisplay = document.getElementById('time');
const profileImage = document.getElementById('profile-image');
const profileDropdown = document.querySelector('.profile-dropdown');

let startTime;
let endTime;
let timerInterval;
let isRunning = false;
let mistakes = 0;
let totalKeystrokes = 0;

const texts = [
    "The quick brown fox jumps over the lazy dog. Pack my box with five dozen liquor jugs.",
    "How vexingly quick daft zebras jump! The five boxing wizards jump quickly.",
    "Sphinx of black quartz, judge my vow. Waltz, nymph, for quick jigs vex Bud.",
    "Quick zephyrs blow, vexing daft Jim. Two driven jocks help fax my big quiz.",
    "The jay, pig, fox, zebra, and my wolves quack! Blowzy red vixens fight for a quick jump."
];

startStopBtn.addEventListener('click', toggleGame);
userInput.addEventListener('input', checkInput);
profileImage.addEventListener('click', toggleProfileDropdown);

function toggleProfileDropdown() {
    profileDropdown.classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.addEventListener('click', function(event) {
    if (!event.target.matches('.profile-image')) {
        const dropdowns = document.getElementsByClassName('profile-dropdown');
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
});

function toggleGame() {
    if (isRunning) {
        stopGame();
    } else {
        startGame();
    }
}

function startGame() {
    userInput.value = '';
    userInput.disabled = false;
    userInput.focus();
    startTime = new Date();
    isRunning = true;
    startStopBtn.textContent = 'Stop';
    timerInterval = setInterval(updateTimer, 1000);
    mistakes = 0;
    totalKeystrokes = 0;
    sampleText.textContent = getRandomText();
    userInput.classList.remove('error', 'success');
}

function stopGame() {
    userInput.disabled = true;
    isRunning = false;
    endTime = new Date();
    clearInterval(timerInterval);
    startStopBtn.textContent = 'Start';
    calculateResults();
    highlightMistakes();
}

function updateTimer() {
    const currentTime = new Date();
    const elapsedTime = Math.floor((currentTime - startTime) / 1000);
    timeDisplay.textContent = elapsedTime;
}

function checkInput() {
    const inputLength = userInput.value.length;
    const currentAccuracy = calculateAccuracy();
    accuracyDisplay.textContent = currentAccuracy;

    totalKeystrokes++;
    if (userInput.value[inputLength - 1] !== sampleText.textContent[inputLength - 1]) {
        mistakes++;
        userInput.classList.add('error');
    } else {
        userInput.classList.remove('error');
    }

    if (userInput.value === sampleText.textContent) {
        stopGame();
        userInput.classList.add('success');
    }
}

function calculateAccuracy() {
    return Math.round(((totalKeystrokes - mistakes) / totalKeystrokes) * 100) || 100;
}

function calculateResults() {
    const timeInMinutes = (endTime - startTime) / 60000;
    const wordsTyped = userInput.value.trim().split(/\s+/).length;
    const wpm = Math.round(wordsTyped / timeInMinutes);
    wpmDisplay.textContent = wpm;
}

function getRandomText() {
    return texts[Math.floor(Math.random() * texts.length)];
}

function highlightMistakes() {
    const inputText = userInput.value;
    const sampleTextContent = sampleText.textContent;
    let highlightedText = '';

    for (let i = 0; i < sampleTextContent.length; i++) {
        if (i >= inputText.length) {
            highlightedText += `<span class="not-typed">${sampleTextContent[i]}</span>`;
        } else if (inputText[i] === sampleTextContent[i]) {
            highlightedText += `<span class="correct">${sampleTextContent[i]}</span>`;
        } else {
            highlightedText += `<span class="incorrect">${sampleTextContent[i]}</span>`;
        }
    }

    sampleText.innerHTML = highlightedText;
}

// Initialize the game
sampleText.textContent = getRandomText();