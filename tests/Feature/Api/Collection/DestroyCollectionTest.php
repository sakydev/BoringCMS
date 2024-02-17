<?php

namespace Feature\Api\Collection;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Services\BoringTestService;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DestroyCollectionTest extends TestCase
{
    use RefreshDatabase;

    private BoringTestService $boringTestService;
    private const DESTROY_COLLECTION_ENDPOINT = '/api/collections/%s';

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    public function testDestroyCollection(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $this->boringTestService->storeManyTestFields(3, $requestCollection->name, $requestUser->id);
        $requestUrl = sprintf(self::DESTROY_COLLECTION_ENDPOINT, $requestCollection->name);

        $response = $this->actingAs($requestUser)->deleteJson($requestUrl);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertFalse(Schema::hasTable($requestCollection['name']));
        $this->assertDatabaseMissing('collections', ['id' => $requestCollection->id]);
        $this->assertDatabaseMissing('fields', ['collection_id' => $requestCollection->id]);
    }

    public function testTryDestroyCollectionWithoutAuthentication(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::DESTROY_COLLECTION_ENDPOINT, $requestCollection->name);

        $this->deleteJson($requestUrl)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testTryDestroyNonExistingCollection(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestUrl = sprintf(self::DESTROY_COLLECTION_ENDPOINT, 'invalid');

        $response = $this->actingAs($requestUser)->deleteJson($requestUrl);
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $responseContent = $response->json();
        $this->assertEquals(phrase('item.error.collection.notFound'), $responseContent['errors']);
    }
}
