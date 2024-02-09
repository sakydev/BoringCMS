<?php

namespace Feature\Api\Accounts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class LoginBoringUserTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const LOGIN_ACCOUNT_ENDPOINT = '/api/account/login';
    private const VALID_EMAIL = 'snow@wall.com';
    private const VALID_PASSWORD = 'snowworld';

    public function testLoginAccount(): void {
        $requestContent = [
            'email' => self::VALID_EMAIL,
            'password' => self::VALID_PASSWORD,
        ];

        BoringUser::factory()->create($requestContent);

        $response = $this->postJson(self::LOGIN_ACCOUNT_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'content' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'auth_token',
                        'created',
                        'updated',
                    ],
                ],
            ]);

        $responseContent = $response->json();
        $userContent = $responseContent['content']['user'];

        $this->assertEquals($requestContent['email'], $userContent['email']);
        $this->assertNotEmpty($userContent['auth_token']);
    }

    public function testLoginAccountWithInvalidCredentials(): void {
        $requestContent = [
            'email' =>self::VALID_EMAIL,
            'password' => self::VALID_PASSWORD,
        ];

        BoringUser::factory()->create($requestContent);

        $requestContent['password'] = time() . '_hello';
        $response = $this->postJson(self::LOGIN_ACCOUNT_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'status',
                'errors',
            ]);
    }

    /**
     * @dataProvider loginValidationDataProvider
     */
    public function testLoginValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $response = $this->postJson(self::LOGIN_ACCOUNT_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure($expectedJsonStructure);
    }

    public static function loginValidationDataProvider(): array
    {
        return [
            'password: long' => [
                'requestContent' => [
                    'email' => self::VALID_EMAIL,
                    'password' =>  str_repeat('a', 101),
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['password']],
            ],
            'password: short' => [
                'requestContent' => [
                    'email' => self::VALID_EMAIL,
                    'password' => 'a',
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['password']],
            ],
            'password: missing' => [
                'requestContent' => [
                    'email' => self::VALID_PASSWORD,
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['password']],
            ],
        ];
    }
}
