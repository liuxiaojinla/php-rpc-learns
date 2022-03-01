# 前言
[官方文档](https://github.com/hprose/hprose-php/wiki)

# 安装

## 安装扩展
### Windows 安装方式
可以直接从 pecl 官网：https://pecl.php.net/package/hprose/1.6.5/windows 下载你所需要的 Windows 版本的 dll，安装即可。

### Linux 安装方式
可以直接通过pecl的方式安装，也可以下载源码之后，使用 phpize 安装。
```shell
pecl install hprose
```

Mac 安装方式
可以直接通过：

```shell
brew install phpXX-hprose
```
来安装。其中 XX 表示 PHP 的版本号。
也可以通过 pecl 命令安装，或者下载源码之后，通过 phpize 方式安装。

### 导入composer依赖包

```shell
composer require hprose/hprose -vvv
```

# 使用
```php
<?php

require_once '../vendor/autoload.php';

```

## 服务端 http-server
```php
<?php

use Hprose\Http\Server;

require_once __DIR__ . '/../vendor/autoload.php';

function hello(string $name): string
{
    return "hello " . $name . "!";
}

$server = new Server();
$server->addFunction('hello', 'hello');
$server->start();
```
**启动**
```shell
php -S 127.0.0.1:8024 ./src/http-server.php
```

## 客户端
```php
use Hprose\Http\Client;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client([
    'http://127.0.0.1:8024',
], false);
$proxy = $client->useService();

/** @var Hprose\Future $result */
$result = $proxy->hello('小明');
dump($result);
```