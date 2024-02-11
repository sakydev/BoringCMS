<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Form;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class UpdateFormTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const UPDATE_FORM_ENDPOINT = '/api/forms/%s';
    private const VALID_NAME = 'Updated Form';
    private const VALID_SLUG = 'updated-slug-here';

    public function testUpdateForm(): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $form = Form::factory()->create(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::UPDATE_FORM_ENDPOINT, $form->slug);
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
        ];

        $this->actingAs($requestUser)
            ->patchJson($requestUrl, $requestContent)
            ->assertStatus(Response::HTTP_OK)
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
    }

    public function testTryUpdateFormWithDuplicateName(): void
    {
        $requestUser = BoringUser::factory()->createOne();

        $firstForm = Form::factory()->create(['created_by' => $requestUser->id]);
        $secondForm = Form::factory()->create(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::UPDATE_FORM_ENDPOINT, $firstForm->slug);
        $requestContent = [
            'name' => $secondForm->name,
            'slug' => self::VALID_SLUG,
        ];

        $this->actingAs($requestUser)
            ->patchJson($requestUrl, $requestContent)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
            ]);
    }

    public function testTryUpdateFormWithDuplicateSlug(): void
    {
        $requestUser = BoringUser::factory()->createOne();

        $firstForm = Form::factory()->create(['created_by' => $requestUser->id]);
        $secondForm = Form::factory()->create(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::UPDATE_FORM_ENDPOINT, $firstForm->slug);
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => $secondForm->slug,
        ];

        $this->actingAs($requestUser)
            ->patchJson($requestUrl, $requestContent)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'slug',
                ],
            ]);
    }

    public function testTryUpdateFormWithoutAuthentication(): void
    {
        $form = Form::factory()->create();
        $requestUrl = sprintf(self::UPDATE_FORM_ENDPOINT, $form->slug);
        $requestContent = [
            'name' => self::VALID_NAME,
            'slug' => self::VALID_SLUG,
        ];

        $this->patchJson($requestUrl, $requestContent)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testTryUpdateFormWithChangedSlug(): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $form = Form::factory()->create(['created_by' => $requestUser->id]);
        $requestUrl = sprintf(self::UPDATE_FORM_ENDPOINT, $form->slug);
        $requestContent = [
            'slug' => 'changed-slug',
        ];

        // 1/2 form slug will change after this
        $response = $this->actingAs($requestUser)
            ->patchJson($requestUrl, $requestContent);

        $response->assertStatus(Response::HTTP_OK);

        $responseContent = $response->json();
        $formResponse = $responseContent['content']['form'];

        $this->assertEquals($requestUser->id, $formResponse['created_by']);
        $this->assertEquals($requestUser->id, $formResponse['updated_by']);
        $this->assertEquals($requestContent['slug'], $formResponse['slug']);

        // 2/2 we need to confirm that previous slug becomes unavailable
        $requestContent['slug'] = 'another-changed-slug';
        $this->actingAs($requestUser)
            ->patchJson($requestUrl, $requestContent)
            ->assertStatus(Response::HTTP_NOT_FOUND);

    }

    /**
     * @dataProvider formValidationDataProvider
     */
    public function testFormValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $form = Form::factory()->createOne(['created_by' => $requestUser->id]);
        $requestUrl = sprintf(self::UPDATE_FORM_ENDPOINT, $form->slug);
        $response = $this
            ->actingAs($requestUser)
            ->patchJson($requestUrl, $requestContent);

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
        ];
    }
}
