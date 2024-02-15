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

class ShowFieldTest extends TestCase
{
    use RefreshDatabase;

    private $boringTestService;
    private const SHOW_FIELD_ENDPOINT = '/api/collections/%s/fields/%s';

    private const VALID_REQUEST_CONTENT = [
        'name' => 'title',
        'field_type' => Field::TYPE_SHORT_TEXT,
        'is_required' => true
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->boringTestService = $this->app->make(BoringTestService::class);
    }

    public function testShowField(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestField = $this->boringTestService->storeTestField(
            self::VALID_REQUEST_CONTENT,
            $requestCollection->name,
            $requestUser->id,
        );
        $requestUrl = sprintf(self::SHOW_FIELD_ENDPOINT, $requestCollection->name, $requestField->uuid);

        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
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

        $this->assertNotEmpty($fieldResponse['uuid']);
        $this->assertNull($fieldResponse['validation']);
        $this->assertNull($fieldResponse['condition']);
        $this->assertEquals($requestField['is_required'], $fieldResponse['is_required']);
        $this->assertEquals($requestField['field_type'], $fieldResponse['field_type']);
        $this->assertEquals($requestField['name'], $fieldResponse['name']);
    }

    public function testTryShowFieldWithNonExistingUUID(): void {
        $fakeUUID = fake()->uuid();
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::SHOW_FIELD_ENDPOINT, $requestCollection->name, $fakeUUID);

        $this->actingAs($requestUser)
            ->getJson($requestUrl)->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTryShowFieldWithInvalidUUID(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::SHOW_FIELD_ENDPOINT, $requestCollection->name, 'invalid');

        $this->actingAs($requestUser)
            ->getJson($requestUrl)->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testTryShowFieldWithoutAuthentication(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestField = $this->boringTestService->storeTestField(
            self::VALID_REQUEST_CONTENT,
            $requestCollection->name,
            $requestUser->id,
        );
        $requestUrl = sprintf(self::SHOW_FIELD_ENDPOINT, $requestCollection->name, $requestField->uuid);

        $this->getJson($requestUrl)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
