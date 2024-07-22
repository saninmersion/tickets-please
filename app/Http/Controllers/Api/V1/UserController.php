<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\User\ReplaceUserRequest;
use App\Http\Requests\Api\V1\User\StoreUserRequest;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    protected $policyClass = UserPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::query()
                ->filter($filters)
                ->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->isAble('store', User::class);

            return new UserResource(User::query()->create($request->mappedAttributes()));
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create the resource.', 401);
        }
    }

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

    public function replace(ReplaceUserRequest $request, $userId)
    {
        try {
            $user = User::query()->findOrFail($userId);

            $this->isAble('replace', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user->refresh());
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User not found.', [
                'error' => 'The provided user id does not exist.'
            ]);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update the resource.', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $userId)
    {
        try {
            $user = User::query()
                ->findOrFail($userId);

            $this->isAble('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user->refresh());
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User not found.', [
                'error' => 'The provided user id does not exist.'
            ]);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update the resource.', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        try {
            $user = User::query()->findOrFail($userId);

            $this->isAble('delete', $user);

            $user->delete();

            return $this->ok('User successfully deleted.');
        } catch (ModelNotFoundException $exception) {
            return $this->error('User not found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update the resource.', 401);
        }
    }
}
