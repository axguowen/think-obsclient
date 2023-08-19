# ThinkPHP 对象存储 客户端

一个简单的 ThinkPHP 对象存储 客户端


## 安装
~~~
composer require axguowen/think-obsclient
~~~

## 使用

首先配置config目录下的obsclient.php配置文件。

### 简单使用
~~~php
// 上传文件
$putObject = \think\facade\ObsClient::putObject('uploads/putobject-test.txt', 'fileContent');
// 如果成功
if(!is_null($putObject[0])){
    var_dump($putObject);
}
// 失败
else{
    // 错误信息
    echo $putObject[1]->getMessage();
}
~~~

### 高级使用
~~~php
// 动态切换平台
$obsClient = \think\facade\ObsClient::platform('baidu');

// 上传文件
$putObject = $ObsClient->putObject('uploads/putobject-test.txt', 'fileContent');
if(!is_null($putObject[0])){
    var_dump($putObject);
}
else{
    echo $putObject[1]->getMessage();
}

~~~