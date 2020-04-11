# hyperf jupush

暂时只支持push，其它后续添加

### 使用方式
```php
use mark0325\hjpush\Client;

$extras = ['data' => 131231];
$push = (new Client())->push();
try {
    $res = $push->setAlias('a')
        ->setAlert('1231241231')
        ->setAndroidNotification(['extras' => ['data' => $extras]])
        ->setIosNotification(['extras' => ['data' => $extras]])
        ->send();
} catch (\Throwable $e) {
    return $e->getMessage();
}
```
