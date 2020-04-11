# hyperf jupush

暂时只支持push，其它后续添加

### 安装
composer require mark0325/hjpush
### 发布配置
php bin/hyperf.php vendor:publish mark0325/hjpush
### 示例代码
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
### 设备相关
示例
```php
use mark0325\hjpush\Client;

$device = (new Client())->device();
try {
    $res = $device->getAliases('a');
} catch (\Throwable $e) {
    return $e->getMessage();
}
```
|方法|说明|
|---|---|
|getInfo|查询设备的别名与标签|
|setInfo|设置设备的别名与标签|
|getAliases|查询别名|
|deleteAliases|删除别名|
|postAliases|批量解绑设备与别名之间的关系|
|getTags|查询标签|
|checkTags|查询某个设备是否在 tag 下|
|postTags|为一个标签添加或者删除设备|
|deleteTags|删除一个标签，以及标签与设备之间的关联关系|
|getDevicesStatus|获取设备在线状态（VIP 专属接口）|
