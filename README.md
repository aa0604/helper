# helper
## 安装

#### 推荐使用composer进行安装

```
composer require xing.chen/helper dev-master
```
#### 依赖安装
如需要以下功能，请另行手动输入composer安装。
电子表格：composer require endroid/qr-code
## 文件目录
```php
- resource
resource/HttpHelper.php http CURL 访问
- text 字符串
text/CheckHelper.php  字符串检查
text/StringHelper.php 字符串处理
- gii
yii/gii YII2 gii生成器相关，如模板
- yii
yii/ARObjectHelper.php 模型AR助手（YII2）
yii/MyActiveRecord.php 模型AR父类
yii/MyActiveRecordTrait.php 模型AR Trait 类
yii/MyCacheTrait.php 模型缓存类
- controllers 控制器
controllers/YiiQrCodeController 二维码：生成
- 
./FormHelper.php 表单类助手
-
```

## 配置在线二维码(YII)

二维码在线生成：/qr-code/en-code?text=url

二维码生成并下载：/qr-code/en-code-download?text=url

配置：
```php
'controllerMap' => [
    'qr-code' => [
        'class' => 'xing\helper\controllers\YiiQrCodeController',
    ]
];
```

## 微信相关
需要安装如下依赖

composer require overtrue/wechat

配置作为yii2组件使用
```php
<?php
'components' => [
'weChat' => [
    'class' => 'xing\helper\yii\WeChat',
    'weChatConfig' => [
        'app_id' => 'app_id',
        'secret' => 'secret',
    ],
]
];
$service = Yii::$app->weChat;

```
### 不依赖框架独立使用

```php
<?php
$service = WeChatService::start(['app_id' => 'app_id', 'secret' => 'secret']);
```
### 使用示例

```php
<?php

// 获取openId
$openId = $service->getMiniProgramOpenId($code);

// 获取微信能力调起授权
$config = $service->buildConfig(['能力1', '能力2'], 'url');

// 解密（比如获取用户手机）
$sessionKey = $service->getSessionKey($code);
$data = $service->decryptData($encryptedData, $iv, $sessionKey);
```
