<!DOCTYPE html>
<html>
<head>
    <title>Trivia Game</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/trivia-game.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div id="game-container" class="glow-container rounded-lg max-w-2xl w-full">
        <div class="content rounded-lg p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Trivia Game</h1>
            
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
        document.addEventListener('DOMContentLoaded', function() {
            window.game.init();
        });
    </script>
</body>
</html> 