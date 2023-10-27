<?php

namespace Tests\Integration;

use Tests\Fixtures\IpFixture;
use Tests\Fixtures\PromoFixture;
use Tests\Fixtures\UserFixture;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;

class IpLimitTest extends TestCase
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
            $this->insertFixtures([UserFixture::class, IpFixture::class, PromoFixture::class]);
            $isInitialized = true;
        }
    }

    public function testItDoesntGeneratePromo()
    {
        // login with new user
        $request = $this->createFormRequest('POST', '/check-login', ['username' => 'aaron']);
        $this->app->handle($request);

        $this->assertTableRowCount(1, 'promos', 'One promo already exists');
        $request = $this->createRequest('GET', '/profile/getpromo', ['REMOTE_ADDR' => '192.168.0.1']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/profile', $response->getHeader('Location')[0], "It doesn't generate promo when ip limit hit");
        $this->assertTableRowCount(1, 'promos', 'No promos created');
    }

    public function testItGivesStoredPromoWithIpLimitHit()
    {
        // login
        $request = $this->createFormRequest('POST', '/check-login', ['username' => 'bekker']);
        $this->app->handle($request);

        $this->assertTableRowCount(1, 'promos', 'Promo exists');
        $request = $this->createRequest('GET', '/profile/getpromo', ['REMOTE_ADDR' => '192.168.0.1']);
        $response = $this->app->handle($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com/?query=ICAiICIggA', $response->getHeader('Location')[0], 'It redirects to external site with earlier generated promo code even with ip limit hit');
        $this->assertTableRowCount(1, 'promos', 'Promo is reused');
    }
}
