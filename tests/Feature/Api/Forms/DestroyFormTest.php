<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Form;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class DestroyFormTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const DELETE_FORM_ENDPOINT = '/api/forms/%s';

    public function testDestroyForm(): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $createdForm = Form::factory()->create(['user_id' => $requestUser->id]);

        $requestUrl = sprintf(self::DELETE_FORM_ENDPOINT, $createdForm->slug);
        $response = $this->actingAs($requestUser)->deleteJson($requestUrl);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('forms', ['id' => $createdForm->id]);
    }

    public function testTryDestroyFormWithoutAuthentication(): void
    {
        $createdForm = Form::factory()->create();

        $requestUrl = sprintf(self::DELETE_FORM_ENDPOINT, $createdForm->slug);
        $response = $this->deleteJson($requestUrl);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testTryDestroyOtherUserForm(): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $formUser = BoringUser::factory()->createOne();
        $createdForm = Form::factory()->create(['user_id' => $formUser->id]);

        $requestUrl = sprintf(self::DELETE_FORM_ENDPOINT, $createdForm->slug);
        $response = $this->actingAs($requestUser)->deleteJson($requestUrl);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testTryDestroyNonExistingForm(): void
    {
        $requestUser = BoringUser::factory()->createOne();

        $requestUrl = sprintf(self::DELETE_FORM_ENDPOINT, 'non-existing-slug');
        $response = $this->actingAs($requestUser)->deleteJson($requestUrl);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
