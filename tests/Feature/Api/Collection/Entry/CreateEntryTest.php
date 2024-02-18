<?php

namespace Feature\Api\Collection\Entry;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Collection;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Services\BoringTestService;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CreateEntryTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private BoringTestService $boringTestService;
    private const CREATE_ENTRY_ENDPOINT = '/api/collections/%s/entries';
    private const VALID_ENTRY_FIELD_VALUE = 'Sample';
    private const VALID_FIELD_CONTENT = [
        'name' => 'title',
        'field_type' => Field::TYPE_SHORT_TEXT,
        'is_required' => true,
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    public function testCreateEntryMinimal(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestField = $this->boringTestService->storeTestField(
            self::VALID_FIELD_CONTENT,
            $requestCollection->name,
            $requestUser->id,
        );

        $requestUrl = sprintf(self::CREATE_ENTRY_ENDPOINT, $requestCollection->name);
        $response = $this->actingAs($requestUser)
            ->postJson($requestUrl, [$requestField->name => self::VALID_ENTRY_FIELD_VALUE]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'entry' => [
                        'id',
                        $requestField->name,
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $responseContent = $response->json();
        /*$collectionResponse = $responseContent['content']['collection'];

        $this->assertEquals(phrase('item.success.collection.createOne'), $responseContent['message']);

        $this->assertEquals($requestUser->id, $collectionResponse['id']);
        $this->assertFalse($collectionResponse['is_hidden']);
        $this->assertEquals(self::VALID_REQUEST_CONTENT['name'], $collectionResponse['name']);

        // check default created table
        $this->assertTrue(Schema::hasTable($collectionResponse['name']));
        $this->assertTrue(Schema::hasColumn($collectionResponse['name'], 'id'));
        $this->assertTrue(Schema::hasColumn($collectionResponse['name'], 'created_at'));
        $this->assertTrue(Schema::hasColumn($collectionResponse['name'], 'updated_at'));*/

        // check fields have been populated
        $this->assertDatabaseCount($requestCollection->name, 1);
    }

    /*public function testTryCreateFieldWithDuplicateValues(): void {
        $requestUser = BoringUser::factory()->createOne();
        $duplicateCollection = Collection::factory()->createOne(['created_by' => $requestUser->id]);
        $requestContent = array_merge(self::VALID_REQUEST_CONTENT, ['name' => $duplicateCollection->name]);

        $response = $this->actingAs($requestUser)
            ->postJson(self::CREATE_COLLECTION_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);
    }

    public function testTryCreateFieldWithoutAuthentication(): void {
        $this->postJson(self::CREATE_COLLECTION_ENDPOINT, self::VALID_REQUEST_CONTENT)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }*/

    /**
     * @dataProvider collectionValidationDataProvider
     */
    /*public function testCollectionValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $response = $this
            ->actingAs($requestUser)
            ->postJson(self::CREATE_COLLECTION_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure($expectedJsonStructure);
    }

    public static function collectionValidationDataProvider(): array
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
            'is_hidden: invalid' => [
                'requestContent' => array_merge(
                    self::VALID_REQUEST_CONTENT,
                    ['is_hidden' => 'yo']
                ),
                'expectedJsonStructure' => ['message', 'errors' => ['is_hidden']],
            ],
        ];
    }*/
}
