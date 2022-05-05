<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Content{

	public static $tname = 'ey_serviceonline_content';

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

	
	public static function del($cid = '') {
		global $wpdb;
		if (empty($cid)) return;
		$wpdb->query("delete from ".$wpdb->prefix.self::$tname." where cid in(".$cid.")");
	}
	
	public static function update($mstr, $cstr){
		global $wpdb;
		$wpdb->query("update ". $wpdb->prefix.self::$tname." set ".$mstr." where ".$cstr);
	}
		
	public static function count($uid='', $content='') {
		global $wpdb;
		$_c = '';
		if($uid != ''){
			$condition = " AND ((uid = ".$uid.") OR (oid = ".$uid."))";
		}
		if($content != ''){
			$_c = " AND content like '%".$content."%'";
		}
		$sql = "SELECT count(*) as c FROM ".$wpdb->prefix.self::$tname." WHERE 1 ".$_c;
		return $wpdb->get_var($sql);
	}
	
	
	
	public static function getAll($field="*", $content='', $limit=10, $offset=0){
		global $wpdb;
		$_c = '';
		if($content != ''){
			$_c = " AND content like '%".$content."%'";
		}
		$sql = "SELECT ". $field ." FROM ".$wpdb->prefix.self::$tname." WHERE 1 ".$_c." ORDER BY ctime desc LIMIT " . $limit .' OFFSET '.$offset;
		return $wpdb->get_results($sql);
	}	

	public static function getBySearch($uid,$content, $start, $ppp){
		$condition = '';
		if($uid != ''){
			$condition = " AND ((uid = ".$uid.") OR (oid = ".$uid."))";
		}
		if($content != ''){
			$condition = " AND (content like '%".$content."%')";
		}
		$sql = "SELECT * FROM ".$wpdb->prefix.self::$tname." WHERE 1 ".$condition." ORDER BY ctime desc LIMIT ".$start.", ".$ppp;
		return $wpdb->get_results($sql);
	}

	public static function getChatCount($uid, $oid, $cid=''){
		global $wpdb;
		$c = " where (uid='{$uid}' and oid='{$oid}') or (uid='{$oid}' and oid='{$uid}') ";
		$c .= empty($cid) ? '' : (' and cid < ' . $cid);
		$sql = "SELECT count(*) as c FROM ".$wpdb->prefix.self::$tname. $c ." ORDER BY cid desc";
		return $wpdb->get_var($sql);
	}

	public static function getChat($uid, $oid, $cid='', $limit = 10,$offset=0){
		global $wpdb;
		$c = " where ((uid='{$uid}' and oid='{$oid}') or (uid='{$oid}' and oid='{$uid}')) ";
		$c .= empty($cid) ? '' : (' and cid < ' . intval($cid));
		$sql = "SELECT cid,uid,oid,content,status,ctime FROM ".$wpdb->prefix.self::$tname . $c ." ORDER BY cid desc LIMIT " . $limit .' OFFSET '.$offset;
		return $wpdb->get_results($sql);
	}
	
	public static function getOfflineMsg($id, $ctime=''){
		global $wpdb;
		$c = " where oid='{$id}' and `status`=0";
		$c .= empty($ctime) ? '' : (' and ctime > ' . $ctime);
		$sql = "SELECT cid,uid,content,ctime,count(*) as total  FROM ".$wpdb->prefix.self::$tname. $c ."  group by uid order by ctime desc";
		return $wpdb->get_results($sql);
	}
}
?>
