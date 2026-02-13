<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_professional_email_returns_true(): void
    {
        $user = User::factory()->create([
            'email' => 'john@entreprise.com'
        ]);

        $this->assertTrue($user->usesProfessionalEmail());
    }

    public function test_gmail_email_returns_false(): void
    {
        $user = User::factory()->create([
            'email' => 'john@gmail.com'
        ]);

        $this->assertFalse($user->usesProfessionalEmail());
    }
}
