<?php

class ESOS_Admin{

	public static $name = 'ey_serviceonline';
	public static $optname = 'eyser_options';
	public static $api_reg = 'https://wordpress.yuyaoit.net';	
	public static $api_pay = 'https://service.yuyaoit.cn/wpplugin/index?app=ey-serviceonline';	
	public static $role_ser = 88;
	public static $role_uer = 0;
	public static $role_ver = -1;
	
	public function __construct(){}
	
	public static function retJson($data){
		header("Content-type: application/json;charset=utf-8");
		exit(json_encode($data));
	}

	public static function manageSetting(){
		
		if (!empty($_POST['data'])){
			$nonce=wp_create_nonce();
			if(!isset($_POST["formhash"]) || $_POST["formhash"] !== $nonce){
				$ret['code'] = 0;
				$ret['msg'] = 'post error!';
				self::retJson($ret);
			}


//				$ret['code'] = 00;
//				$ret['msg'] = sanitize_text_field($_POST['data']['title']);
//				self::retJson($ret);


			$optdata['isopen'] = (isset($_POST['data']['isopen'])) ? 1 : 0;
			$optdata['mobileopen'] = (isset($_POST['data']['mobileopen'])) ? 1 : 0;
			$optdata['title'] = wp_unslash(sanitize_text_field($_POST['data']['title']));
			$optdata['imageupload'] = (isset($_POST['data']['imageupload'])) ? 1 : 0;
			$optdata['imagelimit'] = intval($_POST['data']['imagelimit']);
			$optdata['fileupload'] = (isset($_POST['data']['fileupload'])) ? 1 : 0;
			$optdata['filelimit'] = intval($_POST['data']['filelimit']);
			$optdata['fileext'] = sanitize_text_field($_POST['data']['fileext']);
			$optdata['position'] = sanitize_key(substr($_POST['data']['position'],0,2));
			$optdata['width'] = intval($_POST['data']['width']);
			$optdata['backcolor'] = sanitize_hex_color($_POST['data']['backcolor']);
			$optdata['iconcolor'] = sanitize_hex_color($_POST['data']['iconcolor']);
			$optdata['onlinewidth'] = intval($_POST['data']['onlinewidth']);
			$optdata['onlineheight'] = intval($_POST['data']['onlineheight']);
			$optdata['onlineoffset'] = sanitize_key(substr($_POST['data']['onlineoffset'],0,4));
			$optdata['mobileposition'] = sanitize_key(substr($_POST['data']['mobileposition'],0,2));
			$optdata['mobileoffset'] = sanitize_key(substr($_POST['data']['mobileoffset'],0,4));
			$optdata['mobileheight'] = sprintf('%u%%', sanitize_text_field($_POST['data']['mobileheight']));
			$_opt = get_option(self::$optname);
			foreach ($optdata as $k => $v) $_opt[$k] = $v;
			update_option(self::$optname, $_opt);
			$ret['code'] = 1;
			$ret['opt'] = $optdata;
			self::retJson($ret);
		}

		$_options = get_option(self::$optname);
		include_once(EYOUNG_SERVICERONLINE_PATH . '/assets/setting.php');
	}
	public static function manageSever(){
		if (!empty($_POST['data'])){
			if(!isset($_POST["formhash"]) || $_POST["formhash"] !== wp_create_nonce()){
				$ret['code'] = 0;
				$ret['msg'] = 'post error!';
				self::retJson($ret);
			}
			
			$optdata['severids'] = '';
			if (!empty($_POST['data']['severids'])){
				if (strpos($_POST['data']['severids'],',') !== false){
					$_severids = explode(",", sanitize_text_field($_POST['data']['severids']));
					foreach ($_severids as $k => $v){
						if (!empty($v)){
							$_uid = sanitize_user($v);
							$_u = get_users(array('orderby' => 'user_registered','offset' => 0,'number' => 1,'search'=>$_uid, 'fields' => array('ID','Display_name')));
							if ($_u){
								$_uinfo[$_u[0]->ID] = $_u[0]->display_name;
								$optdata['severids'] .= $_uid.',';
							}
						}
					}
					$optdata['severids'] = rtrim($optdata['severids'],',');
				}else{
					$_uid = sanitize_user($_POST['data']['severids']);
					$_u = get_users(array('orderby' => 'user_registered','offset' => 0,'number' => 1,'search'=>$_uid, 'fields' => array('ID','Display_name')));
					if ($_u){
						$_uinfo[$_u[0]->ID] = $_u[0]->display_name;
						$optdata['severids'] = $_uid;
					}
				}
			}else{
				$_uinfo = [];
			}
						
			$optdata['severinfo'] = $_uinfo;
			$optdata['severqq'] = '';
			if (!empty($_POST['data']['severqq'])){
				if (strpos($_POST['data']['severqq'],',') !== false){
					foreach (explode(",", $_POST['data']['severqq']) as $k => $v) if (!empty($v)) $optdata['severqq'] .= intval($v).',';
					$optdata['severqq'] = rtrim($optdata['severqq'],',');
				}else{
					$optdata['severqq'] = intval($_POST['data']['severqq']);
				}
			}
			$optdata['severwechat'] = '';
			if (!empty($_POST['data']['severwechat'])){
				if (strpos($_POST['data']['severwechat'],',') !== false){
					foreach (explode(",", $_POST['data']['severwechat']) as $k => $v) if (!empty($v)) $optdata['severwechat'] .= esc_url_raw($v).',';
					$optdata['severwechat'] = rtrim($optdata['severwechat'],',');
				}else{
					$optdata['severwechat'] = esc_url_raw($_POST['data']['severwechat']);
				}
			}
			$optdata['severphone'] = '';
			if (!empty($_POST['data']['severphone'])){
				if (strpos($_POST['data']['severphone'],',') !== false){
					foreach (explode(",", $_POST['data']['severphone']) as $k => $v) if (!empty($v)) $optdata['severphone'] .= intval($v).',';
					$optdata['severphone'] = rtrim($optdata['severphone'],',');
				}else{
					$optdata['severphone'] = intval($_POST['data']['severphone']);
				}
			}
			$optdata['severemail'] = '';
			if (!empty($_POST['data']['severemail'])){
				if (strpos($_POST['data']['severemail'],',') !== false){
					foreach (explode(",", $_POST['data']['severemail']) as $k => $v) if (!empty($v)) $optdata['severemail'] .= sanitize_email($v).',';
					$optdata['severemail'] = rtrim($optdata['severemail'],',');
				}else{
					$optdata['severemail'] = sanitize_email($_POST['data']['severemail']);
				}
			}
			$optdata['socket'] = (!empty($_POST['data']['socket'] )) ? sanitize_text_field($_POST['data']['socket']) : '';
			
			$_opt = get_option(self::$optname);
			foreach ($optdata as $k => $v) $_opt[$k] = $v;
			update_option(self::$optname, $_opt);
			$ret['code'] = 1;
			$ret['data'] = $optdata;
			self::retJson($ret);
		}
		
		$_options = get_option(self::$optname);
		$formhash = wp_create_nonce();
		include_once(EYOUNG_SERVICERONLINE_PATH . '/assets/sever.php');
	}
	
