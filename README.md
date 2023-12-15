# redemption-api-sdk-php
Incert Redemption API SDK


## Public API Documentation
TODO: add link

## Initialize application
```
require_once "vendor/autoload.php";

use GuzzleHttp\Psr7\Uri;
use Incert\RedemptionApi\ApiContext;
use Incert\RedemptionApi\Application;

$incert = new Application(
    new ApiContext(
        // Set base URI
        new Uri("https://api-sandbox.myincert.com"),
        // Set client id
        "my-client-id",
        // Set client secret
        "my-client-secret"
    )
);
```

## Cache access token

Implement the AccessTokenStorage interface to provide a cache.

You can cache the token in any database, filesystem, etc.

AccessToken implements the \Serializable interface.
* serialize() in ::set()
* unserialize() in ::get()

Below is an example for redis.
```
use Incert\RedemptionApi\Cache\AccessTokenStorage;
use Incert\RedemptionApi\DAO\AccessToken;

class RedisAccessTokenStorage implements AccessTokenStorage
{
    private const KEY = "incert_token";
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
    }

    public function get(): AccessToken
    {
        return unserialize($this->redis->get(self::KEY));
    }

    public function set(AccessToken $accessToken): void
    {
        $this->redis->setex(
            self::KEY,
            $accessToken->getTtl(),
            serialize($accessToken)
        );
    }

    public function invalidate(AccessToken $accessToken): void
    {
        $this->redis->del(self::KEY);
    }
}
```

## Initialize application with token cache
```
require_once "vendor/autoload.php";

use GuzzleHttp\Psr7\Uri;
use Incert\RedemptionApi\ApiContext;
use Incert\RedemptionApi\Application;
use Incert\RedemptionApi\Cache\AccessTokenStorage;

$incert = new Application(
    new ApiContext(
        // Set base URI
        new Uri("https://api-sandbox.myincert.com"),
        // Set client id
        "my-client-id",
        // Set client secret
        "my-client-secret"
    ),
    // provide cache 
    new RedisAccessTokenStorage()
);
```

## Basic usage
### Status
TODO: change key

See https://api-sandbox.myincert.com/api/doc/TK2XF9XOFQVOOHWTZA99UQQ#/Incert%20Redemption%20v3/get_shop_v3_redemption_status__code_
```
$incert->status("my-voucher-code");
```

### Redemption
TODO: change key

See https://api-sandbox.myincert.com/api/doc/TK2XF9XOFQVOOHWTZA99UQQ#/Incert%20Redemption%20v3/post_shop_v3_redemption_redeem
```
$incert->redeem([
    "code" => "my-voucher-code",
    "uuid" => "c879d955-4b2f-4ed5-adbf-e487d740f0cc",
    "amount" => 100,
    "currency" => "EUR"
]);
```

### Cancel redemption
TODO: change key

See https://api-sandbox.myincert.com/api/doc/TK2XF9XOFQVOOHWTZA99UQQ#/Incert%20Redemption%20v3/post_shop_v3_redemption_redeem_cancel
```
$incert->cancel([
    "code" => "my-voucher-code",
    "redemptionId" => "12345"
]);
```

## Error handling
```
use Incert\RedemptionApi\Exception\BadRequestException;
use Incert\RedemptionApi\Exception\UnauthorizedRequestException;

try
{
    $incert->status("my-voucher-code");
}
catch (\Exception $e)
{
    // 401 response - invalid client credentials
    if ($e instanceof UnauthorizedRequestException)
    {
        var_dump([
            // https://datatracker.ietf.org/doc/html/rfc6749#section-5.2
            $e->getErrorCode(),
            $e->getErrorDescription(),
            $e->getMessage(),
            $e->getHint()
        ]);
        // implements __toString() for logging purposes
        echo $e;
        // UnauthorizedRequestException: [invalid_client] Client authentication failed | Message: Client authentication failed | Hint: 
    }
    // 400 response
    if ($e instanceof BadRequestException)
    {
        var_dump([
            $e->getErrorCode(),
            $e->getMessage()
        ]);
        echo $e;
    }
}
```