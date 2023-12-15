<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Exception;

abstract class Exception extends \Exception
{

    protected static function getName(): string
    {
        $callingChild = get_called_class();
        $chunks = explode("\\", $callingChild);
        return $chunks[count($chunks) - 1];
    }
}
