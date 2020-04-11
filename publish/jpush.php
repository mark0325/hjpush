<?php
declare(strict_types=1);

return [
    # 极光 appKey
    'appKey' => env('JPUSH_APPKEY', ''),
    # 极光 masterSecret
    'secret' => env('JPUSH_SECRET', ''),
    # 生产环境 默认 false
    'production' => env('JPUSH_PRO', false),
];
