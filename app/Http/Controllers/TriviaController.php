<?php

namespace App\Http\Controllers;

use App\Contracts\TriviaServiceInterface;
use App\Services\EncryptionService;
use Illuminate\Http\Request;

class TriviaController extends Controller
{
    public function __construct(
        private TriviaServiceInterface $triviaService,
        private EncryptionService $encryptionService
    ) {}

    public function index()
    {
        return view('trivia.index');
    }

    public function getQuestion()
    {
        $currentQuestion = session('current_question', 1);
        $usedQuestions = session('used_questions', []);
        
        do {
            $question = $this->triviaService->getRandomQuestion();
        } while (in_array($question['question'], $usedQuestions));
        
        // Encrypt the correct answer before storing and sending
        $encryptedAnswer = $this->encryptionService->encrypt($question['correct_answer']);
        
        session([
            'used_questions' => [...$usedQuestions, $question['question']],
            'current_answer' => $question['correct_answer'] // Keep original for comparison
        ]);
        
        return response()->json([
            'question' => $question['question'],
            'options' => $question['options'],
            'current_question' => $currentQuestion,
            'answer_hash' => $encryptedAnswer // Send encrypted answer
        ]);
    }

    public function checkAnswer(Request $request)
    {
        $answer = $request->input('answer');
        $correctAnswer = session('current_answer');
        $currentQuestion = session('current_question', 1);
        
        $isCorrect = $answer === $correctAnswer;
        
        if ($isCorrect) {
            $currentQuestion++;
            session(['current_question' => $currentQuestion]);
        }
        
        $gameWon = $currentQuestion > 10;
        
        if (!$isCorrect || $gameWon) {
            session()->forget(['current_question', 'current_answer', 'used_questions']);
        }
        
        return response()->json([
            'correct' => $isCorrect,
            'correct_answer' => $isCorrect ? null : $correctAnswer, // Only send if wrong
            'game_won' => $gameWon
        ]);
    }
} 