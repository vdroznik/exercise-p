<?php

declare(strict_types=1);

namespace ExercisePromo\Service;

use Random\Engine as RandomEngine;
use Random\Randomizer;

class PromoGenerator
{
    private readonly Randomizer $randomizer;

    public function __construct(RandomEngine $randomEngine)
    {
        $this->randomizer = new Randomizer($randomEngine);
    }

    public function generatePromoForUser(int $userId): string
    {
        // examples are for $userId = 2
        $bin = decbin($userId);
        $bin = str_pad($bin, 32, '0', STR_PAD_LEFT); // string(32) "00000000000000000000000000000010"

        // randomize 56 bits
        $rnd = $this->randomizer->getInt(0, 72057594037927935);
        $target = str_pad(decbin($rnd), 56, '0', STR_PAD_LEFT); // string(56) "01000000101000101000101010001010100000000000000010000000"

        // inject userId 32 bits into 56 bits target value and distribute like this
        // reversed, every 2nd bit, last 8 bits consequently
        //            "00000000 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 1 0"
        // string(56) "01000000101000101000101010001010100000000000000010000000"
        for ($i = 0; $i < 24; $i++) {
            $target[55 - $i * 2] = $bin[$i];
        }
        for ($i = 24; $i < 32; $i++) {
            $target[31 - $i] = $bin[$i];
        }
        $dec = bindec($target);

        $packed = pack("Q", $dec); // pack as 8 chars string
        $promo = substr(base64_encode($packed), 0, 10); // 8 chars are encoded as 12 chars base64 string, take 10

        return $promo;
    }
}
