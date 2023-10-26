<?php

namespace Integration;

use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;

class ProfileTest extends TestCase
{
    use ContainerTestTrait;
    use HttpTestTrait;

    protected App $app;

    protected function setUp(): void
    {
        $this->app = $GLOBALS['app'];
        $this->setUpContainer($GLOBALS['container']);
    }

    public function testAuthorization()
    {
        $request = $this->createFormRequest('POST', '/check-login', ['username' => 'aaron']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/profile', $response->getHeader('Location')[0], 'It redirects to profile upon authorization');
    }

    /**
     * @depends testAuthorization
     */
    public function testProfile()
    {
        $request = $this->createRequest('GET', '/profile');
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseContains($response, 'Welcome, aaron!');
    }

    /**
     * @depends testAuthorization
     */
    public function testOpenPromo()
    {
        $request = $this->createRequest('GET', '/profile/getpromo', ['REMOTE_ADDR' => '192.168.0.2']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=', substr($response->getHeader('Location')[0], 0, -10), 'It redirects to external site with promo code');
    }

    /**
     * @depends testAuthorization
     */
    public function testLogout()
    {
        $request = $this->createRequest('GET', '/profile/logout');
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $response->getHeader('Location')[0], 'It redirects to front page on logout');
    }
}
