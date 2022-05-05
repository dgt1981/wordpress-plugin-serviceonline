<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Track{

	public static $tname = 'ey_serviceonline_track';

	public static $_instance = null;
	
	public static function instance(){
		if( is_null (self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public static function add($adata){		
		global $wpdb;
		$wpdb->insert($wpdb->prefix.self::$tname, $adata);
		return $wpdb->insert_id;
	}
	
	public static function getAll($uid,  $limit = 10, $offset = 0){
		global $wpdb;
		$_c = " WHERE uid='{$uid}'  ORDER BY id desc LIMIT " . $limit .' OFFSET '.$offset;;
		$sql = "SELECT title,url,ctime as time FROM ".$wpdb->prefix.self::$tname. $_c;
		return $wpdb->get_results($sql);
	}

	public static function update($pdata, $condition = ''){
		global $wpdb;
		$wpdb->update($wpdb->prefix.self::$tname, $pdata, $condition);
	}
	
	public static function del($uid = '') {
		global $wpdb;
		$_c = empty($uid) ? '' : ' uid in ('.$uid.')';
		$wpdb->delete($wpdb->prefix.self::$tname, $_c);
	}
	
}
?>