<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: 我想有个家
 * Date: 2020/4/10
 * Time: 17:06
 */
namespace mark0325\hjpush\act;

use mark0325\hjpush\Http;

class Push {
    private $url = "https://api.jpush.cn/v3/push";
    private $client;
    private $cid;
    private $platform = ["android", "ios"];
    private $audience = [];
    private $notification = [];
    private $smsMessage;
    private $message;
    private $options;
    private $callback;
    private $notificationThird;

    /**
     * Push constructor.
     * @param \mark0325\hjpush\Client $client
     */
    public function __construct($client) {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @param array $platform
     * @return $this
     */
    public function setPlatform(array $platform = []) {
        if ($platform) {
            $this->platform = $platform;
        }
        return $this;
    }

    /**
     * @param string|array $val
     * @return $this
     */
    public function setTag($val) {
        $this->updateAudience('tag', $val);
        return $this;
    }

    /**
     * @param string|array $val
     * @return $this
     */
    public function setTagAnd($val) {
        $this->updateAudience('tag_and', $val);
        return $this;
    }

    /**
     * @param string|array $val
     * @return $this
     */
    public function setTagNot($val) {
        $this->updateAudience('tag_not', $val);
        return $this;
    }

    /**
     * @param string|array $val
     * @return $this
     */
    public function setAlias($val) {
        $this->updateAudience('alias', $val);
        return $this;
    }

    /**
     * @param string|array $val
     * @return $this
     */
    public function setRegistrationID($val) {
        $this->updateAudience('registration_id', $val);
        return $this;
    }

    /**
     * @param string $val
     * @return $this
     */
    public function setSegment(string $val) {
        $this->audience['segment'] = [$val];
        return $this;
    }

    /**
     * @param string $val
     * @return $this
     */
    public function setAbtest(string $val) {
        $this->audience['abtest'] = [$val];
        return $this;
    }

    /**
     * @param string $type
     * @param string|array $val
     */
    private function updateAudience($type, $val) {
        if (!isset($this->audience[$type])) {
            $this->audience[$type] = [];
        }
        if (is_array($val)) {
            $this->audience[$type] = array_merge($this->audience[$type], $val);
        } else {
            $this->audience[$type][] = $val;
        }
    }

    /**
     * @param string $alert
     * @return $this
     */
    public function setAlert(string $alert) {
        $this->notification['alert'] = $alert;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setAndroidNotification(array $option) {
        $this->notification['android'] = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setIosNotification(array $option) {
        $this->notification['ios'] = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setIosVoip(array $option) {
        $this->notification['voip'] = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setMessage(array $option) {
        $this->message = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setSmsMessage(array $option) {
        $this->smsMessage = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setOptions(array $option) {
        if (!isset($option['apns_production'])) {
            $option['apns_production'] = $this->client->production;
        }
        $this->options = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setCallback(array $option) {
        $this->callback = $option;
        return $this;
    }

    /**
     * @param array $option
     * @return $this
     */
    public function setNotificationThird(array $option) {
        $this->notificationThird = $option;
        return $this;
    }

    /**
     * @param string $cid
     * @return $this
     */
    public function setCid(string $cid) {
        $this->cid = $cid;
        return $this;
    }

    /**
     * @return false|string
     * @throws \Exception
     */
    private function buildBody() {
        if (empty($this->platform)) {
            $this->platform = "all";
        }
        $data = ['platform' => $this->platform];

        if (empty($this->audience)) {
            $this->audience = "all";
        }
        $data['audience'] = $this->audience;

        if (empty($this->notification)) {
            throw new \Exception('notification must be set');
        }
        if (empty($this->notification['alert'])) {
            throw new \Exception('alert must be set');
        }

        if (isset($this->notification['android']) && empty($this->notification['android']['alert'])) {
            $this->notification['android']['alert'] = $this->notification['alert'];
        }

        if (isset($this->notification['ios']) && empty($this->notification['ios']['alert'])) {
            $this->notification['ios']['alert'] = $this->notification['alert'];
        }
        $data['notification'] = $this->notification;

        if ($this->message) {
            $data['message'] = $this->message;
        }
        if ($this->smsMessage) {
            $data['sms_message'] = $this->smsMessage;
        }
        if ($this->options) {
            $data['options'] = $this->options;
        } else {
            $data['options'] = ['apns_production' => $this->client->production];
        }
        if ($this->callback) {
            $data['callback'] = $this->callback;
        }
        if ($this->notificationThird) {
            $data['notification_3rd '] = $this->notificationThird;
        }
        if ($this->cid) {
            $data['cid '] = $this->cid;
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function send() {
        return Http::post($this->url, $this->client->authorization, $this->buildBody());
    }
}
