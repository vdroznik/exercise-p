<?php

namespace Tests\Fixtures;

class IpFixture
{
    public string $table = 'ips';

    public array $records = [
        [
            'ip' => 3232235521, // 192.168.0.1
            'cnt' => 1000,
        ],
    ];
}
