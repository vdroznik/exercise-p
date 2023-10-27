<?php

declare(strict_types=1);

namespace Tests\Unit\Seßrvice;

use ExercisePromo\Service\PromoGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Random\Engine as RandomEngine;

class PromoGeneratorTest extends TestCase
{
    private PromoGenerator $SUT;

    protected function setUp(): void
    {
        $this->SUT = new PromoGenerator(
            new class implements RandomEngine {
                public function generate(): string
                {
                    return "42";
                }
            }
        );
    }

    #[DataProvider('userIdsProvider')]
    public function testGeneratePromoForUser($userId, $expectedPromo)
    {
        $promo = $this->SUT->generatePromoForUser($userId);
        $this->assertEquals($expectedPromo, $promo, 'Promo code is correct for user_id = 2');
    }

    #[DataProvider('userIdsProvider')]
    public function testReversePromoToUserId($expectedUserId, $promo)
    {
        // below is the reversing algorithm which reconstructs userId from promo code
        // zero byte is added for the base64_decode to return 8 bytes, required by unpack("Q")
        $restored = unpack("Q", base64_decode($promo) . "\0")[1];
        $restoredBin = str_pad(decbin($restored), 56, '0', STR_PAD_LEFT);
        $resultBin = '';
        for ($i = 0; $i < 24; $i++) {
            $resultBin .= $restoredBin[55-$i*2];
        }
        for ($i = 24; $i < 32; $i++) {
            $resultBin .= $restoredBin[31-$i];
        }
        $resn = bindec($resultBin);

        $this->assertEquals($expectedUserId, $resn);
    }

    public static function userIdsProvider()
    {
        return [
            'small id with many binary zeroes' => [2, 'ICIgIiAiQA'],
            'alternating binary 101010' => [2_863_311_530, 'MTMxMzEzVQ'],
            'maxint, all binary ones' => [4_294_967_295, 'dXd1d3V3/w']
        ];
    }
}
