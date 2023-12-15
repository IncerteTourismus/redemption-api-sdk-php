<?php
/**
 * @created raphael.berger 23.11.2023
 */

namespace Incert\RedemptionApi\DAO;

class AccessToken implements \Serializable
{
    /** @var string  */
    private $tokenType = "";
    /** @var string  */
    private $accessToken = "";
    /** @var int  */
    private $ttl = 0;
    /** @var null|\DateTime  */
    private $expireDT = null;

    /**
     * @param array $data
     * @return static
     */
    public static function fromAssocArray(array $data): self
    {
        $token = new self();
        $token->tokenType = $data["token_type"];
        $token->accessToken = $data["access_token"];
        $token->ttl = $data["expires_in"];
        $token->expireDT = (new \DateTime())->setTimestamp(
            time() + $token->ttl
        );
        return $token;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @return \DateTime
     */
    public function getExpireDT(): \DateTime
    {
        return $this->expireDT;
    }

    public function serialize()
    {
        return serialize([
            $this->tokenType,
            $this->accessToken,
            $this->ttl,
            $this->expireDT
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->tokenType,
            $this->accessToken,
            $this->ttl,
            $this->expireDT
        ) = unserialize($data);
    }
}
