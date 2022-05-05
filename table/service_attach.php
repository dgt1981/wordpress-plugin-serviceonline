<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ESOS_Attach{

	public static $tname = 'ey_serviceonline_attach';

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
		//return $wpdb->insert_id;
	}	


	public static function count($rid,  $store) {		
		global $wpdb;
		$condition = ' WHERE rid='.$rid;
		if($store >= 0) $condition = " AND store = ".$store;
		$sql = "SELECT * FROM ".$wpdb->prefix.self::$tname.$condition." order by ctime desc";
		return $wpdb->get_row($sql);
	}

	public static function get($rid='', $store='', $start=0, $ppp=10) {
		global $wpdb;
		$condition = " WHERE 1 ";
		$condition .= (intval($rid)) ? ' AND rid='.$rid : '';
		$condition .= (intval($store)) ? ' AND store='.$store : '';		
		$limit = (isset($start) && !empty($ppp)) ? " LIMIT ".$start.", ".$ppp : '';
		$sql = "SELECT * FROM ".$wpdb->prefix.self::$tname.$condition." ORDER BY ctime desc " . $limit;
		return $wpdb->get_results($sql);
	}

	public static function del($rid, $id) {
		global $wpdb;
		$_c = "rid='".$rid."' and id in(".$id.")";
		$wpdb->delete($wpdb->prefix.self::$tname, $_c);
	}
}
?>