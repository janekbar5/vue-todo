<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterTest extends TestCase
{
    
    public function test_can_register()
    {
        
        $payload = [
            'name' => 'janek1',
            'email' => 'janek1@wp.pl',
            'password' => 'janek1@wp.pl',
            'password_confirmation' => 'janek1@wp.pl',
        ];

        $this->json('post', 'api/v1/auth/register', $payload)
            ->assertStatus(200);      
        

    }   
    
}
