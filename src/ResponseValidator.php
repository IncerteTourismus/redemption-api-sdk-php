<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi;

use Incert\RedemptionApi\Exception\BadRequestException;
use Incert\RedemptionApi\Exception\Exception;
use Incert\RedemptionApi\Exception\UnauthorizedRequestException;
use Incert\RedemptionApi\Exception\UnhandledErrorException;
use Psr\Http\Message\ResponseInterface;

abstract class ResponseValidator
{

    /**
     * @param ResponseInterface $response
     * @return void
     * @throws Exception
     */
    public static function validate(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 200)
        {
            return;
        }
        $raw = $response->getBody()->getContents();
        $parsedResponse = json_decode($raw, true);
        switch ($response->getStatusCode())
        {
            case 400:
                # Example:
                # {
                #     "error": true,
                #     "message": "string",
                #     "code": "string"
                # }
                throw BadRequestException::fromAssocArray($parsedResponse);
            case 401:
                # Example:
                # {
                #     "error": "access_denied",
                #     "error_description": "The resource owner or authorization server denied the request.",
                #     "hint": "Error while decoding from JSON",
                #     "message": "The resource owner or authorization server denied the request."
                # }
                throw UnauthorizedRequestException::fromAssocArray($parsedResponse);
        }
        throw UnhandledErrorException::create($raw);
    }
}
