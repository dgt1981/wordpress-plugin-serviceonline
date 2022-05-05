<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Setting{

	public static $tname = 'ey_serviceonline_setting';

	public static $_instance = null;
	
	public static function instance(){
		if( is_null (self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
		
	public static function getSetting($uid, $cate){
		global $wpdb;	
		return $wpdb->get_row( "SELECT content FROM ".$wpdb->prefix.self::$tname ." WHERE uid=".$uid." and cate='". $cate ."'");
	}

	public static function getAllSetting(){
		global $wpdb;	
		$sql = "SELECT uid,cate,content FROM ".$wpdb->prefix.self::$tname;
		return $wpdb->get_results($sql);
	}

	public static function setSetting($uid, $cate, $content){
		global $wpdb;	
		$_res = self::getSetting($uid, $cate);
		if (!empty($_res->content)){
			$wpdb->query("UPDATE ".$wpdb->prefix.self::$tname." set content='".$content."' WHERE uid='".$uid."' and cate='".$cate."'");		
		}else{
			$adata['uid'] = $uid;
			$adata['cate'] = $cate;
			$adata['content'] = $content;
			$wpdb->insert($wpdb->prefix.self::$tname, $adata);
			return $wpdb->insert_id;
		}
	}	
	
}

?>