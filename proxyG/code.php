<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-4-28
 * Time: 17:12
 */
require "./http.php";
require "./config.php";

$code = isset($_GET['code'])?$_GET['code']:null;

jump2UrlInTime(REDIRECT_URI."?code=$code");

