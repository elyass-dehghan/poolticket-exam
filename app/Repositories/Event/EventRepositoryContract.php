<?php

namespace App\Repositories\Event;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\Request;

interface EventRepositoryContract
{
    public function save(int $userId, string $eventName, array $payload = []): bool;
    public function getPaginated(?string $from, ?int $userId): CursorPaginator|false;
}
