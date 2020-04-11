<?php
/**
 * Created by PhpStorm.
 * User: 我想有个家
 * Date: 2020/4/10
 * Time: 18:42
 */

namespace mark0325\hjpush\act;


class Device {
    private $url = "https://device.jpush.cn";
    private $client;

    /**
     * Push constructor.
     * @param \mark0325\hjpush\Client $client
     */
    public function __construct($client) {
        $this->client = $client;
    }

    public function getAlias(){

    }
}
