<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
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
                'token' => $user->createToken('API token for'.$user->email)->plainTextToken,
                ['*'],
                now()->addMonth()
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
