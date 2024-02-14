<?php

namespace Feature\Api\Collection\Field;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Field;
use Sakydev\Boring\Services\BoringTestService;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DestroyFieldTest extends TestCase
{
    use RefreshDatabase;

    private BoringTestService $boringTestService;
    private const DESTROY_FIELD_ENDPOINT = '/api/collections/%s/fields/%s';

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

    public function testDestroyField(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestField = $this->boringTestService->storeTestField([], $requestCollection->name, $requestUser->id);
        $requestUrl = sprintf(self::DESTROY_FIELD_ENDPOINT, $requestCollection->name, $requestField->uuid);

        $response = $this->actingAs($requestUser)->deleteJson($requestUrl);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertTrue(Schema::hasTable($requestCollection['name']));
        $this->assertFalse(Schema::hasColumn($requestCollection['name'], $requestField->name));
    }

    public function testTryDestroyFieldWithoutAuthentication(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestField = $this->boringTestService->storeTestField([], $requestCollection->name, $requestUser->id);
        $requestUrl = sprintf(self::DESTROY_FIELD_ENDPOINT, $requestCollection->name, $requestField->uuid);

        $this->deleteJson($requestUrl)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    // TODO: also test invalid UUID syntax
    public function testTryDestroyFieldNonExistingField(): void {
        $fakeUUID = fake()->uuid();
        $requestUser = BoringUser::factory()->createOne();
        $requestCollection = $this->boringTestService->storeTestCollection([], $requestUser->id);
        $requestUrl = sprintf(self::DESTROY_FIELD_ENDPOINT, $requestCollection->name, $fakeUUID);

        $this->actingAs($requestUser)
            ->deleteJson($requestUrl)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
