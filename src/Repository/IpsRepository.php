<?php

declare(strict_types=1);

namespace ExercisePromo\Repository;

use Doctrine\DBAL\Connection;

class IpsRepository
{
    public function __construct(
        private Connection $dbal
    ) {}

    public function getCountByIp(int $ip): int
    {
        $result = $this->dbal->fetchOne('SELECT cnt FROM ips WHERE ip = :ip', ['ip' => $ip]);

        return (int) $result;
    }

    public function incrementCountForIp(int $ip): bool
    {
        $affected = $this->dbal->executeStatement('INSERT INTO ips SET ip = :ip, cnt = 1 ON DUPLICATE KEY UPDATE cnt = cnt + 1', ['ip' => $ip]);

        return ((int) $affected) > 0;
    }
}
