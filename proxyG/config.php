<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-4-28
 * Time: 11:43
 */

define('ADDIP','');
define('APPSECRET','');



//获取code之后需要重定向的网址
define("REDIRECT_URI",'');

//限制的接口转发范围
define('ACTION_REGION','return array(
            "getWxAccessToken",
            "refreshWxToken",
            "getWxUserinfo",
            
            "getWxAuthAccessToken",
            
            "sendWxMsgTemplate",
            "setIndustry",
            "getIndustry",
            "addTemplate",
            "getAllPrivateTemplate",
            "delPrivateTemplate",
            
            
            "getWxJsapiTicket"
            );
       ');
