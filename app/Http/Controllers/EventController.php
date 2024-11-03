<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Jobs\RegisterEvents;
use App\Repositories\Event\EventRepositoryContract;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct(private EventRepositoryContract $eventRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexEventRequest $request)
    {
        try {
            $events = $this->eventRepository->getPaginated($request->input('from', null), $request->input('user_id', null));
            if ($events->count())
                return response()->json([
                    'status' => true,
                    'message' => 'ok',
                    'data' => $events
                ], 200);
            else
                return response()->json([
                    'status' => false,
                    'message' => 'not found'
                ], 404);
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
            $event = RegisterEvents::dispatch($request->input('user'), $request->input('title'), $request->input('payload', []))->onQueue('events');
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
