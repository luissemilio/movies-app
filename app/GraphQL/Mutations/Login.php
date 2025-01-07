<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use App\Models\User;
use Illuminate\Support\Arr;
use Laravel\Sanctum\PersonalAccessToken;

final readonly class Login
{
    /**
     * @param  array{}  $args
     *
     * @return array{}
     */
    public function __invoke(null $_, array $args): array
    {
        $credentials = Arr::get($args, 'input');

        if (! Auth::attempt($credentials)) {
            throw new AuthenticationException('Invalid credentials');
        }

        /** @var User */
        $user = Auth::user();
        /** @var PersonalAccessToken */
        $token = $user->createToken(name: 'API TOKEN', expiresAt: now()->addMinutes(config('sanctum.expiration')));

        return [
            'access_token' => [
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at,
                'type' => 'Bearer',
            ],
            'user' => $user,
        ];
    }
}
