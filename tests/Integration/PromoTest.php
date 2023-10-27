<?php

namespace Tests\Integration;

use Tests\Fixtures\UserFixture;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;

class PromoTest extends TestCase
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

    public function testOpenNewPromo()
    {
        $this->assertTableRowCount(0, 'promos', 'No promos initially');
        $request = $this->createRequest('GET', '/profile/getpromo', ['REMOTE_ADDR' => '192.168.0.2']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=ICIgIiAigA', $response->getHeader('Location')[0], 'It redirects to external site with newly generated promo code');
        $this->assertTableRowCount(1, 'promos', 'Generated promo is stored');
    }

    /**
     * @depends testOpenNewPromo
     */
    public function testOpenNewPromoAgainReusesIt()
    {
        $this->assertTableRowCount(1, 'promos', 'Promo exists');
        $request = $this->createRequest('GET', '/profile/getpromo', ['REMOTE_ADDR' => '192.168.0.2']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=ICIgIiAigA', $response->getHeader('Location')[0], 'It redirects to external site with earlier generated promo code');
        $this->assertTableRowCount(1, 'promos', 'Promo is reused');
    }
}
