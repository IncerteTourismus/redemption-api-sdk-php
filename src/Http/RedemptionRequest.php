<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Http;

class RedemptionRequest extends AuthorizedRequest
{
    use OriginTrait;

    public function __construct($originId = null)
    {
        $headers = [];
        self::setOrigin($headers, $originId);
        parent::__construct(
            "POST",
            "shop/v3/redemption/redeem",
            $headers
        );
    }
}
