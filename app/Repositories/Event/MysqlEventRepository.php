<?php

namespace App\Repositories\Event;

use Illuminate\Support\Facades\DB;
use Exception;

class MysqlEventRepository implements EventRepositoryContract
{
    public function save(int $userId, string $eventName, array $payload = []): bool
    {
        $now = date('Y-m-d H:i:s');
        try {
            if (DB::table('events')->insert([
                'user_id' => $userId,
                'name' => $eventName,
                'payload' => $payload === [] ? null : json_encode($payload),
                'created_at' => $now,
                'updated_at' => $now
            ]))
                return true;
        } catch (Exception $exception) {
            // capture exception
        }

        return false;
    }

    public function getPaginated(): array
    {
        // TODO: Implement getPaginated() method.
    }
}
