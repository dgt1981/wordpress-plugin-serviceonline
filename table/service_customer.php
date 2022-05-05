<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Customer{

	public static $tname = 'ey_serviceonline_customer';

	public static $_instance = null;
	
	public static function instance(){
		if( is_null (self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function add($pdata){
		global $wpdb;
		$adata['uid'] = $pdata['uid'];
		$adata['name'] = $pdata['name'];
		$adata['lvl'] = $pdata['lvl'];
		$adata['dv'] = empty($pdata['dv']) ? 'web' : $pdata['dv'];
		$adata['ip'] = empty($pdata['ip']) ? '' : $pdata['ip'];	
		$adata['ctime'] = empty($pdata['ctime']) ? time() : $pdata['ctime'];
		$wpdb->replace($wpdb->prefix.self::$tname, $adata);
		return $wpdb->insert_id;
	}

	public static function get($uid, $field="uid,name,lvl,ip,dv,address,location,email,qq,phone,rate,mark,ctime,locked"){
		if (empty($uid)) return false;
		global $wpdb;
		$_s = is_array($uid) ? ("'".implode("','", $uid)."'") : $uid;
		$_c = " WHERE uid in ({$_s})";
		$sql = "SELECT {$field} FROM ".$wpdb->prefix.self::$tname. $_c;
		return $wpdb->get_results($sql);
	}
	
	public static function getAll($key='', $val='', $limit = 10,$offset=0){
		global $wpdb;
//		$c = (empty($key) || empty($val)) ? '' :  " WHERE {$key} like '%{$val}%' ";
		$c = (empty($key) || empty($val)) ? '' :  " WHERE {$key}='{$val}'";
		$sql = "SELECT id,uid,name,lvl,ip,dv,address,location,email,qq,phone,rate,mark,ctime FROM ".$wpdb->prefix.self::$tname. $c ." ORDER BY id desc LIMIT " . $limit .' OFFSET '.$offset;
		return $wpdb->get_results($sql);
	}

	public static function count($key='', $val=''){
		global $wpdb;
		$c = (empty($key) || empty($val)) ? '' :  " WHERE {$key} like '%{$val}%' ";
		$sql = "SELECT count(*) as c FROM ".$wpdb->prefix.self::$tname. $c;
		return $wpdb->get_var($sql);
	}

	public static function update($pdata, $condition = array()){
		global $wpdb;
		$wpdb->update($wpdb->prefix.self::$tname, $pdata, $condition);
	}
	
	public static function del($uid = '') {
		global $wpdb;
		if (empty($uid)) return;
		$wpdb->query("delete from ".$wpdb->prefix.self::$tname." where uid in(".$uid.")");
	}
}

?>