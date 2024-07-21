<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    use ApiResponses;

    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if ( !Auth::attempt($request->only('email', 'password')) ) {
            return $this->error('Invalid credentials', 402);
        }

        /** @var User $user */
        $user = User::query()->firstWhere('email', $request->get('email'));

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for '.$user->email,
                    Abilities::getAbilities($user),
                    now()->addMonth()
                )->plainTextToken
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }

    public function register(LoginRequest $request)
    {
        return $this->ok($request->get('email'));
    }
}
