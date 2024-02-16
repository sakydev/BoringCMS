<?php

namespace Feature\Api\Collection;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Services\BoringTestService;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ListCollectionTest extends TestCase
{
    use RefreshDatabase;

    private $boringTestService;
    private const LIST_COLLECTIONS_ENDPOINT = '/api/collections';

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    public function testListCollections(): void {
        $requestUser = BoringUser::factory()->createOne();
        $createdCollections = $this->boringTestService->storeManyTestCollections(3, $requestUser->id);

        $requestUrl = sprintf(self::LIST_COLLECTIONS_ENDPOINT);

        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'collections' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'is_hidden',
                            'created_by',
                            'updated_by',
                            'created',
                            'updated',
                        ]
                    ],
                ],
            ]);


        $responseContent = $response->json();
        $collectionsResposne = $responseContent['content']['collections'];

        $collection = current($collectionsResposne);

        $this->assertCount($createdCollections->count(), $collectionsResposne);
        $this->assertNotEmpty($collection['name']);
    }

    public function testTryListFieldsWithoutAuthentication(): void {
        $this->getJson(self::LIST_COLLECTIONS_ENDPOINT)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
