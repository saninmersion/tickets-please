<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::query()->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::query()->findOrFail($request->input('data.relationships.author.data.id'));
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User not found.', [
                'error' => 'The provided user id does not exist.'
            ]);
        }

        $model = [
            'title'       => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status'      => $request->input('data.attributes.status'),
            'user_id'     => $request->input('data.relationships.author.data.id'),
        ];

        return new TicketResource(Ticket::query()->create($model));
    }

    /**
     * Display the specified resource.
     */
    public function show($ticketId)
    {
        try {
            $ticket = Ticket::query()->findOrFail($ticketId);
            if ( $this->include('author') ) {
                return new TicketResource($ticket->load('user'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticketId) {}

    public function replace(ReplaceTicketRequest $request, $ticketId)
    {
        try {
            $ticket = Ticket::query()->findOrFail($ticketId);

            $model = [
                'title'       => $request->input('data.attributes.title'),
                'description' => $request->input('data.attributes.description'),
                'status'      => $request->input('data.attributes.status'),
                'user_id'     => $request->input('data.relationships.author.data.id'),
            ];

            $ticket->update($model);

            return new TicketResource($ticket->refresh());
        } catch (ModelNotFoundException $exception) {
            return $this->ok('Ticket not found.', [
                'error' => 'The provided ticket id does not exist.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticketId)
    {
        try {
            $ticket = Ticket::query()->findOrFail($ticketId);

            $ticket->delete();

            return $this->ok('Ticket successfully deleted.');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket not found.', 404);
        }
    }
}
