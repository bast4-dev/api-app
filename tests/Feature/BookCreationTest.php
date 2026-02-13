<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_book_can_be_created_with_valid_data(): void
    {
        $user = User::factory()->create();

        $bookData = [
            'title' => 'livre fictif',
            'author' => 'auteur fictif',
            'summary' => 'firenfjezbnfhiefierkbjkrzhebfhjhref',
            'isbn' => '9780451524935'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/v1/books', $bookData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => 'livre fictif',
            'author' => 'auteur fictif',
            'summary' => 'firenfjezbnfhiefierkbjkrzhebfhjhref',
            'isbn' => '9780451524935'
        ]);
    }

    public function test_book_cannot_be_created_with_invalid_data(): void
    {
        $user = User::factory()->create();
        
        $invalidBookData = [
            'title' => 'li', 
            'author' => 'auteur fictif',
            'summary' => 'firenfjezbnfhiefierkbjkrzhebfhjhref',
            'isbn' => '9780451524935'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/v1/books', $invalidBookData);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('books', [
            'title' => 'li', 
        ]);
        
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_book_cannot_be_created_without_authentication(): void
    {
        $bookData = [
            'title' => 'livre fictif sans auth',
            'author' => 'auteur fictif',
            'summary' => 'firenfjezbnfhiefierkbjkrzhebfhjhref',
            'isbn' => '9780451524935'
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('books', [
            'title' => 'livre fictif sans auth',
        ]);
    }
}
