<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-4-29
 * Time: 10:41
 */

Class Auth implements IAuth
{
    public function isVaildSign($sign)
    {
        $day = date("Y-m-d-H");
        if ($sign != md5($day)) {
            throw new Exception("sign not vaild", 500);
        }
    }

    public function isVaildAction($action)
    {
        $actionRegion = eval(ACTION_REGION);
        if (!in_array($action, $actionRegion)) {
            throw new Exception("action not vaild", 500);
        }

    }
}

