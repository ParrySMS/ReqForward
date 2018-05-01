<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-4-29
 * Time: 10:37
 */

require "./auth.php";
require "./request.php";
require "./http.php";
require "./config.php";

try {
    $sign = isset($_GET['sign']) ? $_GET['sign'] : null;
    isVaildSign($sign);

    $actionEn = isset($_GET['action']) ? $_GET['action'] : null;
    //解密
    $action = base64_decode($actionEn);
    isVaildAction($action);

    //选择配置文件的判断分支
    $region = eval(ACTION_REGION);

    switch ($action) {
        /**********网页开发********/

        //通过code换取网页授权access_token 含openid
        case $region[0]://POST getWxAccessToken
            $code = isset($_POST['code']) ? $_POST['code'] : null;
            getWxAccessToken($code);
            break;

        //刷新access_token（如果需要） 有效期30天
        case $region[1]://POST refreshWxToken
            $refreshToken = isset($_POST['refresh_token']) ? $_POST['refresh_token'] : null;
            refreshWxToken($refreshToken);
            break;

        //拉取用户信息(需scope为 snsapi_userinfo)
        case $region[2]://POST getWxUserinfo
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            $openid = isset($_POST['openid']) ? $_POST['openid'] : null;
            getWxUserinfo($access_token, $openid);
            break;


        //获取全局 access_token
        case $region[3]://GET getWxAuthAccessToken
            getWxAuthAccessToken();
            break;

        /************** 模板消息类 ***************/

        //发送模板消息
        case $region[4]://POST sendWxMsgTemplate
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            $touser = isset($_POST['touser']) ? $_POST['touser'] : null;
            $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : null;
            $data = isset($_POST['data']) ? $_POST['data'] : null;
            //可选
            $url = isset($_POST['url']) ? $_POST['url'] : null;
            $miniprogram = isset($_POST['miniprogram']) ? $_POST['miniprogram'] : null;

            sendWxMsgTemplate($access_token, $touser, $template_id, $data, $url, $miniprogram);
            break;


        //设置所属行业
        case $region[5]:// POST setIndustry
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            $industry_id1 = isset($_POST['industry_id1']) ? $_POST['industry_id1'] : null;
            $industry_id2 = isset($_POST['industry_id2']) ? $_POST['industry_id2'] : null;
            setIndustry($access_token, $industry_id1, $industry_id2);
            break;

        //获取设置的行业信息
        case $region[6]:// GET getIndustry
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            getIndustry($access_token);
            break;

        //添加模板并获取模板id
        case $region[7]://POST addTemplate
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            $template_id_short = isset($_POST['template_id_short']) ? $_POST['template_id_short'] : null;
            addTemplate($access_token, $template_id_short);
            break;

        //获取模板列表
        case $region[8]://GET getAllPrivateTemplate
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            getAllPrivateTemplate($access_token);
            break;

        //删除模板消息
        case $region[9]://POST delPrivateTemplate
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : null;
            delPrivateTemplate($access_token,$template_id);
            break;

        //获取jsapi_ticket
        case $region[10]: //GET getWxJsapiTicket
            $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : null;
            getWxJsapiTicket($access_token);
            break;

        default:
            throw new Exception("action missing", 500);
    }
} catch (Exception $e) {
    echo $e->getMessage();
    httpCode($e->getCode());
}
