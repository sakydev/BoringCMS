<?php

namespace Feature\Api\Accounts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sakydev\Boring\Models\BoringUser;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class RegisterBoringUserTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    private const REGISTER_ACCOUNT_ENDPOINT = '/api/account/register';
    private const VALID_NAME = 'Jon Snow';
    private const VALID_EMAIL = 'snow@wall.com';
    private const VALID_PASSWORD = 'HelloWorld';

    public function testRegisterAccount(): void {
        $requestContent = [
            'name' => self::VALID_NAME,
            'email' =>self::VALID_EMAIL,
            'password' => self::VALID_PASSWORD,
        ];

        $response = $this->postJson(self::REGISTER_ACCOUNT_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_CREATED)
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

        $this->assertEquals($requestContent['name'], $userContent['name']);
        $this->assertEquals($requestContent['email'], $userContent['email']);
        $this->assertNotEmpty($userContent['auth_token']);
    }

    public function testTryRegisterAccountWithDuplicateValues(): void {
        $requestContent = [
            'name' => self::VALID_NAME,
            'email' =>self::VALID_EMAIL,
            'password' => self::VALID_PASSWORD,
        ];

        BoringUser::factory()->create($requestContent);

        $response = $this->postJson(self::REGISTER_ACCOUNT_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email'
                ],
            ]);
    }

    /**
     * @dataProvider registerValidationDataProvider
     */
    public function testRegisterValidation(array $requestContent, array $expectedJsonStructure): void
    {
        $response = $this->postJson(self::REGISTER_ACCOUNT_ENDPOINT, $requestContent);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure($expectedJsonStructure);
    }

    public static function registerValidationDataProvider(): array
    {
        return [
            'name: long' => [
                'requestContent' => [
                    'name' => str_repeat('a', 51),
                    'email' => self::VALID_EMAIL,
                    'password' => self::VALID_PASSWORD,
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: short' => [
                'requestContent' => [
                    'name' => 'ab',
                    'email' => self::VALID_EMAIL,
                    'password' => self::VALID_PASSWORD,
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: invalid chars' => [
                'requestContent' => [
                    'name' => 'Invalid$Name',
                    'email' => self::VALID_EMAIL,
                    'password' => self::VALID_PASSWORD,
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'name: missing' => [
                'requestContent' => [
                    'email' => self::VALID_EMAIL,
                    'password' => self::VALID_PASSWORD,
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['name']],
            ],
            'password: long' => [
                'requestContent' => [
                    'name' => self::VALID_NAME,
                    'email' => self::VALID_EMAIL,
                    'password' =>  str_repeat('a', 101),
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['password']],
            ],
            'password: short' => [
                'requestContent' => [
                    'name' => self::VALID_NAME,
                    'email' => self::VALID_NAME,
                    'password' => 'a',
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['password']],
            ],
            'password: missing' => [
                'requestContent' => [
                    'name' => self::VALID_NAME,
                    'email' => self::VALID_PASSWORD,
                ],
                'expectedJsonStructure' => ['message', 'errors' => ['password']],
            ],
        ];
    }
}
