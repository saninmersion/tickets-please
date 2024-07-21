<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function store($authorId, StoreTicketRequest $request)
    {
        $model = [
            'title'       => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status'      => $request->input('data.attributes.status'),
            'user_id'     => $authorId,
        ];

        return new TicketResource(Ticket::query()->create($model));
    }
}
