<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;

class AuthorizationTest extends TestCase
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
            $isInitialized = true;
        }
    }

    public function testRegister()
    {
        $this->assertTableRowCount(0, 'users', 'No users initially');
        $request = $this->createFormRequest('POST', '/check-login', ['username' => 'aaron']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/profile', $response->getHeader('Location')[0], 'It redirects to profile upon registration');

        $this->assertTableRowCount(1, 'users', 'User is created');
    }

    /**
     * @depends testRegister
     */
    public function testLogin()
    {
        $this->assertTableRowCount(1, 'users', 'User exists');
        $request = $this->createFormRequest('POST', '/check-login', ['username' => 'aaron']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/profile', $response->getHeader('Location')[0], 'It redirects to profile on login');

        $this->assertTableRowCount(1, 'users', 'User is reused');
    }
}
