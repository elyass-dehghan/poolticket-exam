<?php

namespace App\Jobs;

use App\Repositories\Event\EventRepositoryContract;
use App\Repositories\Event\MysqlEventRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class RegisterEvents implements ShouldQueue
{
    use Queueable;

    private EventRepositoryContract $eventRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $userId, private string $eventName, private array $payload = [])
    {
        $this->eventRepository = new MysqlEventRepository();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->eventRepository->save($this->userId, $this->eventName, $this->payload);
    }
}
