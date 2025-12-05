<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Routing\Controller as BaseController;


class AttendeeController extends BaseController
{
    use AuthorizesRequests;
    use CanLoadRelationships;
    private array $relations = ['user', 'event'];

    public function __construct()
    {
        $this->authorizeResource(Attendee::class, 'attendee');
    }


    public function index(Event $event)
    {
        //Gate::authorize('viewAny', Attendee::class);

        $attendees = $this->loadRelationships($event->attendees()->getQuery());
        return AttendeeResource::collection($attendees->get()/*paginate()*/);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        //Gate::authorize('create', $event);

        $data = [
            'user_id' => $request->user()->id, 
        ];

        $attendee = $this->loadRelationships($event->attendees()->create($data));
        return new AttendeeResource($attendee);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $event, Attendee $attendee)
    {
        //Gate::authorize('view', $attendee);

        return new AttendeeResource($this->loadRelationships($attendee));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event, Attendee $attendee)
    {
        //Gate::authorize('delete', $attendee);

        $attendee->delete();
        return response()->json(status:204);
    }
}
