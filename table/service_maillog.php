<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Maillog{

	public static $tname = 'ey_serviceonline_maillog';

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

	public static function del($id) {
		global $wpdb;
		$_c = 'logid in ('.$id.')';
		$wpdb->delete($wpdb->prefix.self::$tname, $_c);
	}
	
	public static function count($mail='', $content='') {
		global $wpdb;
		$condition = '';
		if($mail != ''){
			$condition .= " AND (mail='".$mail."')";
		}
		if($content != ''){
			$condition .= " AND (content like '%".$content."%')";
		}
		$sql = "SELECT count(*) as c FROM ".$wpdb->prefix.self::$tname." WHERE 1 ".$condition." order by ctime desc";
		return $wpdb->get_var($sql);
	}

	public static function getAll($mail,$content, $start, $ppp){
		global $wpdb;
		$condition = '';
		if($mail != ''){
			$condition .= " AND (mail='".$mail."')";
		}
		if($content != ''){
			$condition .= " AND (content like '%".$content."%')";
		}
		$sql = "SELECT * FROM ".$wpdb->prefix.self::$tname." WHERE 1 ".$condition." ORDER BY ctime desc LIMIT ".$start.", ".$ppp;
		return $wpdb->get_results($sql);
	}

}

?>