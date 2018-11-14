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