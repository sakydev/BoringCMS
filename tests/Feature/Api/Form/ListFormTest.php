<?php

namespace Feature\Api\Forms;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Models\Form;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class ListFormTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const LIST_FORMS_ENDPOINT = '/api/forms?page=%d&limit=%d';

    public function testListForms(): void
    {
        $requestUser = BoringUser::factory()->createOne();
        $createdForms = Form::factory()->count(3)->create(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::LIST_FORMS_ENDPOINT, 1, 10);
        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'forms' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'created_by',
                            'updated_by',
                            'created',
                            'updated',
                        ],
                    ],
                ],
            ])
            ->assertJsonCount(3, 'content.forms');

        $responseContent = $response->json();
        $responseForms = $responseContent['content']['forms'];

        $this->assertEquals(phrase('item.success.form.findMany'), $responseContent['message']);

        foreach ($createdForms as $index => $createdForm) {
            $this->assertEquals($createdForm->id, $responseForms[$index]['id']);
            $this->assertEquals($createdForm->name, $responseForms[$index]['name']);
            $this->assertEquals($createdForm->slug, $responseForms[$index]['slug']);
            $this->assertEquals($createdForm->created_by, $requestUser->id);
        }
    }

    // create 5 forms, set limit to 2 and page to 3: ensure only 1 is listed then
    public function testListFormsPagination(): void
    {
        $requestUser = BoringUser::factory()->createOne();
        Form::factory()->count(5)->create(['created_by' => $requestUser->id]);

        $requestUrl = sprintf(self::LIST_FORMS_ENDPOINT, 3, 2);
        $response = $this->actingAs($requestUser)->getJson($requestUrl);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'forms' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'created_by',
                            'updated_by',
                            'created',
                            'updated',
                        ],
                    ],
                ],
            ])
            ->assertJsonCount(1, 'content.forms');
    }

    public function testTryListFormsWithoutAuthentication(): void
    {
        $response = $this->getJson(self::LIST_FORMS_ENDPOINT);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
