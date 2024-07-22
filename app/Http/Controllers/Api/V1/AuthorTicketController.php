<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\Ticket\ReplaceUserRequest;
use App\Http\Requests\Api\V1\Ticket\StoreUserRequest;
use App\Http\Requests\Api\V1\Ticket\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index($authorId, TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::query()
                ->where('user_id', $authorId)
                ->filter($filters)
                ->paginate()
        );
    }

    public function replace(ReplaceUserRequest $request, $authorId, $ticketId)
    {
        try {
            $ticket = Ticket::query()
                ->where('user_id', $authorId)
                ->where('id', $ticketId)
                ->firstOrFail();

            $this->isAble('replace', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket->refresh());
        } catch (ModelNotFoundException $exception) {
            return $this->ok('Ticket not found.', [
                'error' => 'The provided ticket id does not exist.'
            ]);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update the resource.', 401);
        }
    }

    public function store(StoreUserRequest $request, $authorId)
    {
        try {
            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::query()->create($request->mappedAttributes([
                'author' => 'user_id'
            ])));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create the resource.', 401);
        }
    }

    public function destroy($authorId, $ticketId)
    {
        try {
            $ticket = Ticket::query()
                ->where('user_id', $authorId)
                ->where('id', $ticketId)
                ->firstOrFail();
            $this->isAble('delete', $ticket);

            $ticket->delete();

            return $this->ok('Ticket successfully deleted.');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket not found.', 404);
        }
    }

    public function update(UpdateTicketRequest $request, $authorId, $ticketId)
    {
        try {
            $ticket = Ticket::query()
                ->where('user_id', $authorId)
                ->where('id', $ticketId)
                ->firstOrFail();

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket->refresh());
        } catch (ModelNotFoundException $exception) {
            return $this->ok('Ticket not found.', [
                'error' => 'The provided ticket id does not exist.'
            ]);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update the resource.', 401);
        }
    }
}
