<?php

namespace ExercisePromo\Entity;

use DateTimeImmutable;

class Promo
{
    public function __construct(
        public int $userId,
        public string $code,
        public int $ipPacked,
        public ?int $id = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['user_id'],
            $data['code'],
            $data['ip_packed'],
            $data['id'],
            date_create_immutable($data['created_at']),
            date_create_immutable($data['updated_at']),
        );
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'code' => $this->code,
            'ip_packed' => $this->ipPacked,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