	public static function upSocketUrl(){
		if(!empty($_POST["formhash"]) && ($_POST["formhash"] == wp_create_nonce())) {		
			$response = wp_remote_post(self::$api_reg.'/plugin/serviceonline/upsocket',array('method' => 'POST','sslverify' => false,'body' => array('weburl' => site_url(),'webkey' => AUTH_KEY,'k' => AUTH_SALT,'v' => md5(AUTH_SALT.AUTH_KEY.site_url()))));
			if (is_wp_error( $response ) ) {
				$ret['code'] = 0;
				$ret['msg'] = $response->get_error_message();
				self::retJson($ret);
			} else {
				$_res = wp_remote_retrieve_body($response);
				$data = json_decode($_res, true);
				if ($data['code'] == '1'){
					$_opt = get_option(self::$optname);
					foreach ($data['data'] as $k => $v) $_opt[$k] = $v;
					update_option(self::$optname, $_opt);
					$ret['code'] = 1;
					$ret['msg'] = $data['data']['socket'];
					self::retJson($ret);
				}else{
					$ret['code'] = 0;
					$ret['msg'] = $data['msg'];
					self::retJson($ret);
				}
			}
		}
		
		$ret['code'] = 0;
		$ret['msg'] = 'post error!';
		self::retJson($ret);
	}
	
