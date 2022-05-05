<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Editlog{

	public static $tname = 'ey_serviceonline_editlog';

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

	public static function del($uid = '', $active='edit') {
		global $wpdb;
		$_c = (!empty($active)) ? " WHERE active='{$active}'" : " WHERE 1 "; 
		$_c .= empty($uid) ? '' : (' AND uid in ('.$uid.')');
		$wpdb->delete($wpdb->prefix.self::$tname, $_c);
	}

	public function get($oid,$uid='', $active='edit', $key='', $limit = 10, $offset = 0){
		global $wpdb;
		$_c = " WHERE active='{$active}' AND oid='{$oid}'";
		$_c .= (!empty($key)) ? " AND key='{$key}'" : "";		
		$_c .= (!empty($uid)) ? " AND uid='{$uid}'" : "";
		$sql = "SELECT title,url,ctime as time FROM ".$wpdb->prefix.self::$tname. $_c . "ORDER BY id desc LIMIT " . $limit .' OFFSET '.$offset;
		return $wpdb->get_results($sql);
	}
	

}
?>