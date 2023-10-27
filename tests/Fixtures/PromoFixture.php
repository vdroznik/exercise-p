<?php

namespace Tests\Fixtures;

class PromoFixture
{
    public string $table = 'promos';

    public array $records = [
        [
            'id' => 1,
            'user_id' => 1,
            'code' => 'ICAiICIggA',
            'ip_packed' => 3232235522,
        ],
    ];
}
