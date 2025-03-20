<?php

namespace App\Contracts;

interface TriviaServiceInterface
{
    /**
     * Get a random trivia question
     *
     * @return array{
     *   question: string,
     *   correct_answer: string,
     *   options: array<string>
     * }
     */
    public function getRandomQuestion(): array;
} 