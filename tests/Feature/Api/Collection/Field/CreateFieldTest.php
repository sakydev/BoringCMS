<?php

namespace Feature\Api\Collection\Field;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Services\BoringTestService;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateFieldTest extends TestCase
{
    use RefreshDatabase;

    private $boringTestService;
    private const CREATE_FIELD_ENDPOINT = '/api/collections/%s/fields';

    private const VALID_NAME = 'title';

    private const VALID_REQUEST_CONTENT = [
        'name' => self::VALID_NAME,
        'field_type' => Field::TYPE_SHORT_TEXT,
        'is_required' => true
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    /**
     * @dataProvider fieldTypeDataProvider
     */
    public function testCreateFieldWithDifferentTypes(array $requestContent): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::CREATE_FIELD_ENDPOINT, $requestCollection->name);

        $response = $this->actingAs($requestUser)
            ->postJson($requestUrl, $requestContent);

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
                        'created_by',
                        'updated_by',
                        'created',
                        'created',
                        'updated'
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $fieldResponse = $responseContent['content']['field'];

        $this->assertEquals(phrase('item.success.field.createOne'), $responseContent['message']);

        $this->assertNotEmpty($fieldResponse['uuid']);
        $this->assertNull($fieldResponse['validation']);
        $this->assertNull($fieldResponse['condition']);
        $this->assertEquals($requestContent['is_required'], $fieldResponse['is_required']);
        $this->assertEquals($requestContent['field_type'], $fieldResponse['field_type']);
        $this->assertEquals($requestContent['name'], $fieldResponse['name']);

        $this->assertTrue(Schema::hasTable($requestCollection['name']));

        $tableColumns = Schema::getColumns($requestCollection['name']);
        $columnDetails = Arr::first($tableColumns, function ($value) use ($requestContent) {
            return Arr::get($value, 'name') === $requestContent['name'];
        });

        $this->assertNotEmpty($columnDetails);
        $this->assertNull($columnDetails['default']);
        $this->assertEquals($columnDetails['nullable'], !$requestContent['is_required']);
        $this->assertEquals(
            $columnDetails['type'],
            Field::SUPPORTED_TYPES_COLUMN_MAPPING[$requestContent['field_type']]
        );
    }

    public function testTryCreateFieldWithDuplicateValues(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $duplicateField = $this->boringTestService->storeTestField(
            self::VALID_REQUEST_CONTENT,
            $requestCollection->name,
            $requestUser->id,
        );
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
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::CREATE_FIELD_ENDPOINT, $requestCollection->name);

        $this->postJson($requestUrl, self::VALID_REQUEST_CONTENT)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @dataProvider fieldValidationDataProvider
     */
    public function testFieldValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::CREATE_FIELD_ENDPOINT, $requestCollection->name);

        $response = $this
            ->actingAs($requestUser)
            ->postJson($requestUrl, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure($expectedJsonStructure);
    }

    public static function fieldTypeDataProvider(): array
    {
        return [
            'field_type: ' . Field::TYPE_SHORT_TEXT => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_LONG_TEXT => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => true,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_MARKDOWN => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_RICHTEXT => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_FLOAT => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_INTEGER => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_JSON => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
            'field_type: ' . Field::TYPE_TIMESTAMP => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    [
                        'field_type' => Field::TYPE_SHORT_TEXT,
                        'is_required' => false,
                    ]
                ),
            ],
        ];
    }

    public static function fieldValidationDataProvider(): array
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
