<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Http;

class StatusRequest extends AuthorizedRequest
{
    use OriginTrait;

    public function __construct(string $code, $originId = null, $lock = null)
    {
        $headers = [];
        self::setOrigin($headers, $originId);
        if (!is_null($lock))
        {
            $headers["X-Lock"] = $lock;
        }
        parent::__construct(
            "GET",
            sprintf(
                "shop/v3/redemption/status/%s",
                $code
            ),
            $headers
        );
    }
}
