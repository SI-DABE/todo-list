<?php

namespace Tests\Models;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testShouldHaveATitle(): void
    {
        $page = file_get_contents('http://web');

        $this->assertEquals('HTTP/1.1 200 OK', $http_response_header[0]);
        $this->assertMatchesRegularExpression('/<h1>Todo List<\/h1>/', $page);
        $this->assertMatchesRegularExpression('/<a href="\/login">Entrar<\/a>/', $page);
        $this->assertMatchesRegularExpression('/<a href="\/sign-up">Registrar-se<\/a>/', $page);
    }
}
