<?php

namespace Feature\Api\Forms;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateFormTest extends TestCase
{
    private const CREATE_FORM_ENDPOINT = '/api/forms';

    private array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public function testTryCreateFormWithoutName(): void
    {
        $response = $this->withHeaders($this->headers)->post(self::CREATE_FORM_ENDPOINT, [
            'slug' => 'hello'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }
}
