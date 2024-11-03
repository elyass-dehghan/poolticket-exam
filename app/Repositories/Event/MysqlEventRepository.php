<?php

namespace App\Repositories\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Contracts\Pagination\CursorPaginator;

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

    /**
     * @throws Exception
     */
    public function getPaginated(?string $from, ?int $userId): CursorPaginator|false
    {
        try {
            $query = DB::table('events');

            if ($from !== null)
                $query->where('created_at', '>=', $from);
            if ($userId !== null)
                $query->where('user_id', $userId);

            return $query->orderBy('id', 'desc')->cursorPaginate(2);
        } catch (Exception $exception) {
            // capture exception
        }

        return false;
    }
}
