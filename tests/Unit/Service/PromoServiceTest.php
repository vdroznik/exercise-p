<?php

declare(strict_types=1);

namespace Unit\Service;

use ExercisePromo\Entity\Promo;
use ExercisePromo\Entity\User;
use ExercisePromo\Repository\PromoRepository;
use ExercisePromo\Service\PromoGenerator;
use ExercisePromo\Service\PromoService;
use PHPUnit\Framework\TestCase;

class PromoServiceTest extends TestCase
{
    public function testFindOrCreatePromoForUserCreate()
    {
        $userId = 2;

        $exprectedPromo = new Promo($userId, 'abcde12345', 0);
        $promoRepoMock = $this->getMockBuilder(PromoRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $promoRepoMock->expects($this->exactly(2))
            ->method('findPromoByUserId')
            ->with($userId)
            ->willReturnOnConsecutiveCalls(null, $exprectedPromo);
        $promoRepoMock->expects($this->once())
            ->method('create')
            ->willReturn(true);

        $SUT = new PromoService(
            $this->getMockBuilder(PromoGenerator::class)
                ->disableOriginalConstructor()
                ->getMock(),
            $promoRepoMock
        );

        $user = new User('test', $userId);
        $ip = '192.168.0.1';
        $promo = $SUT->findOrCreatePromoForUser($user, $ip);

        $this->assertEquals($exprectedPromo, $promo);
    }

    public function testFindOrCreatePromoForUserFind()
    {
        $userId = 3;

        $exprectedPromo = new Promo($userId, 'abcde12345', 0);
        $promoRepoMock = $this->getMockBuilder(PromoRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $promoRepoMock->expects($this->once())
            ->method('findPromoByUserId')
            ->with($userId)
            ->willReturn($exprectedPromo);
        $promoRepoMock->expects($this->never())
            ->method('create');

        $SUT = new PromoService(
            $this->getMockBuilder(PromoGenerator::class)
                ->disableOriginalConstructor()
                ->getMock(),
            $promoRepoMock
        );

        $user = new User('test', $userId);
        $ip = '192.168.0.1';
        $promo = $SUT->findOrCreatePromoForUser($user, $ip);

        $this->assertEquals($exprectedPromo, $promo);
    }
}
