<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Laravel\Sanctum\PersonalAccessToken;

final readonly class Logout
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

        /** @var PersonalAccessToken $personalAccessToken */
        $personalAccessToken = $user->currentAccessToken();
        $personalAccessToken->delete();

        return [
            'message' => "Session closed successfully",
            'user'  => $user
        ];
    }
}
