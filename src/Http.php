<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: 我想有个家
 * Date: 2020/4/10
 * Time: 18:18
 */

namespace mark0325\hjpush;

use Hyperf\Di\Exception\Exception;
use Hyperf\Guzzle\HandlerStackFactory;
use GuzzleHttp\Client;

class Http {

    /**
     * @return Client
     */
    private static function getClient() {
        $factory = new HandlerStackFactory();
        $stack = $factory->create();
        return make(Client::class, [
            'config' => [
                'handler' => $stack,
            ],
        ]);
    }

    /**
     * @param string $url
     * @param string $authorization
     * @param array $query
     * @return array
     * @throws Exception
     */
    public static function get(string $url, string $authorization, array $query) {
        $response = self::getClient()->get($url, [
            'headers' => ['Authorization' => $authorization],
            'query' => $query
        ]);
        return self::responseCheck($response);
    }

    /**
     * @param string $url
     * @param string $authorization
     * @param string $body
     * @return array
     * @throws Exception
     */
    public static function post(string $url, string $authorization, string $body) {
        $response = self::getClient()->post($url, [
            'headers' => ['Authorization' => $authorization],
            'body' => $body,
        ]);
        return self::responseCheck($response);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array
     * @throws Exception
     */
    private static function responseCheck($response) {
        $code = $response->getStatusCode();
        if ($code != 200) {
            throw new Exception("request error ($code)");
        }
        $body = (string) $response->getBody();
        if (empty($body)) {
            return [];
        }
        $body = json_decode($body, true);
        if (isset($body['error'])) {
            throw new Exception("business error, {$body['error']['message']} ({$body['error']['code']})");
        }
        return $body;
    }
}
