import { createGame } from './services/gameService';

// Create and expose the game instance
window.game = createGame();

// Expose necessary methods globally
window.startGame = () => window.game.startGame();

export function initGame() {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
    
    window.loadQuestion = function() {
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

    window.submitAnswer = function(answer) {
        axios.post('/trivia/check', { answer })
            .then(response => {
                const data = response.data;
                const resultContainer = document.getElementById('result-container');
                const resultMessage = document.getElementById('result-message');
                
                if (data.game_won) {
                    resultMessage.innerHTML = `
                        <div class="text-green-600 mb-4">🎉 Congratulations! You've won the game! 🎉</div>
                    `;
                    fireConfetti();
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

    // Start the game when initialized
    startGame();
} 