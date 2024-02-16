<?php

namespace Feature\Api\Form;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
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

    public function testCreateForm(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
        ];

        $response = $this->actingAs($requestUser)
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
                        'created_by',
                        'updated_by',
                        'created',
                        'updated',
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $formResponse = $responseContent['content']['form'];

        $this->assertEquals(phrase('item.success.form.createOne'), $responseContent['message']);

        $this->assertEquals($requestUser->id, $formResponse['created_by']);
        $this->assertEquals($requestContent['name'], $formResponse['name']);
        $this->assertEquals($requestContent['slug'], $formResponse['slug']);
    }

    public function testTryCreateFormWithDuplicateValues(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
            'created_by' => $requestUser->id,
        ];

        Form::factory()->create($requestContent);

        $response = $this->actingAs($requestUser)
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

    public function testTryCreateFormWithoutAuthentication(): void {
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
        ];

        $this->postJson(self::CREATE_FORM_ENDPOINT, $requestContent)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @dataProvider formValidationDataProvider
     */
    public function testFormValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $response = $this
            ->actingAs($requestUser)
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