	public static function goCenter(){
		$cfg = get_option(self::$optname);
		if (!empty($cfg['servicepageid'])){
			$centerurl = get_page_link($cfg['servicepageid']);
			echo '<script language="javascript">document.location= "'.$centerurl.'";</script>';
		}
	}
	
	public static function getCustomer(){
		if(!empty($_POST["formhash"]) && ($_POST["formhash"] == wp_create_nonce())) {
			include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_customer.php';
			$limit = empty($_POST['limit']) ? 10 : intval($_POST['limit']);
			$page = max(1, intval($_POST['page']));
			$data = ESOS_Customer::getAll('','', $limit,($page - 1) * $limit);
			$ret['code'] = 1;
			$ret['count'] = empty($_POST['count']) ? ESOS_Customer::count() : intval($_POST['count']);
			$ret['data'] = $data;
			self::retJson($ret);
		}
		$lang = ESOS_Front::$lg;
		include_once(EYOUNG_SERVICERONLINE_PATH . '/assets/customer.php');
	}
	public static function setCustomer(){
		if(!empty($_POST["formhash"]) && ($_POST["formhash"] == wp_create_nonce()) && !empty($_POST['uid'])) {
			$_uinfo = wp_get_current_user();
			if (!empty($_uinfo->ID)){
				$_POST['uinfo']['uid'] = $_uinfo->ID;
				$_POST['uinfo']['oid'] = sanitize_user($_POST['uid']);
				ESOS_Front::setUserField();
			}		
		}
	}
	public static function delCustomer(){
		if(!empty($_POST["formhash"]) && ($_POST["formhash"] == wp_create_nonce())) {
			include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_customer.php';
		
			$_ids = trim(sanitize_text_field($_POST['delids']),',');
			if (strpos($_ids,',') !== false){
				$ids = explode(',',$_ids);
			}else{
				$ids[] = $_ids;
			}
			
			$uids = "'".rtrim(implode("','",$ids),",'")."'";				
			ESOS_Customer::del($uids);
			$ret['code'] = 1;
			self::retJson($ret);
		}
	}
	
	public static function getChatAll(){
		if(!empty($_POST["formhash"]) && ($_POST["formhash"] == wp_create_nonce())) {
			include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_content.php';
			$limit = empty($_POST['limit']) ? 10 : intval($_POST['limit']);
			$page = max(1, intval($_POST['page']));
			$data = ESOS_Content::getAll('*','', $limit,($page - 1) * $limit);			
			$uids = $uinfo = array();
			if (!empty($data)){
				foreach ($data as $k => $v){
					if (is_numeric($v->uid)){
						$uids[$v->uid] = $v->uid;
					}
					if (is_numeric($v->oid)){
						$uids[$v->oid] = $v->oid;
					}
				}
				$uids = array_keys($uids);
				if (!empty($uids)){
					include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_customer.php';
					$_uinfo = ESOS_Customer::get($uids,'uid,name');
				}
				if (!empty($_uinfo)){
					$uinfo = array_reduce($_uinfo, function(&$uinfo, $v){
						$uinfo[$v->uid] = $v->name;
						return $uinfo;
					});
				}
			}

			$ret['code'] = 1;
			$ret['count'] = empty($_POST['count']) ? ESOS_Content::count() : intval($_POST['count']);
			$ret['data'] = $data;
			$ret['uinfo'] = $uinfo;
			self::retJson($ret);
		}
		$lang = ESOS_Front::$lg;
		include_once(EYOUNG_SERVICERONLINE_PATH . '/assets/chat.php');		
	}
	public static function delChatAll(){
		if(!empty($_POST["formhash"]) && ($_POST["formhash"] == wp_create_nonce())) {
			include_once EYOUNG_SERVICERONLINE_PATH.'/table/service_content.php';		
			$_ids = trim(sanitize_text_field($_POST['delids']),',');
			if (strpos($_ids,',') !== false){
				$ids = explode(',',$_ids);
			}else{
				$ids[] = $_ids;
			}
			
			$cids = "'".rtrim(implode("','",$ids),",'")."'";				
			ESOS_Content::del($cids);
			$ret['code'] = 1;
			self::retJson($ret);			
		}
	}
		
