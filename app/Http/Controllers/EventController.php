<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Jobs\RegisterEvents;
use App\Repositories\Event\EventRepositoryContract;
use Exception;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct(private EventRepositoryContract $eventRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $events = $this->eventRepository->getPaginated();
            if ($events['status'])
                return response()->json([
                    'status' => true,
                    'message' => 'ok',
                    'data' => $events
                ], 200);
        } catch (Exception $exception) {
            // capture exception
        }

        return response()->json([
            'status' => false,
            'message' => 'try again later'
        ], 503);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        try {
            $event = RegisterEvents::dispatch(1, 'test')->onQueue('events')->onConnection('redis');
            if ($event)
                return response()->json([
                    'status' => true,
                    'message' => 'ok',
                ], 201);
        } catch (Exception $exception) {
            // capture exception
        }

        return response()->json([
            'status' => false,
            'message' => 'try again later'
        ], 503);
    }
}
