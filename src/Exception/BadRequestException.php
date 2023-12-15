<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Exception;

class BadRequestException extends Exception
{
    /** @var string  */
    private $errorCode;

    public function __construct(string $message, string $errorCode)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message);
    }

    public static function fromAssocArray(array $data): self
    {
        return new self(
            $data["message"],
            $data["code"]
        );
    }

    public function __toString(): string
    {
        return sprintf(
            "%s: [%s] %s",
            self::getName(),
            $this->errorCode,
            $this->message
        );
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