	public static function setImageUpload(){
		$_t = wp_get_attachment_image_src(media_handle_upload( 'file', 0));
		$ret['code'] = 1;
		$ret['msg'] = $_t[0];
		self::retJson($ret);
	}
	
	public static function init(){
		if (is_admin()) {
			register_activation_hook(EYOUNG_SERVICERONLINE_FILE, [__CLASS__, 'plugin_activate']);
			register_deactivation_hook(EYOUNG_SERVICERONLINE_FILE, [__CLASS__, 'plugin_deactivate']);
			register_uninstall_hook(EYOUNG_SERVICERONLINE_FILE, [__CLASS__, 'plugin_uninstall']);
			add_action('admin_menu', array(__CLASS__, 'admin_menu_handler'));
			add_action('wp_ajax_eys_setting',array(__CLASS__,'manageSetting'));
			add_action('wp_ajax_eys_sever',array(__CLASS__,'manageSever'));
			add_action('wp_ajax_eys_upsocket',array(__CLASS__,'upSocketUrl'));
			add_action('wp_ajax_eys_getCustomer',array(__CLASS__,'getCustomer'));
			add_action('wp_ajax_eys_setCustomer',array(__CLASS__,'setCustomer'));
			add_action('wp_ajax_eys_delCustomer',array(__CLASS__,'delCustomer'));
			add_action('wp_ajax_eys_getChatAll',array(__CLASS__,'getChatAll'));
			add_action('wp_ajax_eys_delChatAll',array(__CLASS__,'delChatAll'));
			add_filter('plugin_action_links', array(__CLASS__, 'setQuickUrl'), 10, 2);
			add_action('admin_enqueue_scripts', array(__CLASS__,'setLoadScripts'));						
		}
		
		add_action('wp_ajax_eys_imageupload',array(__CLASS__,'setImageUpload'));
	}
	
	public static function setLoadScripts(){
		wp_enqueue_style( 'eyservice_eyui_admin_css', EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS.'/css/eyui.css');
		wp_enqueue_script('eyservice_eyui_js', EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS.'/eyui.js');		
	}
	
	public static function setQuickUrl($links, $file){
		if ($file != plugin_basename(EYOUNG_SERVICERONLINE_FILE)) return $links;
		array_unshift($links, '<a href="'.self::$api_pay.'" target="_blank"><span style="color: blue;">延期&扩容</span></a>');
		array_unshift($links, '<a href="' . menu_page_url('ey_serviceonline-setting', false) . '">设置</a>');
		return $links;
	}
		
	private static function getInitOpt(){
		$_opt['title'] = 'Ey客服系统';
		$_opt['version'] = '1.0';
		$_opt['isopen'] = 1;
		$_opt['mobileopen'] = 1;
		$_opt['imageupload'] = 1;
		$_opt['imagelimit'] = 1024;
		$_opt['fileupload'] = 1;
		$_opt['filelimit'] = 2048;
		$_opt['fileext'] = 'zip|rar|7z|docx|doc|xls|xlsx|pdf';
		$_opt['position'] = "l";
		$_opt['width'] = 50;
		$_opt['backcolor'] = "#FFFFFF";
		$_opt['iconcolor'] = "#3366CC";
		$_opt['onlinewidth'] = 300;
		$_opt['onlineheight'] = 500;
		$_opt['onlineoffset'] = "r";
		$_opt['ip2address'] = "http://ip-api.com/json/";
		$_opt['mobileposition'] = "r";
		$_opt['mobileoffset'] = "t";
		$_opt['mobileheight'] = "60%";
		return $_opt;
	}
	
