<?php

namespace Feature\Api\Form;

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
        $created = Form::factory()->create(['created_by' => $requestUser->id]);

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
        $responseForm = $responseContent['content']['form'];

        $this->assertEquals(phrase('item.success.form.findOne'), $responseContent['message']);

        $this->assertEquals($created->id, $responseForm['id']);
        $this->assertEquals($created->name, $responseForm['name']);
        $this->assertEquals($created->slug, $responseForm['slug']);
    }

    public function testTryShowFormWithoutAuthentication(): void {
        $created = Form::factory()->create();

        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, $created->id);
        $response = $this->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testTryShowFormWithId(): void {
        $requestUser = BoringUser::factory()->createOne();
        $created = Form::factory()->create(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, $created->id);
        $response = $this->actingAs($requestUser)->getJson($requestUrl);
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $responseContent = $response->json();

        $this->assertEquals(phrase('item.error.form.notFound'), $responseContent['errors']);
    }

    public function testTryShowFormWithInvalidSlug(): void {
        $requestUser = BoringUser::factory()->createOne();
        $requestUrl = sprintf(self::SHOW_FORM_ENDPOINT, 'invalid-slug');

        $response = $this->actingAs($requestUser)->getJson($requestUrl);
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $responseContent = $response->json();
        $this->assertEquals(phrase('item.error.form.notFound'), $responseContent['errors']);
    }
}
