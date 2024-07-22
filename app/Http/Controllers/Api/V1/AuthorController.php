<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\Ticket\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::query()
                ->select('users.*')
                ->join('tickets', 'user_id', '=', 'tickets.user_id')
                ->filter($filters)
                ->distinct()
                ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request) {}

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ( $this->include('tickets') ) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
