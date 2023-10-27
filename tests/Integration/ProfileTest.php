<?php

namespace Tests\Integration;

use Tests\Fixtures\UserFixture;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;

class ProfileTest extends TestCase
{
    use ContainerTestTrait;
    use HttpTestTrait;
    use DatabaseTestTrait;

    protected App $app;

    protected function setUp(): void
    {
        static $isInitialized = false;

        $this->app = $GLOBALS['app'];
        $this->setUpContainer($GLOBALS['container']);

        if (!$isInitialized) {
            $this->truncateTables();
            $this->insertFixtures([UserFixture::class]);
            // login
            $request = $this->createFormRequest('POST', '/check-login', ['username' => 'bekker']);
            $this->app->handle($request);
            $isInitialized = true;
        }
    }

    public function testProfile()
    {
        $request = $this->createRequest('GET', '/profile');
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseContains($response, 'Welcome, bekker!');
    }

    public function testLogout()
    {
        $request = $this->createRequest('GET', '/profile/logout');
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $response->getHeader('Location')[0], 'It redirects to front page on logout');
    }
}
