<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * Tests the desired endpoint for showing all comments.
     *
     * @return void
     * @test
     */
    public function test_can_show_all_comments()
    {
        $response = $this->get('/comments');

        $response->assertStatus(200);
    }

    /**
     * Tests the desired endpoint for publishing a new comment.
     *
     * @return void
     * @test
     */
    public function test_insert_a_new_comment()
    {
        $response = $this->post('/comments', [
            "name" => "Franz",
            "message" => "This is an UNIT Test",
        ]);

        $response->assertRedirect('/comments');

    }
    /**
     * Tests the desired endpoint for reply to an existing comment.
     *
     * @return void
     * @test
     */
    public function test_reply_to_a_comment()
    {
        $response = $this->post('/comments/{id}/reply', [
            "name" => "Franz",
            "message" => "This is an UNIT Test Reply",
        ]);

        $response->assertStatus(200);
    }
    /**
     * Tests the desired endpoint for updating an existing comment.
     *
     * @return void
     * @test
     */
    public function test_update_an_existing_comment()
    {
        $response = $this->patch('/comments/{id}', [
            "name" => "Edited Name",
            "message" => "This message has been edited",
        ]);

        $response->assertRedirect('/comments');
    }
    /**
     * Tests the if the database is ok using the DatabaseSeeder data.
     *
     * @return void
     * @test
     */
    public function test_database_seeder_data()
    {
        $this->assertDatabaseHas('comments', [
            "name" => "John Smith",
        ]);
    }
}
