<?php

declare(strict_types=1);

namespace ExercisePromo\Service;

use ExercisePromo\Entity\Promo;
use ExercisePromo\Entity\User;
use ExercisePromo\Repository\PromoRepository;

readonly class PromoService
{
    public function __construct(
        private PromoGenerator $generator,
        private PromoRepository $repo,
    ) {}

    public function findOrCreatePromoForUser(User $user, string $ip): Promo
    {
        $promo = $this->repo->findPromoByUserId($user->id);
        if ($promo) {
            return $promo;
        }

        $promo = new Promo($user->id, $this->generator->generatePromoForUser($user->id), ip2long($ip));
        $this->repo->create($promo);

        return $this->repo->findPromoByUserId($user->id);
    }
}
