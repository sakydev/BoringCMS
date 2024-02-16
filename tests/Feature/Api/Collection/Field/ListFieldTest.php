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

class ListFieldTest extends TestCase
{
    use RefreshDatabase;

    private $boringTestService;
    private const LIST_FIELDS_ENDPOINT = '/api/collections/%s/fields';

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    public function testListFields(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $createdFields = $this->boringTestService->storeManyTestFields(3, $requestCollection->name, $requestUser->id);

        $requestUrl = sprintf(self::LIST_FIELDS_ENDPOINT, $requestCollection->name);

        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'fields' => [
                        '*' => [
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
                        ]
                    ],
                ],
            ]);


        $responseContent = $response->json();
        $fieldsResponse = $responseContent['content']['fields'];

        $field = current($fieldsResponse);

        $this->assertCount($createdFields->count() + 3, $fieldsResponse); // 3 default fields
        $this->assertNotEmpty($field['name']);
        $this->assertNotEmpty($field['uuid']);
        $this->assertNull($field['validation']);
        $this->assertNull($field['condition']);
    }

    public function testTryListFieldsWithoutAuthentication(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);

        $requestUrl = sprintf(self::LIST_FIELDS_ENDPOINT, $requestCollection->name);

        $this->getJson($requestUrl)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