	private static function setInstallSql(){
		global $wpdb;
		$prefix = $wpdb->prefix.self::$name;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = <<<EOF
CREATE TABLE `{$prefix}_content` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext,
  `uid` varchar(32) DEFAULT NULL,
  `sendflag` tinyint(4) DEFAULT '0',  
  `oid` varchar(32) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `ctime` int(11) DEFAULT NULL,
  `stime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`),
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `oid`(`oid`) USING BTREE
) {$charset_collate};
EOF;
		$wpdb->query($sql);
		$sql = <<<EOF
CREATE TABLE `{$prefix}_maillog`  (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) DEFAULT NULL,
  `usermail` varchar(50) DEFAULT NULL,
  `userphone` varchar(50) DEFAULT '0',
  `userqq` varchar(20) DEFAULT NULL,
  `content` text ,
  `status` tinyint(4) DEFAULT 0,
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`logid`),
  INDEX `mail`(`mail`)
) {$charset_collate};
EOF;
		$wpdb->query($sql);
		$sql = <<<EOF
CREATE TABLE `{$prefix}_setting`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(20) DEFAULT NULL,
  `content` text,
  `cate` varchar(30) DEFAULT NULL,  
  `status` tinyint(4)  DEFAULT 0,
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), 
  UNIQUE INDEX `uid`(`uid`)
) {$charset_collate};
EOF;
		$wpdb->query($sql);
		$sql = <<<EOF
CREATE TABLE `{$prefix}_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` varchar(32) DEFAULT NULL,
  `store` tinyint(4)  DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `url` varchar(100)  DEFAULT NULL,
  `uid` varchar(32)  DEFAULT NULL,
  `tag` varchar(20)  DEFAULT NULL,  
  `name` varchar(32) DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `size` int(11) DEFAULT '0',
  `ext` varchar(20) DEFAULT NULL,
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),  
  INDEX `rid`(`rid`) USING BTREE,
  INDEX `tag`(`tag`) USING BTREE,  
  INDEX `rtag`(`rid`,`tag`) USING BTREE
) {$charset_collate};
EOF;
		$wpdb->query($sql);
		$sql = <<<EOF
CREATE TABLE `{$prefix}_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lvl` tinyint(4) DEFAULT NULL,
  `dv` varchar(10) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `location` varchar(32) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `mark` text,
  `rate` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `locked` int(11) DEFAULT 0,
  `ctime` int(11) DEFAULT NULL,
  `stime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uid`(`uid`) USING BTREE
) {$charset_collate};
EOF;
		$wpdb->query($sql);
		$sql = <<<EOF
