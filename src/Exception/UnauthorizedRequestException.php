<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Exception;

class UnauthorizedRequestException extends Exception
{
    /** @var string  */
    private $errorCode;
    /** @var string  */
    private $errorDescription;
    /** @var string|null  */
    private $hint;

    public function __construct(
        string $message,
        string $errorCode,
        string $errorDescription,
        $hint = null
    )
    {
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
        $this->hint = $hint;
        parent::__construct($message);
    }

    public static function fromAssocArray(array $data): self
    {
        return new self(
            $data["message"],
            $data["error"],
            $data["error_description"],
            $data["hint"]
        );
    }

    public function __toString()
    {
        return sprintf(
            "%s: [%s] %s | Message: %s | Hint: %s",
            self::getName(),
            $this->errorCode,
            $this->errorDescription,
            $this->message,
            $this->hint
        );
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    /**
     * @return string|null
     */
    public function getHint()
    {
        return $this->hint;
    }
}
