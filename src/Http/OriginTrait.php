<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Http;

trait OriginTrait
{

    private static function setOrigin(array &$headers, $originId = null)
    {
        if (!is_null($originId))
        {
            $headers["X-Origin-Id"] = $originId;
        }
    }
}