CREATE TABLE `{$prefix}_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ctime` int(11) DEFAULT NULL,
  `stime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE
) {$charset_collate};
EOF;
		$wpdb->query($sql);
		$sql = <<<EOF
CREATE TABLE `{$prefix}_editlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(20) DEFAULT NULL,
  `key` varchar(30) DEFAULT NULL,
  `val` varchar(100) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `active` enum('edit','switch') DEFAULT 'edit',
  `ctime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `oid`(`oid`) USING BTREE,
  INDEX `oid_2`(`oid`, `active`) USING BTREE
) {$charset_collate};
EOF;
		$wpdb->query($sql);
	}
	
	public static function plugin_activate(){
	
		$__opt = get_option(self::$optname);
		$_opt = empty($__opt) ? self::getInitOpt() : $__opt;
		$_uinfo = wp_get_current_user();
		$_opt['severids'] = $_uinfo->data->user_login;
		$_opt['severinfo'][$_uinfo->data->ID] = $_uinfo->data->user_login;		
		$_opt['servicepageid'] = self::setServiceCenterPage('客服中心', 'servicecenter', 'servicecenter.php', '[ey_servicecenter]');		
		if (empty($__opt))
			add_option(self::$optname, $_opt);
		else
			update_option(self::$optname, $_opt);
		
		self::setInstallSql();
	
		$response = wp_remote_post(self::$api_reg.'/plugin/serviceonline/install',array('method' => 'POST','sslverify' => false,'body' => array('weburl' => site_url(),'webkey' => AUTH_KEY,'k' => AUTH_SALT,'v' => md5(AUTH_SALT.AUTH_KEY.site_url()))));
		if (is_wp_error( $response ) ) {
			$ret['code'] = 0;
			$ret['msg'] = $response->get_error_message();
			self::retJson($ret);
		} else {
			$_res = wp_remote_retrieve_body($response);
			$data = json_decode($_res, true);

			if ($data['code'] == '1'){
				foreach ($data['msg'] as $k => $v) $_opt[$k] = $v;
				update_option(self::$optname, $_opt);
			}else{
			}
		}
	}
	
	private static function setServiceCenterPage($title, $slug, $page_template='', $post_content=''){
		$allPages = get_pages();//获取所有页面
		$exists = false;
		foreach( $allPages as $page ){
			//通过页面别名来判断页面是否已经存在
			if( strtolower( $page->post_name ) == strtolower( $slug ) ){
				return $page->ID;
				$exists = true;
			}
		}
		if( $exists == false ) {
			$new_page_id = wp_insert_post(
				array(
					'post_title'	=> $title,
					'post_type'	=> 'page',
					'post_name'	=> $slug,
					'comment_status'=> 'closed',
					'ping_status'	=> 'closed',
					'post_content'	=> $post_content,
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'menu_order'	=> 0
				)
			);
			//如果插入成功 且设置了模板   
			if($new_page_id && $page_template!=''){
				//保存页面模板信息
				update_post_meta($new_page_id, '_wp_page_template',  $page_template);
			}
			return $new_page_id;
		}
	}

	public static function plugin_deactivate(){
//		delete_option(self::$optname);	
	}

	public static function plugin_uninstall(){
		delete_option(self::$optname);
		global $wpdb;
		$prefix = $wpdb->prefix.self::$name;
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_content`;");
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_setting`;");
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_maillog`;");
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_editlog`;");
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_track`;");
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_customer`;");
		$wpdb->query("DROP TABLE IF EXISTS `{$prefix}_attach`;");	
		
		$response = wp_remote_post(self::$api_reg.'/plugin/serviceonline/uninstall',array('method' => 'POST','sslverify' => false,'body' => array('weburl' => site_url(),'webkey' => AUTH_KEY,'k' => AUTH_SALT,'v' => md5(AUTH_SALT.AUTH_KEY.site_url()))));
		if (is_wp_error( $response ) ) {
			$ret['code'] = 0;
			$ret['msg'] = $response->get_error_message();
			self::retJson($ret);
		} else {

		}	
	}

	/**
	 * 插件设置
	 */
	public static function admin_menu_handler(){
		global $submenu;
		add_menu_page(
			'Ey在线客服插件',
			'Ey在线客服',
			'administrator',
			self::$name,
			array(__CLASS__, 'manageSetting'),
			//plugin_dir_url(EYOUNG_SERVICERONLINE_FILE). 'assets/icon_for_menu.svg'
			'dashicons-testimonial'
		);

		add_submenu_page(self::$name,'Ey在线客服插件','参数设置', 'administrator', self::$name.'-setting', array(__CLASS__,'manageSetting'));
		add_submenu_page(self::$name,'Ey在线客服插件','客服设置', 'administrator', self::$name.'-setsever', array(__CLASS__,'manageSever'));
		add_submenu_page(self::$name,'Ey在线客服插件','客户管理', 'administrator', self::$name.'-customer', array(__CLASS__,'getCustomer'));
		add_submenu_page(self::$name,'Ey在线客服插件','沟通记录', 'administrator', self::$name.'-chat', array(__CLASS__,'getChatAll'));
		add_submenu_page(self::$name,'Ey在线客服插件','客服中心', 'administrator', self::$name.'-gocenter', array(__CLASS__,'goCenter'));		
//		add_submenu_page(self::$name,'Ey在线客服插件','邮件记录', 'administrator', self::$name.'-email', array(__CLASS__,'manageEmail'));
		unset($submenu[self::$name][0]);
	}
}
