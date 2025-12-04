<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();
        return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $data = [
            'user_id' => 1, //arbitrary atm
        ];

        $attendee = $event->attendees()->create($data);

        return new AttendeeResource($attendee);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event, Attendee $attendee)
    {
        $attendee->delete();

        return response()->json(status:204);
    }
}
