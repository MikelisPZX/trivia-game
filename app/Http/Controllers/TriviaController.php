<?php

namespace App\Http\Controllers;

use App\Contracts\TriviaServiceInterface;
use Illuminate\Http\Request;

class TriviaController extends Controller
{
    public function __construct(
        private TriviaServiceInterface $triviaService
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
        
        session([
            'used_questions' => [...$usedQuestions, $question['question']],
            'current_answer' => $question['correct_answer']
        ]);
        
        return response()->json([
            'question' => $question['question'],
            'options' => $question['options'],
            'current_question' => $currentQuestion
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
            'correct_answer' => $correctAnswer,
            'game_won' => $gameWon
        ]);
    }
} 