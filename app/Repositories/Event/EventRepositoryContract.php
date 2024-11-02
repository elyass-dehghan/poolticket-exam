<?php

namespace App\Repositories\Event;

interface EventRepositoryContract
{
    public function save(int $userId, string $eventName, array $payload = []): bool;
    public function getPaginated(): array;
}
