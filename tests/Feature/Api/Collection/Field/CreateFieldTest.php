<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CreateFieldTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const CREATE_FIELD_ENDPOINT = '/api/collections/%s/fields';
    private const VALID_NAME = 'title';
    private const VALID_REQUEST_CONTENT = [
        'name' => self::VALID_NAME,
        'field_type' => Field::TYPE_SHORT_TEXT,
        'is_required' => true
    ];

    public function testCreateField(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = Collection::factory()->createOne(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::CREATE_FIELD_ENDPOINT, $requestCollection->name);
        $response = $this->actingAs($requestUser)
            ->postJson($requestUrl, self::VALID_REQUEST_CONTENT);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'field' => [
                        'id',
                        'uuid',
                        'collection_id',
                        'name',
                        'field_type',
                        'validation',
                        'condition',
                        'is_required',
                        'created',
                        'updated'
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $fieldResponse = $responseContent['content']['field'];

        $this->assertEquals($requestUser->id, $fieldResponse['id']);
        $this->assertNotEmpty($fieldResponse['uuid']);
        $this->assertTrue($fieldResponse['is_required']);
        $this->assertNull($fieldResponse['validation']);
        $this->assertNull($fieldResponse['condition']);
        $this->assertEquals(self::VALID_REQUEST_CONTENT['field_type'], $fieldResponse['field_type']);
        $this->assertEquals(self::VALID_REQUEST_CONTENT['name'], $fieldResponse['name']);
    }

    public function testTryCreateFieldWithDuplicateValues(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = Collection::factory()->createOne(['created_by' => $requestUser->id]);
        $duplicateField = Field::factory()->createOne(['collection_id' => $requestCollection->id]);
        $requestContent = array_merge(self::VALID_REQUEST_CONTENT, ['name' => $duplicateField->name]);
        $requestUrl = sprintf(self::CREATE_FIELD_ENDPOINT, $requestCollection->name);
        $response = $this->actingAs($requestUser)
            ->postJson($requestUrl, $requestContent);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                'status',
                'errors',
            ]);
    }

    public function testTryCreateFieldWithoutAuthentication(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = Collection::factory()->createOne(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::CREATE_FIELD_ENDPOINT, $requestCollection->name);
        $this->postJson($requestUrl, self::VALID_REQUEST_CONTENT)
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
            ->postJson(self::CREATE_FIELD_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure($expectedJsonStructure);
    }

    public static function formValidationDataProvider(): array
    {
        return [
            'name: long' => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    ['name' => str_repeat('a', 51)]
                ),
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: short' => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    ['name' => 'ab']
                ),
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: invalid chars' => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    ['name' => 'hello world']
                ),
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: missing' => [
                'requestContent' => Arr::except(self::VALID_REQUEST_CONTENT, ['name']),
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'is_required: invalid' => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    ['is_required' => 'yo']
                ),
                'expectedJsonStructure' => ['message', 'errors' => ['is_required']],
            ],
            'is_required: missing' => [
                'requestContent' => Arr::except(self::VALID_REQUEST_CONTENT, ['is_required']),
                'expectedJsonStructure' => ['message', 'errors' => ['is_required']],
            ],
            'field_type: invalid' => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    ['field_type' => 'what']
                ),
                'expectedJsonStructure' => ['message', 'errors' => ['field_type']],
            ],
            'field_type: missing' => [
                'requestContent' => Arr::except(self::VALID_REQUEST_CONTENT, ['field_type']),
                'expectedJsonStructure' => ['message', 'errors' => ['field_type']],
            ],
        ];
    }
}
