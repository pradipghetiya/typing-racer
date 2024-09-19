<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Race</title>
    <style>
        /* General body settings */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e3f2fd;
        }

        /* Navbar styles */
        .navbar {
            background-color: #0d47a1;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #bbdefb;
            margin-right: 10px;
        }

        .race-options a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .race-options a:hover {
            background-color: #1976d2;
        }

        /* Typing area container */
        .typing-area {
            max-width: 900px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Text to type */
        .text-to-type {
            font-size: 20px;
            line-height: 1.8;
            margin-bottom: 25px;
            color: #424242;
        }

        /* Typing input field */
        .typing-input {
            width: 100%;
            height: 120px;
            font-size: 18px;
            padding: 15px;
            border: 1px solid #90a4ae;
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Word highlighting */
        .highlight {
            color: green;
        }

        .wrong {
            color: red;
        }

        /* Metrics (time, accuracy, WPM) */
        .metrics {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .metric {
            text-align: center;
        }

        .metric h3 {
            font-size: 16px;
            color: #0d47a1;
        }

        .metric p {
            font-size: 20px;
            font-weight: bold;
            color: #0288d1;
        }

        /* Button styles */
        .button-container {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .button {
            padding: 12px;
            background-color: #0d47a1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #1976d2;
        }

        .button:disabled {
            background-color: #90a4ae;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="user-info">
            <div class="user-avatar"></div>
            <span>Username</span>
        </div>
        <div>
            <h3>Typing Racer</h3>
        </div>
        <div class="race-options">
            <a href="create.php">Create Race</a>
            <a href="join.php">Join Race</a>
        </div>
    </nav>

    <div class="typing-area">
        <p class="text-to-type" id="text-to-type">
            <!-- Random paragraph will appear here -->
        </p>
        <textarea class="typing-input" id="typing-input" placeholder="Start typing here..." disabled></textarea>
        <div class="metrics">
            <div class="metric">
                <h3>Time</h3>
                <p id="time">120s</p>
            </div>
            <div class="metric">
                <h3>Accuracy</h3>
                <p id="accuracy">0%</p>
            </div>
            <div class="metric">
                <h3>WPM</h3>
                <p id="wpm">0</p>
            </div>
        </div>

        <div class="button-container">
            <button class="button" id="start-button">Start Race</button>
            <button class="button" id="stop-button" disabled>Stop Race</button>
            <!-- <button class="button" id="restart-button" disabled>Restart Race</button> -->
            <p hidden id="paraId"><?php echo $_GET['num'] ?></p>
        </div>
    </div>
    <script>
        const textToTypeElement = document.getElementById('text-to-type');
        const typingInput = document.getElementById('typing-input');
        const startButton = document.getElementById('start-button');
        const stopButton = document.getElementById('stop-button');
        const restartButton = document.getElementById('restart-button');
        const timeDisplay = document.getElementById('time');
        const accuracyDisplay = document.getElementById('accuracy');
        const wpmDisplay = document.getElementById('wpm');
        const paraId = document.getElementById('paraId');
        let textToType = [];

        var paragraphs = "";
        var race_number = paraId.innerHTML;

        fetch('fetchPara.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ race_number })
        })
            .then(response => response.json())
            .then(data => {
                paragraphs = data.paragraph;
            });

        function setNewParagraph() {
            textToType = paragraphs.split(' ');
            textToTypeElement.innerHTML = textToType.map(word => `<span>${word}</span>`).join(' ');
        }

        startButton.addEventListener('click', () => {
            typingInput.disabled = false;
            typingInput.focus();
            startButton.disabled = true;
            stopButton.disabled = false;
            restartButton.disabled = false;
            setNewParagraph();
            startRace();
        });

        stopButton.addEventListener('click', () => {
            clearInterval(timer);
            typingInput.disabled = true;
            stopButton.disabled = true;
        });

        restartButton.addEventListener('click', () => {
            resetRace();
            setNewParagraph();
            typingInput.disabled = false;
            typingInput.focus();
            startRace();
        });

        let timer, timeLeft = 120, wordsTyped = 0, totalChars = 0, correctChars = 0;

        function startRace() {
            timeLeft = 120;
            timer = setInterval(() => {
                timeLeft--;
                timeDisplay.innerText = timeLeft + 's';
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    typingInput.disabled = true;
                    calculateWPM();
                }
            }, 1000);
        }

        function resetRace() {
            clearInterval(timer);
            timeLeft = 120;
            timeDisplay.innerText = '120s';
            accuracyDisplay.innerText = '100%';
            wpmDisplay.innerText = '0';
            typingInput.value = '';
            wordsTyped = 0;
            totalChars = 0;
            correctChars = 0;
            startButton.disabled = false;
            stopButton.disabled = true;
            restartButton.disabled = true;
        }

        typingInput.addEventListener('input', updateTyping);

        function updateTyping() {
            const inputWords = typingInput.value.split(' ');

            inputWords.forEach((word, index) => {
                const typedWord = word.trim();
                const targetWord = textToType[index] || '';

                if (typedWord === targetWord) {
                    textToTypeElement.querySelectorAll('span')[index].classList.add('highlight');
                    textToTypeElement.querySelectorAll('span')[index].classList.remove('wrong');
                } else {
                    textToTypeElement.querySelectorAll('span')[index].classList.add('wrong');
                    textToTypeElement.querySelectorAll('span')[index].classList.remove('highlight');
                }
            });

            totalChars = typingInput.value.length;
            correctChars = inputWords.reduce((sum, word, index) => {
                return sum + (word === textToType[index] ? word.length : 0);
            }, 0);

            const accuracy = (correctChars / totalChars) * 100 || 100;
            accuracyDisplay.innerText = accuracy.toFixed(2) + '%';

            wordsTyped = inputWords.filter((word, index) => word === textToType[index]).length;
            calculateWPM();
        }

        function calculateWPM() {
            const timeTaken = 60 - timeLeft;
            const wpm = (wordsTyped / timeTaken) * 60 || 0;
            wpmDisplay.innerText = Math.round(wpm);
        }


    </script>
</body>

</html>