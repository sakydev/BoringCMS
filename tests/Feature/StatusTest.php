<?php

namespace Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StatusTest extends TestCase
{
    public function testUptimeStatus(): void
    {
        $this->get('/status/uptime')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['item', 'message', 'time']);
    }
}
