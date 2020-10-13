<?php
/*
* @ class name qqmini Request
* @ author tjit.net <blog@tjit.net>
* @ link https://www.tjit.net
* @ copyright 2020.10.13 tjit.net
*/


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


class  qqmini
{
    public static function Request_Send($Host, $param)
    {
        ksort($param);
        $paramStr = '';
        foreach ($param as $key => $value) {
            $paramStr .=  $key . "=" . $value . "&";
        }
        $paramStr = substr($paramStr, 0, -1);
        $ret = self::curl_get($Host . '?' . $paramStr);
        if (!empty($ret)) {
            $arr = json_decode($ret, true);
            if ($arr['Code'] < 0) {
                return '请求失败：' . $arr['Result'];
            } else {
                return $ret;
            }
        } else {
            return '请求失败,网络通讯错误';
        }
    }

    public static function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function Event($Event)
    {
        $Event_arr = [
            -1 => '未定义的消息类型',
            1 => '好友消息',
            2 => '群消息',
            3 => '讨论组消息',
            4 => '群临时会话消息',
            5 => '讨论组临时会话消息',
            6 => '在线临时会话消息',
            7 => '好友验证回复会话消息',
            1000 => '被单向添加好友',
            1001 => '被请求添加好友',
            1002 => '好友状态改变',
            1003 => '被删除好友',
            1004 => '好友签名变更',
            1005 => '说说被评论',
            1006 => '好友正在输入',
            1007 => '好友今天首次发起会话',
            1008 => '被好友抖动',
            1009 => '好友文件接收',
            1010 => '好友视频接收',
            1011 => '被同意加为好友',
            1012 => '被拒绝加为好友',
            80001 => '收到财付通转账',
            2001 => '某人申请加入群',
            2002 => '某人被邀请加入群',
            2003 => '我被邀请加入群',
            2005 => '某人被批准加入了群',
            2006 => '某人退出群',
            2007 => '某人被管理移除群',
            2008 => '某群被解散',
            2009 => '某人成为管理',
            2010 => '某人被取消管理',
            2011 => '群名片变动',
            2012 => '群名变动',
            2013 => '群公告变动',
            2014 => '对象被禁言',
            2015 => '对象被解除禁言',
            2016 => '群管开启全群禁言',
            2017 => '群管关闭全群禁言',
            2018 => '群管开启匿名聊天',
            2019 => '群管关闭匿名聊天',
            2020 => '群撤回消息',
            2021 => '群文件接收',
            2022 => '群视频接收',
            10000 => '框架加载完成',
            10001 => '框架即将重启',
            11000 => '添加了一个新的帐号',
            11001 => 'QQ登录完成',
            11002 => 'QQ被手动离线',
            11003 => 'QQ被强制离线',
            11004 => 'QQ长时间无响应或掉线',
        ];
        return $Event_arr[$Event];
    }
}
