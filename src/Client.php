<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: 我想有个家
 * Date: 2020/4/10
 * Time: 15:39
 */

namespace mark0325\hjpush;


use Hyperf\Contract\ConfigInterface;
use mark0325\hjpush\act\Push;

class Client {

    /**
     * @var ConfigInterface
     */
    private $config;

    public function __construct(ConfigInterface $config) {
        $this->config = $config;
    }

    /**
     * 获取 header Authorization
     * @return string
     */
    public function getAuthorization() {
        return base64_encode("{$this->config->get('jpush.appKey')}:{$this->config->get('jpush.secret')}");
    }

    /**
     * @return Push
     */
    public function push() {
        return new Push($this);
    }
}
