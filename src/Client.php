<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: 我想有个家
 * Date: 2020/4/10
 * Time: 15:39
 */

namespace mark0325\hjpush;


use mark0325\hjpush\act\Push;

class Client {

    public $authorization;
    public $production;

    public function __construct() {
        $appKey = config('jpush.appKey');
        $secret = config('jpush.secret');
        $this->production = config('jpush.production');
        $this->authorization = "Basic " . base64_encode("{$appKey}:{$secret}");
    }

    /**
     * @return Push
     */
    public function push() {
        return new Push($this);
    }
}
