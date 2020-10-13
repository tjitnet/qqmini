# qqmini
基于qqmini HTTP_API框架的PHP实例
### 感谢QQMini && HTTP_API(@未死鲤鱼)
## 请求API：
``` 
<?php
require 'qqmini_class.php';
$Host = 'http://127.0.0.1:88/httpAPI';  // HTTPAPI服务地址
/*
*@ param $Api_params_array 请求API所需的参数数组
*/
$Api_params_array = [
    'Api' => 'SendMsg',
    'Robot' => '206006691',
    'Type' => '2',
    'Group' => '34544417',
    'QQ' => '523077333',
    'Content' => 'body'
];

$ret = qqmini::Request_Send($Host, $Api_params_array); //请求API,直接返回JSON内容
echo $ret;

```
## 回调处理Demo：
```
<?php
/*
*@ 回调信息处理
*/

$request = file_get_contents("php://input");
$arr = json_decode($request, true);
if (!empty($arr['Event'])) {
    $errmsg = qqmini::Event($arr['Event']); //获取回调消息标识的说明文字
    $date = date('Y-m-d H:i:s');
    file_put_contents("./request.log", $date . $errmsg . $request . "\n", FILE_APPEND); //保存回调消息到日志
    echo json_encode(["Code" => 1, "content" => "回调消息接收成功"], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); //返回消息：Code：返回值（消息处理方式）0忽略此消息 1继续处理 2拦截此消息 10同意此事件 20拒绝此事件
    /*
    * 这里开始自己写回调推送消息处理程序流程
    */
}

```
