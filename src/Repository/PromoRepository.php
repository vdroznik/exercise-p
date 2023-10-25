<?php

declare(strict_types=1);

namespace ExercisePromo\Repository;

use Doctrine\DBAL\Connection;
use ExercisePromo\Entity\Promo;

class PromoRepository
{
    public function __construct(
        private Connection $dbal
    ) {}

    public function findPromoByUserId(int $userId): ?Promo
    {
        $result = $this->dbal->fetchAssociative('SELECT * FROM promos WHERE user_id = :user_id', ['user_id' => $userId]);

        if (!$result) {
            return null;
        }

        return Promo::fromArray($result);
    }

    public function create(Promo $promo): bool
    {
        $result = $this->dbal->insert('promos', array_filter($promo->asArray()));

        return ((int) $result) > 0;
    }
}
