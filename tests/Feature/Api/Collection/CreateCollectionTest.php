<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class CreateCollectionTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const CREATE_COLLECTION_ENDPOINT = '/api/collections';

    private const VALID_NAME = 'posts';

    private const VALID_DESCRIPTION = 'Hello';

    private const VALID_REQUEST_CONTENT = [
        'name' => self::VALID_NAME,
        'description' => self::VALID_DESCRIPTION,
        'is_required' => true
    ];

    public function testCreateCollection(): void {
        $requestUser = BoringUser::factory()->createOne();

        $response = $this->actingAs($requestUser)
            ->postJson(self::CREATE_COLLECTION_ENDPOINT, self::VALID_REQUEST_CONTENT);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'collection' => [
                        'id',
                        'name',
                        'description',
                        'is_hidden',
                        'created_by',
                        'updated_by',
                        'created',
                        'updated',
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $collectionResponse = $responseContent['content']['collection'];

        $this->assertEquals($responseContent['message'], phrase('item.success.createOne'));

        $this->assertEquals($requestUser->id, $collectionResponse['id']);
        $this->assertFalse($collectionResponse['is_hidden']);
        $this->assertEquals(self::VALID_REQUEST_CONTENT['name'], $collectionResponse['name']);

        // check default created table
        $this->assertTrue(Schema::hasTable($collectionResponse['name']));
        $this->assertTrue(Schema::hasColumn($collectionResponse['name'], 'id'));
        $this->assertTrue(Schema::hasColumn($collectionResponse['name'], 'created_at'));
        $this->assertTrue(Schema::hasColumn($collectionResponse['name'], 'updated_at'));

        // check fields have been populated
        $this->assertDatabaseCount('fields', 3);
    }

    public function testTryCreateFieldWithDuplicateValues(): void {
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
    }

    /**
     * @dataProvider collectionValidationDataProvider
     */
    public function testCollectionValidation(array $requestContent, array $expectedJsonStructure): void
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
    }
}
