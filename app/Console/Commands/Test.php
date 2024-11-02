<?php

namespace App\Console\Commands;

use App\Jobs\RegisterEvents;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command used for quick tests.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $event = RegisterEvents::dispatch(1, 'test')->onQueue('events')->onConnection('redis');
        dump($event);
    }
}
