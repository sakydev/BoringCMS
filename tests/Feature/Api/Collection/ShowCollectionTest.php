<?php

namespace Feature\Api\Collection;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Services\BoringTestService;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowCollectionTest extends TestCase
{
    use RefreshDatabase;

    private $boringTestService;
    private const SHOW_COLLECTION_ENDPOINT = '/api/collections/%s';

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    public function testShowField(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::SHOW_COLLECTION_ENDPOINT, $requestCollection->name);

        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
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

        $this->assertEquals(phrase('item.success.collection.findOne'), $responseContent['message']);

        $this->assertEquals($requestCollection->name, $collectionResponse['name']);
        $this->assertEquals($requestUser->id, $collectionResponse['created_by']);
    }

    public function testTryShowCollectionWithNonExistingName(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestUrl = sprintf(self::SHOW_COLLECTION_ENDPOINT, 'invalid');

        $response = $this->actingAs($requestUser)->getJson($requestUrl);
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $responseContent = $response->json();
        $this->assertEquals(phrase('item.error.collection.notFound'), $responseContent['errors']);
    }

    public function testTryShowCollectionWithoutAuthentication(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::SHOW_COLLECTION_ENDPOINT, $requestCollection->name);

        $this->getJson($requestUrl)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
