<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    // use RefreshDatabase;

    public function test_store_an_event(): void
    {
        $testKey = now()->format('YmdHis') . rand(1, 100);
        $eventName = "test-{$testKey}";
        $userId = (int)rand(1, 10000);

        $response = $this->post(route('api.v1.store.event'), [
            'title' => $eventName,
            'user' => $userId,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('status', true);
        $response->assertSee('message');
    }

    public function test_failure_store_an_event_by_invalid_data(): void
    {
        $response = $this->post(route('api.v1.store.event'), [
            'title' => "A",
            'user' => "U",
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonPath('errors.user', ['The user field must be a number.']);
        $response->assertJsonPath('errors.title', ['The title field must be at least 3 characters.']);
        $response->assertSee('message');
    }

    public function test_index_events(): void
    {
        $response = $this->get(route('api.v1.index.events'), [
            'Accept' => 'application/json',
        ]);
        $responseArray = $response->json();

        $lastEvent = Event::latest()->first();

        $response->assertOk();
        $response->assertJsonPath('status', true);
        $response->assertSee('message');
        $this->assertArrayHasKey('data', $responseArray);
        $this->assertArrayHasKey('per_page', $responseArray['data']);
        $this->assertArrayHasKey('next_cursor', $responseArray['data']);
        $this->assertArrayHasKey('next_page_url', $responseArray['data']);
        $this->assertArrayHasKey('prev_cursor', $responseArray['data']);
        $this->assertArrayHasKey('prev_page_url', $responseArray['data']);
        $this->assertEquals($responseArray['data']['data'][0]['name'], $lastEvent->name);
        $this->assertEquals($responseArray['data']['data'][0]['user_id'], $lastEvent->user_id);
        $this->assertLessThanOrEqual($responseArray['data']['per_page'], count($responseArray['data']['data']));
    }

    public function test_index_events_by_proper_filter()
    {
        $lastEvent = Event::latest()->first();

        $anHourAgo = now()->subHour()->toDateTimeString();
        $response = $this->get(route('api.v1.index.events', [
            'from' => $anHourAgo,
            'user_id' => $lastEvent->user_id
        ]), [
            'Accept' => 'application/json',
        ]);
        $responseArray = $response->json();

        $response->assertOk();
        $response->assertJsonPath('status', true);
        $response->assertSee('message');
        $this->assertArrayHasKey('data', $responseArray);
        $this->assertArrayHasKey('per_page', $responseArray['data']);
        $this->assertArrayHasKey('next_cursor', $responseArray['data']);
        $this->assertArrayHasKey('next_page_url', $responseArray['data']);
        $this->assertArrayHasKey('prev_cursor', $responseArray['data']);
        $this->assertArrayHasKey('prev_page_url', $responseArray['data']);
        $this->assertEquals($responseArray['data']['data'][0]['name'], $lastEvent->name);
        $this->assertEquals($responseArray['data']['data'][0]['user_id'], $lastEvent->user_id);
        $this->assertLessThanOrEqual($responseArray['data']['per_page'], count($responseArray['data']['data']));
    }

    public function test_not_found_index_events_by_date_filter()
    {
        $anHourLater = now()->addHour()->toDateTimeString();
        $response = $this->get(route('api.v1.index.events', [
            'from' => $anHourLater
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertNotFound();
        $response->assertJsonPath('status', false);
        $response->assertSee('message');
    }

    public function test_not_found_index_events_by_user_filter()
    {
        $response = $this->get(route('api.v1.index.events', [
            'user_id' => -1
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertNotFound();
        $response->assertJsonPath('status', false);
        $response->assertSee('message');
    }
}
