<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Exception;

class UnhandledErrorException extends Exception
{

    public static function create(string $message): self
    {
        return new self(
            sprintf(
                "Unhandled error: %s",
                $message
            )
        );
    }
}
