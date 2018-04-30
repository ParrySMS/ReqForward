<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-4-28
 * Time: 11:43
 */


/*** 网页授权相关 ************************************************/

/** 通过code换取网页授权access_token 含openid
 * @param $code
 * @param string $appid
 * @param string $appsecret
 */
function getWxAccessToken($code, $appid = APPID, $appsecret = APPSECRET)
{
    //appid 和 appsecret在配置文件中
    //根据code获得Access Token 与 openid
    $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
    $access_token_json = request_post($access_token_url);
    print_r($access_token_json);
}

/** 刷新access_token（如果需要） 有效期30天
 * @param string $appid
 * @param $refresh_token
 */
function refreshWxToken($refresh_token, $appid = APPID)
{
    //appid 和 appsecret在配置文件中
    //根据code获得Access Token 与 openid
    $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
    $json = request_post($url);
    print_r($json);
}


/**拉取用户信息(需scope为 snsapi_userinfo)
 * @param string $appid
 * @param $refresh_token
 */
function getWxUserinfo($access_token, $openid)
{
    //appid 和 appsecret在配置文件中
    //根据code获得Access Token 与 openid
    $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
    $json = request_post($url);
    print_r($json);
}


/*** 消息模板相关 ************************************************/

/** 获取全局access_token
 * @param string $appid
 * @param string $appsecret
 */

function getWxAuthAccessToken($appid = APPID, $appsecret = APPSECRET)
{
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    $json = request_get($url);
    print_r($json);
}

/** 发送模板消息
 * @param $access_token
 * @param $touser
 * @param $template_id
 * @param $data
 */
function sendWxMsgTemplate($access_token, $touser, $template_id, $data)
{
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    unset($postData);
    $postData = array();
    $postData['touser'] = $touser;
    $postData['template_id'] = $template_id;
    $postData['data'] = $data;
    $json = request_post($url, $postData);
    print_r($json);
}

/** 获取jsapi_ticket
 * @param $access_token
 */
function getWxJsapiTicket($access_token)
{
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
    $json = request_get($url);
    print_r($json);
}

/** 发送请求
 * @param $url
 * @param null $data
 * @return mixed
 */
function request_post($url, $data = null)
{
    $ch = curl_init();
    /* 设置验证方式 */
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept:text/plain;charset=utf-8',
        'Content-Type:application/x-www-form-urlencoded',
        'charset=utf-8'
    ));
    /* 设置返回结果为流 */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /* 设置超时时间*/
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    /* 设置通信方式 */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($result === false) {
//        echo 'Curl error: ' . $error;
        return 'Curl error: ' . $error;
    } else {
        return $result;
    }
}


function request_get($url, $data = null)
{
    $ch = curl_init();
    /* 设置验证方式 */
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept:text/plain;charset=utf-8',
        'Content-Type:application/x-www-form-urlencoded',
        'charset=utf-8'
    ));
    /* 设置返回结果为流 */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /* 设置超时时间*/
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    /* 设置通信方式 */
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if (!empty($data)) {
        $url = $url . '?' . http_build_query($data);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($result === false) {
        return 'Curl error: ' . $error;
    } else {
        return $result;
    }
}