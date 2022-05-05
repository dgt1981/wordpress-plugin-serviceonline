<?php
class ESOS_Front{

	public static $lg = array();
	public static $t = null;
	
	public static  $uinfo = array();
	
	public function __construct(){
	}
	
	private static function getUserInfo(){
		if (!empty(self::$uinfo)) return self::$uinfo;
		$_uinfo = wp_get_current_user();
		if (empty($_uinfo->ID)){
			$_member['uid'] = 'v'.substr(md5($_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"]),10,5);
			$_member['name'] = self::$lg['visitor'].$_member['uid'];
			$_member['lvl'] = ESOS_Admin::$role_ver;
		}else{
			$cfg = get_option(ESOS_Admin::$optname);
			$_member['uid'] = $_uinfo->ID;
			$_member['name'] = $_uinfo->display_name;
			$_member['lvl'] = (!empty($cfg['severinfo'])) ? (self::checkService($cfg['severinfo'],$_member['uid']) ?  ESOS_Admin::$role_ser : ESOS_Admin::$role_uer) : ESOS_Admin::$role_uer;
		}
		$_member['v'] = self::setUinfoSign($_member, wp_create_nonce());
		$_member['ip'] = self::getUserIP();
		self::$uinfo = $_member;
		return $_member;
	}
	
	private static function getUserIP() {
		if (!empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}  	
	
	private static function getAvatarUrl($avatar_html='') {
		if (empty($avatar_html)) return EYOUNG_SERVICERONLINE_URL.'assets/images/ver.png';
		preg_match('/src=["|\'](.+)[\&|"|\']/U', $avatar_html, $matches);
		if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
			return esc_url_raw($matches[1]);
		}
		return EYOUNG_SERVICERONLINE_URL.'images/ver.png';
	}
	
	private static function setUinfoSign($uinfo, $hash){
		return md5($uinfo['uid'].$uinfo['lvl'].$hash);
	}
	
	private static function checkUinfoSign($uinfo, $hash){
		if (empty($uinfo['v'])) return false;
		return ($uinfo['v'] == self::setUinfoSign($uinfo, $hash)) ? true : false;
	}
		
	private static function checkService($severinfo, $uid){	
		return in_array($uid, array_keys($severinfo)) ? true : false;
	}

	private static function checkValidUid($uid){
		if (!is_numeric($uid) && (strlen($uid) != 6)){
			return false;				
		}else if(!is_numeric($uid) && (strlen($uid) == 6) && (substr($uid,0,1) == 'v')) {
			preg_match_all('/[a-zA-Z0-9]{6}/u',$uid, $s);
			return $s[0][0];
		}else if(is_numeric($uid) && (strlen($uid) != 6)){
			return intval($uid);
		}
	}

	private static function checkValidRid($ary){
		asort($ary, 2);
		return implode('_', $ary);
	}
	
	private static function getUserFilter($pinfo){
		$ret['uid'] = self::checkValidUid($pinfo['uid']);
		$ret['name'] = sanitize_user($pinfo['name']);
		$ret['lvl'] = intval($pinfo['lvl']);
		if (!empty($pinfo['oid'])) $ret['oid'] = self::checkValidUid($pinfo['oid']);
		if (!empty($pinfo['ip'])) $ret['ip'] = (filter_var($pinfo['ip'], FILTER_VALIDATE_IP)) ? $pinfo['ip'] : '';
		if (!empty($pinfo['v'])) $ret['v'] = filter_var($pinfo['v'], FILTER_SANITIZE_STRING);
		return $ret;
	}
	
	public static function setAjx(){

		if (!self::checkUinfoSign($_POST['uinfo'], $_POST['formhash'])){
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
		
		switch ($_POST['action']) {
			case 'eys_getChat':
				self::getChat();
				break;
			case 'eys_getOfflineMsg':
				self::getOfflineMsg();
				break;
			case 'eys_setTrack':
				self::setTrack();
				break;
			case 'eys_getTrack':
				self::getTrack();
				break;				
			case 'eys_setContent':
				self::setContent();
				break;
			case 'eys_setChatView':
				self::setChatView();
				break;
			case 'eys_setUserField':
				self::setUserField();
				break;
			case 'eys_getAddressByIp':
				self::getAddressByIp();
				break;
			case 'eys_sendtomail':
				self::sendtomail();
				break;
			case 'eys_setOfflineReply':
				self::setOfflineReply();
				break;
			case 'eys_getOfflineReply':
				self::getOfflineReply();
				break;
				
				
				
				/*
			case 'eys_uploadImage':
				self::uploadImage();
				break;
			case 'eys_uploadFile':
				self::uploadFile();
				break;
				*/
		}

//		ESOS_Admin::retJson($_POST);		
		//exit("ok");
	}
	
	private static function setOfflineReply(){
		$uinfo = self::getUserFilter($_POST['uinfo']);
		if ($uinfo['lvl'] != ESOS_Admin::$role_ser){
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_setting.php';		
		
		$content = substr(sanitize_textarea_field(trim($_POST['content'])), 0, 1000);
		ESOS_Setting::setSetting($uinfo['uid'],'offline_reply', $content);
		
		$_res = ESOS_Setting::getAllSetting();
		
		$res = [];
		if (!empty($_res)){
			foreach ($_res as $k => $v){
				$res[$v->cate][$v->uid] = $v->content;
			}
			$_opt = get_option(ESOS_Admin::$optname);
			$_opt['setting'] = $res;
			update_option(ESOS_Admin::$optname, $_opt);			
		}
	
		$ret['code'] = 1;
		$ret['data'] = $content;
		ESOS_Admin::retJson($ret);		
	}
	
	private static function getOfflineReply(){
		$uinfo = self::getUserFilter($_POST['uinfo']);
		if ($uinfo['lvl'] != ESOS_Admin::$role_ser){
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
		
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_setting.php';		
		$ret['code'] = 1;
		$ret['data'] = ESOS_Setting::getSetting($uinfo['uid'],'offline_reply');
		ESOS_Admin::retJson($ret);
	}
		
	private static function setContent(){

		$uinfo = self::getUserFilter($_POST['uinfo']);
		$oinfo = self::getUserFilter($_POST['oinfo']);
		
		$rid = self::checkValidRid(array($uinfo['uid'],$oinfo['uid']));
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
	
		$adata['content'] = substr(sanitize_textarea_field(trim($_POST['content'])), 0, 1000);
		
		if (empty($adata['content'])){
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
		
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_content.php';
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_customer.php';
		
		$adata['uid'] = self::checkValidUid($uinfo['uid']);
		$adata['oid'] = self::checkValidUid($oinfo['uid']);

		$adata['sendflag'] = ($uinfo['lvl'] == ESOS_Admin::$role_ser) ? 1 : 0;
		$adata['status'] = intval($_POST['status']);
		$adata['ctime'] = time();
		$adata['cid'] = ESOS_Content::add($adata);
		$adata['rid'] = $rid;
		if ($adata['cid']){
			$uinfo['ctime'] = $adata['ctime'];
			ESOS_Customer::add($uinfo, false);
		}
		
		$adata['content'] = wp_unslash($adata['content']);
		
		$ret['code'] = 1;
		$ret['data'] = $adata;
		ESOS_Admin::retJson($ret);
	}
	private static function setChatView(){

		$uinfo = self::getUserFilter($_POST['uinfo']);
		$oinfo = self::getUserFilter($_POST['oinfo']);
		
		$rid = self::checkValidRid(array($uinfo['uid'],$oinfo['uid']));
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			exit(json_encode($ret));	
		}
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_content.php';	
		if (!empty($_POST['cids'])){
			foreach ($_POST['cids'] as $k => $v){
				$_cids[] = intval($v);
			}			
			if ($_cids){
				$cids = implode(',', $_cids);
				ESOS_Content::update('status=1',"cid in({$cids})");
				$ret['code'] = 1;
				$ret['data'] = $_cids;
				ESOS_Admin::retJson($ret);				
			}
		}
	
		$ret['code'] = 0;
		ESOS_Admin::retJson($ret);
	}
	public static function setUserField(){
		if (empty($_POST['key']) || !isset($_POST['val'])){
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
	
		$uinfo = self::getUserFilter($_POST['uinfo']);
		$ret['code'] = 1;
		$key = '';
		switch ($_POST['key']){
			case 'rate' :
				$val = intval($_POST['val']);
				$ret['code'] = $val ? 1 : 0;
				$key = 'rate';
				break;
			case 'name' :
				$val = sanitize_text_field($_POST['val']);
				$ret['code'] = 1;
				$key = 'name';
				break;
			case 'phone':
				$val = filter_var($_POST['val'], FILTER_SANITIZE_NUMBER_FLOAT);
				$ret['code'] = 1;
				$key = 'phone';
				break;
			case 'email':
				$val = sanitize_email($_POST['val']);
				$ret['code'] = is_email($val) ? 1 : 0;
				$key = 'email';
				break;
			case 'qq':
				$val = intval($_POST['val']);			
				$ret['code'] = $val ? 1 : 0;
				$key = 'qq';
				break;
			case 'address':
				$val = sanitize_text_field($_POST['val']);
				$ret['code'] = 1;
				$key = 'address';
				break;
			case 'mark':
				$val = sanitize_text_field($_POST['val']);
				$ret['code'] = 1;
				$key = 'mark';
				break;
			default :
				$ret['code'] = 0;
				$val = '';
				$key = '';
				break;
		}
	
		if ($ret['code'] == 1){
			include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_customer.php';	
			include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_editlog.php';	
			$adata[$key] = $val;
			$adata['ctime'] = time();
			$sdata['uid'] = $uinfo['oid'];
			ESOS_Customer::update($adata, $sdata);
			$pdata['oid'] = $uinfo['oid'];
			$pdata['uid'] = $uinfo['uid'];
			$pdata['key'] = $key;
			$pdata['val'] = $val;
			$pdata['ctime'] = $adata['ctime'];
			ESOS_Editlog::add($pdata);
			$ret['uid'] = $uinfo['oid'];
		}

		$ret['key'] = $key;
		$ret['val'] = $val;
		ESOS_Admin::retJson($ret);
	}
	private static function getAddressByIp(){

		$uinfo = self::getUserFilter($_POST['uinfo']);
		$oinfo = self::getUserFilter($_POST['oinfo']);
		
		$rid = self::checkValidRid(array($uinfo['uid'],$oinfo['uid']));
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
	
		if (empty($oinfo['ip'])){
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
		
		$address = $location = '';
		$_opt = get_option(ESOS_Admin::$optname);
		if (!empty($_opt['ip2address'])){
			$response = wp_remote_get($_opt['ip2address'].$oinfo['ip']."?lang=zh-CN");
			if (is_wp_error( $response ) ) {
				$ret['code'] = 0;
				$ret['msg'] = $response->get_error_message();
				ESOS_Admin::retJson($ret);
			} else {
				$_res = wp_remote_retrieve_body($response);
				$_res = json_decode($_res, true);
				if ($_res['status'] == 'success'){
					$address = $_res['country'].$_res['regionName'].$_res['city'];
					$location = $_res['lat'].','.$_res['lon'];
				}
			}
		}
	
		$ret['code'] = 1;
		$ret['address'] = $address;
		$ret['location'] = $location;
		ESOS_Admin::retJson($ret);
	}
	private static function sendtomail(){}	
	public static function uploadImage(){
		$uinfo = self::getUserFilter(json_decode(stripslashes($_POST['uinfo']), true));
		$oinfo = self::getUserFilter(json_decode(stripslashes($_POST['oinfo']), true));		
		
		$rid = self::checkValidRid(array($uinfo['uid'],$oinfo['uid']));
		
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['rid'] = $rid;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}

		$_src = wp_get_attachment_metadata(media_handle_upload('file',0));
				
		if (!empty($_src['file'])){
			$wp_dir = wp_upload_dir();
			$storepath = $wp_dir['url'].'/';
			$ret['thumb'] = (!empty($_src['sizes']['thumbnail']['file'])) ? ($storepath.$_src['sizes']['thumbnail']['file']) : '';
			$ret['large'] = (!empty($_src['sizes']['large']['file'])) ? ($storepath.$_src['sizes']['large']['file']) : ((!empty($_src['sizes']['medium']['file'])) ? ($storepath.$_src['sizes']['medium']['file']) : '');
			$ret['thumb'] = $ret['thumb'] ? $ret['thumb'] : $wp_dir['baseurl'] .'/'. $_src['file'];
			$ret['large'] = $ret['large'] ? $ret['large'] : $wp_dir['baseurl'] .'/' . $_src['file'];
			$ret['code'] = 1;
			ESOS_Admin::retJson($ret);
		}

		$ret['code'] = 0;
		ESOS_Admin::retJson($ret);
	}
	public static function uploadFile(){

		$uinfo = self::getUserFilter(json_decode(stripslashes($_POST['uinfo']), true));
		$oinfo = self::getUserFilter(json_decode(stripslashes($_POST['oinfo']), true));		
		
		$rid = self::checkValidRid(array($uinfo['uid'],$oinfo['uid']));
		
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}

		$ret['code'] = 1;
		$ret['url'] = wp_get_attachment_url(media_handle_upload('file',''));
		ESOS_Admin::retJson($ret);	
	}
	private static function setTrack(){
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_track.php';	
		$uinfo = self::getUserFilter($_POST['uinfo']);
		$adata['uid'] = $uinfo['uid'];
		$adata['title'] = (!empty($_POST['pinfo']['title'])) ? sanitize_text_field($_POST['pinfo']['title']) : '';
		$adata['url'] = (!empty($_POST['pinfo']['url'])) ? esc_url_raw($_POST['pinfo']['url']) : '';
		$adata['ctime'] = (!empty($_POST['pinfo']['time'])) ? intval($_POST['pinfo']['time']) : time();
		ESOS_Track::add($adata);
		ESOS_Admin::retJson(array("code"=>1));
	}

	private static function getTrack(){
		$uinfo = self::getUserFilter($_POST['uinfo']);
		$rid = self::checkValidRid(array($uinfo['uid'],$uinfo['oid']));
		
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_track.php';			
		$_data =ESOS_Track::getAll($uinfo['oid']);
		$ret['code'] = 1;
		$ret['msg'] = $uinfo['oid'];
		$ret['data'] = $_data;
		exit(json_encode($ret));	
	}
	
	private static function getOfflineMsg(){
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_content.php';
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_customer.php';		
		$uinfo = self::getUserFilter($_POST['uinfo']);

		$ctime = empty($_POST['ctime']) ? '' : intval($_POST['ctime']);
		$_data =  ESOS_Content::getOfflineMsg($uinfo['uid'], $ctime);
		$uids = $exisid = [];
		if (!empty($_data)) $uids = array_column($_data,'uid');
		
		if (!empty($_POST['exisid'])){
			foreach ($_POST['exisid'] as $k => $v){
				$_v  = sanitize_user($v);
				if (!empty($_v)) array_push($exisid, $_v);
			}
		}else{
			$exisid = [];
		}
		$uids = array_merge($uids, $exisid);

		$member = [];
		if (!empty($uids)){
			$_member = ESOS_Customer::get($uids);
			if (!empty($_member)){
				$member = array_reduce($_member, function(&$member, $v){
					$member[$v->uid] = $v;
					return $member;
				});
			}
		}
		$ret['code'] = 1;
		$ret['data'] = $_data;
		$ret['member'] = $member;
		ESOS_Admin::retJson($ret);
	}
	private static function getChat(){		
		$uinfo = self::getUserFilter($_POST['uinfo']);
		$oinfo = self::getUserFilter($_POST['oinfo']);
		
		$rid = self::checkValidRid(array($uinfo['uid'],$oinfo['uid']));
		
		if ($rid !== $_POST['rid']) {
			$ret['code'] = 0;
			$ret['msg'] = self::$lg['tip_reterr'];
			ESOS_Admin::retJson($ret);
		}	
	
		$uid = self::checkValidUid($uinfo['uid']);
		$oid = self::checkValidUid($oinfo['uid']);
		
		include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_content.php';
	
		$cid = empty($_POST['cid']) ? 0 : intval($_POST['cid']);
		$curpage = empty($_POST['curr']) ? 1: intval($_POST['curr']);
		$pageCount = empty($_POST['pageCount']) ? 1 : intval($_POST['pageCount']);
		$limit = 10;
		if($pageCount == 1){
			$total = ESOS_Content::getChatCount($uid, $oid, $cid);
			$pageCount = ceil($total/$limit);
		}
		$ret['pageCount'] = $pageCount;
		$offset = ($curpage - 1)*$limit;
		$ret['code'] = 1;
		$_data = ESOS_Content::getChat($uid, $oid, $cid,$limit,$offset);	
		$ret['data'] = $_data;
		ESOS_Admin::retJson($ret);
	}

	public static function init(){		
		$cfg = get_option(ESOS_Admin::$optname);
		if (wp_is_mobile()){
			if (empty($cfg['mobileopen'])) return;
		}else{
			if (empty($cfg['isopen'])) return;
		}
		include_once EYOUNG_SERVICERONLINE_PATH.'/lang/front.php';
		self::$lg = $lang;
		
		add_action('plugins_loaded', array(__CLASS__, 'renderScript'));			
	}
	
	public static function renderScript(){
	
		$uinfo = self::getUserInfo();
						
		add_action('wp_ajax_eys_setContent', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_getChat', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_getOfflineMsg', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_setChatView', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_setTrack', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_getTrack', array(__CLASS__, 'setAjx'));		
		add_action('wp_ajax_eys_setUserField', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_setOfflineReply', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_getOfflineReply', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_getAddressByIp', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_sendtomail', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_eys_uploadImage', array(__CLASS__, 'uploadImage'));
		add_action('wp_ajax_eys_uploadFile', array(__CLASS__, 'uploadFile'));
		add_action('wp_ajax_nopriv_eys_setContent', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_getChat', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_getOfflineMsg', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_setChatView', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_setTrack', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_setUserField', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_sendtomail', array(__CLASS__, 'setAjx'));
		add_action('wp_ajax_nopriv_eys_uploadImage', array(__CLASS__, 'uploadImage'));
		add_action('wp_ajax_nopriv_eys_uploadFile', array(__CLASS__, 'uploadFile'));

		add_filter('wp_handle_upload_prefilter', function($file){
			$_t = explode(".", $file['name']);
			$_c = count($_t) -1;			
			$file['name'] = time()."_".mt_rand(100,999).".".$_t[$_c];
			return $file; 
		});				
		
		if (self::$uinfo['lvl'] == ESOS_Admin::$role_ser){
			add_filter('template_include',array(__CLASS__, 'getCenterPage'));			
		}else{
			add_filter('the_content', array(__CLASS__, 'cantEnterTips'));
			add_action('wp_footer', array(__CLASS__, 'renderUserScript'));
		}
		add_action('wp_enqueue_scripts', array(__CLASS__,'setLoadScripts'), 11);		
	}
	
	public static function setLoadScripts(){
		wp_enqueue_style( 'eyservice_eyui_css', EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS.'/css/eyui.css');
		wp_enqueue_style( 'eyservice_service_css', EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS.'/css/service.css');		
		wp_enqueue_script('eyservice_eyui_js', EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS.'/eyui.js');			
	}
	
	public static function renderUserScript(){	
		$uinfo = self::getUserInfo();
		$cfg = get_option(ESOS_Admin::$optname);
        $sercfg = (!empty($cfg['setting'])) ? json_encode($cfg['setting']) : [];
		if (!empty($cfg['severphone'])) $cfg['severphone'] = json_encode(explode(",", $cfg['severphone']));
		if (!empty($cfg['severemail'])) $cfg['severemail'] = json_encode(explode(",", $cfg['severemail']));
		if (!empty($cfg['severqq'])) $cfg['severqq'] = json_encode(explode(",", $cfg['severqq']));
		if (!empty($cfg['severwechat'])) $cfg['severwechat'] = json_encode(explode(",", $cfg['severwechat']));
		$check = md5($cfg['license'].$uinfo['uid']);	
		$lang = self::$lg;		
		if (wp_is_mobile())
			include_once EYOUNG_SERVICERONLINE_PATH . '/assets/scripth5.php';
		else
			include_once EYOUNG_SERVICERONLINE_PATH . '/assets/scriptpc.php';		
	}
	
	public static function cantEnterTips($content){
		if (is_page('servicecenter')){
			return "<div style='font-size:18px;text-align:center;'>非客服人员无法访问</div>";
		}		
		return $content;
	}

	public static function getCenterPage( $template ) {	
		if (is_page('servicecenter')){
			$uinfo = self::getUserInfo();
			$cfg = get_option(ESOS_Admin::$optname);
			$check = md5($cfg['license'].$uinfo['uid']);
			$lang = self::$lg;
		 	include_once EYOUNG_SERVICERONLINE_PATH . '/assets/servicecenter.php';			
			return false;
		}
		return $template;
	}	
}
