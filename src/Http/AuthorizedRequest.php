<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Http;

use GuzzleHttp\Psr7\Request;
use Incert\RedemptionApi\DAO\AccessToken;
use Psr\Http\Message\MessageInterface;

abstract class AuthorizedRequest extends Request
{

    public function withAuthorization(AccessToken $accessToken): MessageInterface
    {
        return $this->withHeader(
            "Authorization",
            sprintf(
                "%s %s",
                $accessToken->getTokenType(),
                $accessToken->getAccessToken()
            )
        );
    }
}
