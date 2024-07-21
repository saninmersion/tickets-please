<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;

class AuthorTicketController extends ApiController
{
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
}
