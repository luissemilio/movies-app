<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Laravel\Sanctum\PersonalAccessToken;

final readonly class RefreshToken
{
    /**
     * @param  array{}  $args
     *
     * @return array{}
     */
    public function __invoke(null $_, array $args, GraphQLContext $context)
    {
        /** @var User */
        $user = $context->user();

        /** @var PersonalAccessToken */
        $personalAccessToken = $user->currentAccessToken();
        $personalAccessToken->delete();

        /** @var PersonalAccessToken */
        $token = $user->createToken(name: 'API TOKEN', expiresAt: now()->addMinutes(config('sanctum.expiration')));

        return [
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'type' => 'Bearer',
        ];
    }
}
