<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Form;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class ShowFormTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const SHOW_FORM_ENDPOINT = '/api/forms/%s';

    public function testShowForm(): void {
        $requestUser = BoringUser::factory()->createOne();
        $created = Form::factory()->create(['user_id' => $requestUser->id]);

        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, $created->slug);
        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'form' => [
                        'id',
                        'name',
                        'slug',
                        'created',
                        'updated',
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $formContent = $responseContent['content']['form'];

        $this->assertEquals($created->id, $formContent['id']);
        $this->assertEquals($created->name, $formContent['name']);
        $this->assertEquals($created->slug, $formContent['slug']);
    }

    public function testTryShowFormWithoutAuthentication(): void {
        $created = Form::factory()->create();

        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, $created->id);
        $response = $this->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testTryShowFormWithId(): void {
        $requestUser = BoringUser::factory()->createOne();
        $created = Form::factory()->create(['user_id' => $requestUser->id]);

        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, $created->id);
        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTryShowFormWithInvalidSlug(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, 'invalid-slug');

        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
