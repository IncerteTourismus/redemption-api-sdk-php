<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Http;

use GuzzleHttp\Psr7\Request;

class AccessTokenRequest extends Request
{

    public function __construct()
    {
        parent::__construct(
            "POST",
            "oauth/accessToken"
        );
    }
}
