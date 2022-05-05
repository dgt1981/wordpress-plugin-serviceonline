<?php
/*
Plugin Name: Eyoung在线客服系统
Description: 为WordPress网站提供网页版可在线即时沟通的客服系统.
Author: dgt
Version: 1.0
Author URI: https://wordpress.yuyaoit.net/
*/


if(!defined('ABSPATH')){
    return;
}

define('EYOUNG_SERVICERONLINE_PATH',dirname(__FILE__));
define('EYOUNG_SERVICERONLINE_FILE',__FILE__);
define('EYOUNG_SERVICERONLINE_JS', 'jsdev');
define('EYOUNG_SERVICERONLINE_URL',plugin_dir_url(EYOUNG_SERVICERONLINE_FILE));

require_once EYOUNG_SERVICERONLINE_PATH.'/classes/admin.class.php';
require_once EYOUNG_SERVICERONLINE_PATH.'/classes/front.class.php';

ESOS_Admin::init();
ESOS_Front::init();