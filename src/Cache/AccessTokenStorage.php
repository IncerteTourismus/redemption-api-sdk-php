<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\Cache;

use Incert\RedemptionApi\DAO\AccessToken;

interface AccessTokenStorage
{

    /**
     * @return AccessToken
     */
    public function get(): AccessToken;

    /**
     * @param AccessToken $accessToken
     * @return void
     */
    public function set(AccessToken $accessToken);

    /**
     * @param AccessToken $accessToken
     * @return void
     */
    public function invalidate(AccessToken $accessToken);
}
