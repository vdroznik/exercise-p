<?php

namespace ExercisePromo\Entity;

use DateTimeImmutable;

class User
{
    public function __construct(
        public string $username,
        public ?int $id = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['username'],
            $data['id'],
            date_create_immutable($data['created_at']),
            date_create_immutable($data['updated_at']),
        );
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
