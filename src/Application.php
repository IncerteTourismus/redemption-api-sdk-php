<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Incert\RedemptionApi\Cache\AccessTokenStorage;
use Incert\RedemptionApi\DAO\AccessToken;
use Incert\RedemptionApi\DAO\CanceledRedemption;
use Incert\RedemptionApi\DAO\Redemption;
use Incert\RedemptionApi\DAO\Status;
use Incert\RedemptionApi\Http\AccessTokenRequest;
use Incert\RedemptionApi\Http\AuthorizedRequest;
use Incert\RedemptionApi\Http\CancelRedemptionRequest;
use Incert\RedemptionApi\Http\RedemptionRequest;
use Incert\RedemptionApi\Http\StatusRequest;
use Psr\Http\Message\ResponseInterface;

class Application
{
    /** @var ApiContext  */
    private $context;
    /** @var AccessTokenStorage|null  */
    private $accessTokenStorage;
    /** @var Client|null  */
    private $httpClient = null;
    /** @var AccessToken|null  */
    private $accessToken = null;

    /**
     * @param ApiContext $context
     * @param AccessTokenStorage|null $accessTokenStorage
     */
    public function __construct(ApiContext $context, AccessTokenStorage $accessTokenStorage = null)
    {
        $this->context = $context;
        $this->accessTokenStorage = $accessTokenStorage;
    }

    /**
     * @param string $code
     * @param string|null $originId
     * @param bool|null $lock
     * @return Status
     * @throws GuzzleException
     */
    public function status(string $code, string $originId = null, bool $lock = null): Status
    {
        $result = $this->parseResponse(
            $this->execute(
                new StatusRequest($code, $originId, $lock)
            )
        );
        return new Status($result);
    }

    /**
     * @param array $data
     * @param string|null $originId
     * @return Redemption
     * @throws GuzzleException
     */
    public function redeem(array $data, string $originId = null): Redemption
    {
        $result = $this->parseResponse(
            $this->execute(
                new RedemptionRequest($originId),
                ["json" => $data]
            )
        );
        return new Redemption($result);
    }

    /**
     * @param array $data
     * @param string|null $originId
     * @return CanceledRedemption
     * @throws GuzzleException
     */
    public function cancel(array $data, string $originId = null): CanceledRedemption
    {
        $result = $this->parseResponse(
            $this->execute(
                new CancelRedemptionRequest($originId),
                ["json" => $data]
            )
        );
        return new CanceledRedemption($result);
    }

    /**
     * @param Request $request
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws \Exception
     */
    private function execute(Request $request, array $options = []): ResponseInterface
    {
        if (is_null($this->httpClient))
        {
            $this->createClient();
        }
        if ($request instanceof AuthorizedRequest)
        {
            if (!$this->hasValidAccessToken())
            {
                $this->getAccessToken();
            }
            $request = $request->withAuthorization($this->accessToken);
        }
        try
        {
            return $this->httpClient->send($request, $options);
        }
        catch (GuzzleException $e)
        {
            if ($e instanceof ClientException && $e->hasResponse())
            {
                ResponseValidator::validate($e->getResponse());
            }
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @throws GuzzleException
     * @return void
     */
    private function getAccessToken()
    {
        $response = $this->execute(
            new AccessTokenRequest(),
            ["json" => [
                "grant_type" => "client_credentials",
                "scope" => "gms",
                "client_id" => $this->context->getClientId(),
                "client_secret" => $this->context->getClientSecret()
            ]]
        );
        ResponseValidator::validate($response);
        $data = $this->parseResponse($response);
        $this->accessToken = AccessToken::fromAssocArray($data);
        if (!is_null($this->accessTokenStorage))
        {
            $this->accessTokenStorage->set($this->accessToken);
        }
    }

    /**
     * @return void
     */
    private function createClient()
    {
        $this->httpClient = new Client([
            "base_uri" => sprintf(
                "%s/api/",
                $this->context->getBaseUri()
            )
        ]);
    }

    /**
     * @return bool
     */
    private function hasValidAccessToken(): bool
    {
        if (is_null($this->accessToken))
        {
            return false;
        }
        if (is_null($this->accessTokenStorage))
        {
            return false;
        }

        $token = $this->accessTokenStorage->get();
        if ($token->getExpireDT() <= new \DateTime())
        {
            $this->accessTokenStorage->invalidate($token);
            return false;
        }

        $this->accessToken = $token;
        return true;
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    private function parseResponse(ResponseInterface $response): array
    {
        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }
}
