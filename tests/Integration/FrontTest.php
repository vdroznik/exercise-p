<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;

class FrontTest extends TestCase
{
    use ContainerTestTrait;
    use HttpTestTrait;

    protected App $app;

    protected function setUp(): void
    {
        $this->app = $GLOBALS['app'];
        $this->setUpContainer($GLOBALS['container']);
    }

    public function testFrontPage()
    {
        $request = $this->createRequest('GET', '/');
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseContains($response, 'Welcome');
        $this->assertResponseContains($response, 'Enter your username to signup or login');
    }
}
