<?php

declare(strict_types=1);

namespace ExercisePromo\Service;

use Doctrine\DBAL\Connection;
use ExercisePromo\Entity\Promo;
use ExercisePromo\Entity\User;
use ExercisePromo\Repository\IpsRepository;
use ExercisePromo\Repository\PromoRepository;

readonly class PromoService
{
    public function __construct(
        private PromoGenerator $generator,
        private PromoRepository $repo,
        private IpsRepository $ipsRepo,
        private Connection $dbal
    ) {}

    public function findOrCreatePromoForUser(User $user, int $ip): Promo
    {
        $promo = $this->repo->findPromoByUserId($user->id);
        if ($promo) {
            return $promo;
        }

        $promo = new Promo($user->id, $this->generator->generatePromoForUser($user->id), $ip);

        $this->dbal->beginTransaction();
        $this->repo->create($promo);
        $this->ipsRepo->incrementCountForIp($ip);
        $this->dbal->commit();

        return $this->repo->findPromoByUserId($user->id);
    }
}
