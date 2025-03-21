<?php

namespace App\Services;

use App\Contracts\TriviaServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class NumbersApiTriviaService implements TriviaServiceInterface
{
    private const API_URL = 'http://numbersapi.com';
    
    public function getRandomQuestion(): array
    {
        $number = random_int(1, 100);
        $response = Http::get(self::API_URL . "/{$number}/trivia");
        
        $fact = $response->body();
        $correctAnswer = (string) $number;
        
        // Replace the number with 'X'
        $fact = preg_replace("/\b{$number}\b/", 'X', $fact);
        
        // Generate wrong options and convert to strings
        $options = collect(range(max(1, $number - 10), $number + 10))
            ->filter(fn($n) => $n != $number)
            ->shuffle()
            ->take(3)
            ->map(fn($n) => (string) $n)
            ->push($correctAnswer)
            ->shuffle()
            ->values()
            ->all();
            
        return [
            'question' => $fact,
            'correct_answer' => $correctAnswer,
            'options' => $options
        ];
    }
} 