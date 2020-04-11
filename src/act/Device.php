<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: 我想有个家
 * Date: 2020/4/10
 * Time: 18:42
 */

namespace mark0325\hjpush\act;


use mark0325\hjpush\Http;

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

    /**
     * 查询设备的别名与标签
     * @param string $registrationID
     * @return array
     * @throws \Throwable
     */
    public function getInfo(string $registrationID) {
        return Http::get("{$this->url}/v3/devices/{$registrationID}", $this->client->authorization, []);
    }

    /**
     * 设置设备的别名与标签
     * @param string $registrationID
     * @param array $body
     * @return array
     * @throws \Throwable
     */
    public function setInfo(string $registrationID, array $body) {
        $body = json_encode($body, JSON_UNESCAPED_UNICODE);
        return Http::post("{$this->url}/v3/devices/{$registrationID}", $this->client->authorization, $body);
    }

    /**
     * 查询别名
     * @param string $aliases
     * @param string $platform
     * @return array
     * @throws \Throwable
     */
    public function getAliases(string $aliases, string $platform = "android,ios") {
        return Http::get("{$this->url}/v3/aliases/{$aliases}", $this->client->authorization, ['platform' => $platform]);
    }

    /**
     * 删除别名
     * @param string $aliases
     * @param string $platform
     * @return array
     * @throws \Throwable
     */
    public function deleteAliases(string $aliases, string $platform = "android,ios") {
        return Http::delete("{$this->url}/v3/aliases/{$aliases}?platform={$platform}", $this->client->authorization, '');
    }

    /**
     * 批量解绑设备与别名之间的关系
     * @param string $aliases
     * @param array $registrationIDs
     * @return array
     * @throws \Throwable
     */
    public function postAliases(string $aliases, array $registrationIDs) {
        if (empty($registrationIDs)) {
            throw new \Exception('registration_ids must set');
        }
        $body = json_encode(["registration_ids" => ["remove" => $registrationIDs],], JSON_UNESCAPED_UNICODE);
        return Http::post("{$this->url}/v3/aliases/{$aliases}", $this->client->authorization, $body);
    }

    /**
     * 查询标签
     * @return array
     * @throws \Throwable
     */
    public function getTags() {
        return Http::get("{$this->url}/v3/tags", $this->client->authorization, []);
    }

    /**
     * 查询某个设备是否在 tag 下
     * @param string $tag
     * @param string $registrationID
     * @return array
     * @throws \Throwable
     */
    public function checkTags(string $tag, string $registrationID) {
        return Http::get("{$this->url}/v3/tags/{$tag}/registration_ids/{$registrationID}", $this->client->authorization, []);
    }

    /**
     * 为一个标签添加或者删除设备
     * @param string $tag
     * @param array $addRegistrationIDs
     * @param array $removeRegistrationIDs
     * @return array
     * @throws \Throwable
     */
    public function postTags(string $tag, array $addRegistrationIDs, array $removeRegistrationIDs) {
        $registrationIDs = [];
        if ($addRegistrationIDs) {
            $registrationIDs['add'] = $addRegistrationIDs;
        }
        if ($removeRegistrationIDs) {
            $registrationIDs['add'] = $removeRegistrationIDs;
        }
        if (empty($registrationIDs)) {
            throw new \Exception('registration_ids must set');
        }
        $registrationIDs = json_encode($registrationIDs, JSON_UNESCAPED_UNICODE);
        return Http::post("{$this->url}/v3/tags/{$tag}", $this->client->authorization, $registrationIDs);
    }

    /**
     * 删除一个标签，以及标签与设备之间的关联关系
     * @param string $tag
     * @param string $platform
     * @return array
     * @throws \Throwable
     */
    public function deleteTags(string $tag, string $platform = "android,ios") {
        return Http::delete("{$this->url}/v3/tags/{$tag}?platform={$platform}", $this->client->authorization, '');
    }

    /**
     * 获取设备在线状态（VIP 专属接口）
     * @param array $registrationIDs
     * @return array
     * @throws \Throwable
     */
    public function getDevicesStatus(array $registrationIDs) {
        if (empty($registrationIDs)) {
            throw new \Exception('registrationIDs must set');
        }
        $body = json_encode(['registration_ids' => $registrationIDs], JSON_UNESCAPED_UNICODE);
        return Http::post("{$this->url}/v3/devices/status/", $this->client->authorization, $body);
    }
}
