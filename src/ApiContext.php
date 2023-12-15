<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi;

use GuzzleHttp\Psr7\Uri;

class ApiContext
{
    /** @var Uri  */
    private $baseUri;
    /** @var string  */
    private $clientId;
    /** @var string  */
    private $clientSecret;

    public function __construct(
        Uri $baseUri,
        string $clientId,
        string $clientSecret
    )
    {
        $this->baseUri = $baseUri;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return Uri
     */
    public function getBaseUri(): Uri
    {
        return $this->baseUri;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}
