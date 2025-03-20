<?php

namespace Tests\Feature;

use App\Contracts\TriviaServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class TriviaGameTest extends TestCase
{
    public function test_can_get_new_question()
    {
        $mockService = Mockery::mock(TriviaServiceInterface::class);
        $mockService->shouldReceive('getRandomQuestion')
            ->once()
            ->andReturn([
                'question' => 'Test question',
                'correct_answer' => '42',
                'options' => ['41', '42', '43', '44']
            ]);
            
        $this->app->instance(TriviaServiceInterface::class, $mockService);
        
        $response = $this->get('/trivia/question');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'question',
                'options',
                'current_question'
            ]);
    }

    public function test_can_check_correct_answer()
    {
        session(['current_answer' => '42']);
        session(['current_question' => 1]);
        
        $response = $this->post('/trivia/check', [
            'answer' => '42'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'correct' => true,
                'game_won' => false
            ]);
        
        $this->assertEquals(2, session('current_question'));
    }

    public function test_game_ends_on_wrong_answer()
    {
        session(['current_answer' => '42']);
        session(['current_question' => 5]);
        
        $response = $this->post('/trivia/check', [
            'answer' => '41'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'correct' => false,
                'correct_answer' => '42'
            ]);
        
        $this->assertNull(session('current_question'));
    }

    public function test_game_wins_after_ten_correct_answers()
    {
        session(['current_answer' => '42']);
        session(['current_question' => 10]);
        
        $response = $this->post('/trivia/check', [
            'answer' => '42'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'correct' => true,
                'game_won' => true
            ]);
        
        $this->assertNull(session('current_question'));
    }
} 