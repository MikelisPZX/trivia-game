<!DOCTYPE html>
<html>
<head>
    <title>Trivia Game</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div id="game-container" class="glow-container rounded-lg max-w-2xl w-full">
        <div class="content rounded-lg p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">General Trivia Game</h1>
            
            <div id="question-container">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-700">Question <span id="question-number">1</span>/10</h2>
                    <div class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        Find the value of X
                    </div>
                </div>
                
                <p id="question-text" class="text-lg text-gray-600 mb-8 p-6 bg-gray-50 rounded-lg"></p>
                
                <div id="options-container" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
            </div>
            
            <div id="result-container" class="text-center" style="display: none;">
                <p id="result-message" class="text-xl mb-6"></p>
                <button onclick="startGame()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                    Play Again
                </button>
            </div>
        </div>
    </div>

    <script>
        
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        
        function startGame() {
            document.getElementById('question-container').style.display = 'block';
            document.getElementById('result-container').style.display = 'none';
            loadQuestion();
        }

        function loadQuestion() {
            axios.get('/trivia/question')
                .then(response => {
                    const data = response.data;
                    document.getElementById('question-number').textContent = data.current_question;
                    document.getElementById('question-text').textContent = data.question;
                    
                    const optionsContainer = document.getElementById('options-container');
                    optionsContainer.innerHTML = '';
                    
                    data.options.forEach(option => {
                        const button = document.createElement('button');
                        button.textContent = option;
                        button.className = 'p-4 text-lg font-medium rounded-lg border-2 border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition duration-200';
                        button.onclick = () => submitAnswer(option);
                        optionsContainer.appendChild(button);
                    });
                });
        }

        function submitAnswer(answer) {
            axios.post('/trivia/check', { answer })
                .then(response => {
                    const data = response.data;
                    const resultContainer = document.getElementById('result-container');
                    const resultMessage = document.getElementById('result-message');
                    const messageClass = data.correct ? 'text-green-600' : 'text-red-600';
                    
                    if (data.game_won) {
                        resultMessage.innerHTML = `
                            <div class="text-green-600 mb-4">🎉 Congratulations! You've won the game! 🎉</div>
                        `;
                    } else if (!data.correct) {
                        resultMessage.innerHTML = `
                            <div class="text-red-600 mb-4">Wrong answer!</div>
                            <div class="text-gray-600">The correct answer was: ${data.correct_answer}</div>
                        `;
                    }
                    
                    if (data.game_won || !data.correct) {
                        document.getElementById('question-container').style.display = 'none';
                        resultContainer.style.display = 'block';
                    } else {
                        loadQuestion();
                    }
                });
        }

        // Start the game when page loads
        startGame();
    </script>
</body>
</html> 