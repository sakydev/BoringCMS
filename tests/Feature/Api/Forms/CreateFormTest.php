<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\Form;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CreateFormTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const CREATE_FORM_ENDPOINT = '/api/forms';
    private const VALID_NAME = 'Test Form';
    private const VALID_SLUG = 'test-slug-here';

    private array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public function testCreateForm(): void {
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
        ];

        $response = $this->withHeaders($this->headers)
            ->postJson(self::CREATE_FORM_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'form' => [
                        'id',
                        'name',
                        'slug',
                        'created',
                        'updated',
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $formContent = $responseContent['content']['form'];

        $this->assertEquals($requestContent['name'], $formContent['name']);
        $this->assertEquals($requestContent['slug'], $formContent['slug']);
    }

    public function testTryCreateFormWithDuplicateValues(): void {
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
        ];

        Form::factory()->create($requestContent);

        $response = $this->withHeaders($this->headers)
            ->postJson(self::CREATE_FORM_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'slug'
                ],
            ]);
    }

    /**
     * @dataProvider formValidationDataProvider
     */
    public function testFormValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $response = $this->withHeaders($this->headers)
            ->postJson(self::CREATE_FORM_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure($expectedJsonStructure);
    }

    public static function formValidationDataProvider(): array
    {
        return [
            'name: long' => [
                'requestContent' => ['name' => str_repeat('a', 51), 'slug' => self::VALID_SLUG],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: short' => [
                'requestContent' => ['name' => 'ab', 'slug' => self::VALID_SLUG],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: invalid chars' => [
                'requestContent' => ['name' => 'Invalid$Name', 'slug' => self::VALID_SLUG],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: missing' => [
                'requestContent' => ['slug' => self::VALID_SLUG],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'slug: long' => [
                'requestContent' => ['name' => self::VALID_NAME, 'slug' => str_repeat('a', 151)],
                'expectedJsonStructure' => ['message', 'errors' => ['slug']],
            ],
            'slug: short' => [
                'requestContent' => ['name' => self::VALID_NAME, 'slug' => 'a'],
                'expectedJsonStructure' => ['message', 'errors' => ['slug']],
            ],
            'slug: invalid chars' => [
                'requestContent' => ['name' => self::VALID_NAME, 'slug' => 'invalid-slug_here$'],
                'expectedJsonStructure' => ['message', 'errors' => ['slug']],
            ],
            'slug: invalid space' => [
                'requestContent' => ['name' => self::VALID_NAME, 'slug' => 'invalid-slug here'],
                'expectedJsonStructure' => ['message', 'errors' => ['slug']],
            ],
            'slug: missing' => [
                'requestContent' => ['name' => self::VALID_NAME],
                'expectedJsonStructure' => ['message', 'errors' => ['slug']],
            ],
        ];
    }
}
