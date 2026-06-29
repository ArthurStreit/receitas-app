<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testRootRedirectsToLoginScreen()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
