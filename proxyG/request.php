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
function sendWxMsgTemplate($access_token, $touser, $template_id, $data, $tem_url, $miniprogram)
{
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    unset($postData);
    $postData = array();
    $postData['touser'] = $touser;
    $postData['template_id'] = $template_id;
    $postData['data'] = $data;
    //可选
    if (is_object($miniprogram) && !empty($miniprogram)) {
        $postData['miniprogram'] = $miniprogram;
    }

    if (!empty($tem_url)) {
        $postData['url'] = $tem_url;
    }

    $json = request_post($url, $postData);
    print_r($json);
}

/** 设置行业
 * 可在微信公众平台后台完成，每月可修改行业1次，帐号仅可使用所属行业中相关的模板
 * @param $access_token
 * @param $industry_id1
 * @param $industry_id2
 */
function setIndustry($access_token, $industry_id1, $industry_id2)
{
    $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=$access_token";

    unset($postData);
    $postData = array();
    $postData['industry_id1'] = $industry_id1;
    $postData['industry_id2'] = $industry_id2;
    $json = request_post($url, $postData);
    print_r($json);
}

/** 查看行业信息
 * @param $access_token
 */
function getIndustry($access_token)
{
    $url = "https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=$access_token";
    $json = request_get($url);
    print_r($json);
}

/** 添加模板并获取模板id
 * @param $access_token
 * @param $template_id_short
 */
function addTemplate($access_token, $template_id_short)
{
    $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=$access_token";
    unset($postData);
    $postData = array();
    $postData['template_id_short'] = $template_id_short;
    $json = request_post($url, $postData);
    print_r($json);

}

/**获取模板列表
 * 获取已添加至帐号下所有模板列表，可在微信公众平台后台中查看模板列表信息
 */
function getAllPrivateTemplate($access_token)
{
    $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=$access_token";
    $json = request_get($url);
    print_r($json);
}

/** 删除模板消息
 * @param $access_token
 * @param $template_id
 */
function delPrivateTemplate($access_token,$template_id){
    $url = "https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=$access_token";
    unset($postData);
    $postData = array();
    $postData['template_id'] = $template_id;
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