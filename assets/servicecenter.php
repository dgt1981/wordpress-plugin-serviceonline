<?php  get_header();?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title><?php _e($lang['servicecenter']);?></title>
<style>
.contactlist{overflow:hidden;overflow-y:scroll;padding:5px;}
.user-item .user-ext,.user-item .user-address{font-size:14px;}
.user-edit {width:300px;padding:10px;font-size:14px;}
.user-edit .eyui-input{height:28px;line-height: 28px;}
.user-edit .eyui-input-block{margin-left:60px;}
.user-edit .eyui-form-label{width:50px;padding:5px;}
.user-edit .eyui-form-item{margin-bottom:2px;}
</style>
</head>
<body>
<div class="eyui-container">  
	<div class="eyui-row eyui-col-space15" id="serviceframe">
		<div class="eyui-col-sm3 eyui-col-xs12">
			<div class="eyui-tab">
			  <ul class="eyui-tab-title">
				<li class="eyui-this"><?php _e($lang['recent'].$lang['online']);?> <span class="eyui-badge-rim contactnumb">0</span></li>
				<li><?php _e($lang['recent'].$lang['contact']);?> <span class="eyui-badge-rim contactnumb">0</span></li>
			  </ul>
			  <div class="eyui-tab-content">
				<div class="eyui-tab-item contactlist eyui-show">
				</div>
				<div class="eyui-tab-item contactlist">
				</div>
			  </div>
			</div>
		</div>	
		<div class="eyui-col-sm9  eyui-hide-xs">
			<div class="eyui-tab eyui-tab-card" lay-allowClose="true"  lay-filter="serviceTab" id="serviceTab">
				<ul class="eyui-tab-title">
				</ul>
				<div class="eyui-tab-content" style="padding-top:10px;">
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/html" id="welcome-tpl">
<div class="eyui-row eyui-col-space5 eyui-hide-xs" id="tip-welcome" style="height:500px;">
	<div class="eyui-col-sm2"></div>
	<div class="eyui-col-sm8" style="padding-top:100px;">
		<fieldset class="eyui-elem-field eyui-field-title"> <legend><i class="eyui-icon eyui-icon-service" style="font-size: 20px;"></i> <?php _e($lang['welcome'].$lang['servicecenter']);?></legend></fieldset>	
		<blockquote class="eyui-elem-quote eyui-quote-nm" style="font-size:16px;"><i class="eyui-icon eyui-icon-return" style="font-size: 20px;"></i> <?php _e($lang['tips_welcome']);?> </blockquote>
	</div>
	<div class="eyui-col-sm2"></div>
</div>
</script>
<script type="text/html" id="room-frame-tpl">
<div class="eyui-row eyui-col-space5 room-frame">
	<div class="eyui-col-sm8 eyui-col-xs12 room-center">
		<div class="eyui-row">
			<div class="eyui-col-sm12" style="text-align:right;">
			 <div class="eyui-btn-group">
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsHistory"><i class="eyui-icon eyui-icon-log"></i><?php _e($lang['history']);?></button>
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsClear"><i class="eyui-icon eyui-icon-delete"></i><?php _e($lang['clear']);?></button>
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsScroll"><i class="eyui-icon eyui-icon-ok-circle"></i><?php _e($lang['scroll']);?></button>
			</div>
			</div>
		</div>
		<div class="chat-cont">
		<ul class="chat-list">
		</ul>
		</div>
		<div class="eyui-row act-area eyui-col-space5">
			<div class="eyui-col-sm12">
				<div class="eyui-btn-group">
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsFace" ><i class="eyui-icon eyui-icon-face-smile"></i><?php _e($lang['face']);?></button>
				{{# if (d.conf.imageupload) { }}
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsImage"><i class="eyui-icon eyui-icon-picture"></i><?php _e($lang['image']);?></button>
				{{# } }}
				{{# if (d.conf.fileupload) { }}
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsFile"><i class="eyui-icon eyui-icon-file"></i><?php _e($lang['file']);?></button>
				{{# } }}
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsSetting" ><i class="eyui-icon eyui-icon-set"></i><?php _e($lang['set']);?></button>
				</div>
			</div>
			<div class="eyui-col-sm9 eyui-col-xs9">
				<textarea name="content" ey-active="setContent" disabled="true" placeholder="<?php _e($lang['tips_content']);?>" class="eyui-textarea eyui-btn-disabled chat-input"></textarea>
			</div>
			<div class="eyui-col-sm3 eyui-col-xs3">
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled chat-send" ey-active="EyToolsSend" style="height:54px;"><i class="eyui-icon eyui-icon-release" style="font-size: 30px;"></i> <?php _e((wp_is_mobile()) ? '' :  $lang['send']);?></button>
			</div>
		</div>
	</div>
	<div class="eyui-col-sm4 room-right eyui-hide-xs">
		<fieldset class="eyui-elem-field user-info"> 
			<legend><?php _e($lang['userinfo']);?></legend>
			<div class="eyui-field-box">
					<div class="eyui-row user-item">
						<div class="eyui-col-sm3"> <img src="{{d.base.avatar}}"> </div> 
						<div class="eyui-col-sm9">
							<div class="eyui-row eyui-col-space1">
								<div class="eyui-col-sm12 user-title"><span class="user-name" uid="{{d.base.uid}}"">{{d.base.name}}</span>
								<span class="eyui-badge-rim">
								{{# if (d.base.lvl >0) { }}
								<?php _e($lang['service']);?>
								{{# }else if(d.base.lvl == 0){ }}
								<?php _e($lang['member']);?>
								{{# }else{ }}
								<?php _e($lang['visitor']);?>
								{{# } }}
								</span>
								</div>
								<div class="eyui-col-sm12 eyui-elip">
								<i class="eyui-icon eyui-icon-location"></i>
								<span class="user-address" uid="{{d.base.uid}}">
								{{# if (d.base.address) { }}
								 {{d.base.address}}
								{{# }else{ }}
								 {{d.base.ip}}
								{{# } }}
								</span>
								</div>
							</div>
						</div>
					</div>
					<div class="eyui-row"  style="margin-top:-20px;">
						<div class="eyui-col-sm3" style="text-align: center;top:12px;"><span class="eyui-badge-rim"><?php _e($lang['rate']);?> : </span></div>
						<div class="eyui-col-sm9 user-rate"></div> 
					</div>
					<div class="eyui-row" style="margin-top: -30px;">
						<div class="eyui-col-sm12" style="text-align:center;">
							 <div class="eyui-btn-group">
								<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsUseredit"><i class="eyui-icon eyui-icon-edit"></i><?php _e($lang['edit']);?></button>
								<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsTrack"><i class="eyui-icon eyui-icon-ok-circle"></i><?php _e($lang['track']);?></button>
								<?php if (!empty($cfg['modelocked'])) { ?>
								<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsMonop"><i class="eyui-icon eyui-icon-heart"></i><?php _e($lang['monop']);?></button>
								<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-xs" ey-active="EyToolsSwitch"><i class="eyui-icon eyui-icon-transfer"></i><?php _e($lang['switch']);?></button>
								<?php } ?>
							</div>
						</div> 
					</div>
			</div>
		</fieldset>
		<fieldset class="eyui-elem-field eyui-field-title user-track"> <legend><?php _e($lang['access']);?></legend></fieldset>	
		<ul class="eyui-timeline user-track">
		</ul>
	</div>
</div>
</script>
<script type="text/html" id="useredit-tpl">
<form class="eyui-form" lay-filter="formuseredit" action="">
	<div class="eyui-form-item">
		<label class="eyui-form-label"><?php _e($lang['name']);?></label>
		<div class="eyui-input-block">
			<input type="text" name="name" onChange="setEyUserField(this)" autocomplete="off" class="eyui-input">
		</div>
	</div>
	<div class="eyui-form-item">
		<label class="eyui-form-label"><?php _e($lang['phone']);?></label>
		<div class="eyui-input-block">
			<input type="text" name="phone"  onChange="setEyUserField(this)" autocomplete="off" class="eyui-input">
		</div>
	</div>
	<div class="eyui-form-item">
		<label class="eyui-form-label"><?php _e($lang['qq']);?></label>
		<div class="eyui-input-block">
			<input type="text" name="qq"  onChange="setEyUserField(this)"  autocomplete="off" class="eyui-input">
		</div>
	</div>		
	<div class="eyui-form-item">
		<label class="eyui-form-label"><?php _e($lang['email']);?></label>
		<div class="eyui-input-block">
			<input type="text" name="email"  onChange="setEyUserField(this)"  autocomplete="off" class="eyui-input">
		</div>
	</div>	
	<div class="eyui-form-item">
		<label class="eyui-form-label"><?php _e($lang['address']);?></label>
		<div class="eyui-input-block">
			<input type="text" name="address"  onChange="setEyUserField(this)"  autocomplete="off" class="eyui-input">
		</div>
	</div>
	<div class="eyui-form-item">
		<label class="eyui-form-label"><?php _e($lang['mark']);?></label>
		<div class="eyui-input-block">
			<input type="text" name="mark"  onChange="setEyUserField(this)"  autocomplete="off" class="eyui-input">
		</div>
	</div>
</form>
</script>
<script type="text/html" id="memberitem-tpl">
	<div class="eyui-col-sm3 member-item" uid="{{ d.uid }}" name="{{ d.name }}" lvl="{{ d.lvl }}">
		<div class="eyui-panel"><div class="member-avatar"><img src="{{d.avatar}}"></div><div class="member-name">{{ d.name }}</div></div>
	</div>
</script>
<script type="text/html" id="useritem-tpl">
	<div class="eyui-panel user-item" data="{{d.uid}}">
		<div class="eyui-row">
			<div class="eyui-col-sm3 eyui-col-xs2">
			<img src="{{d.avatar}}">
			</div>
			<div class="eyui-col-sm9 eyui-col-xs10">
				<div class="eyui-row eyui-col-space1">
					<div class="eyui-col-sm10 eyui-col-xs10 user-title" data-json="{{encodeURIComponent(JSON.stringify(d))}}" ey-active="userEnter">
						<span class="user-name" uid="{{d.uid}}">{{d.name}}</span>
						<span class="eyui-badge-rim">
							{{# if (d.lvl >0) { }}
							<?php _e($lang['service']);?>
							{{# }else if(d.lvl == 0){ }}
							<?php _e($lang['member']);?>
							{{# }else{ }}
							<?php _e($lang['visitor']);?>
							{{# } }}
						</span>
					</div>
					<div class="eyui-col-sm2 eyui-col-xs2">&nbsp;<span class="eyui-badge-dot user-status {{# if (d.ol == '1') { }}eyui-bg-blue{{# }else{ }}eyui-bg-gray2{{# } }}"></span></div>
					<div class="eyui-col-sm12 eyui-col-xs12 eyui-elip eyui-font-gray user-ext">{{# if (!!d.ext) { }}{{d.ext}}{{# } }}</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/html" id="chatitem-tpl">
	{{# if (!!d.me) { }}
	<li class="chat-item-me" cid="{{d.cid}}" time="{{d.ctime}}" style="display:none;">
		<div class="items-content">
			<p class="itemes-author">{{d.name}}</p>
			<div class="items-msg items-text">
				<span>{{d.content}} {{# if (d.status == '1') { }}<i class="eyui-icon eyui-icon-ok-circle eyui-font-blue items-status"></i>{{# }else{ }}<i class="eyui-icon eyui-icon-tips eyui-font-orange  items-status"></i>{{# } }}</span>
			</div>
			
		</div>
		<a class="items-avatar" uid="{{d.uid}}" name="{{d.name}}" lvl="{{d.lvl}}"><img src="{{d.avatar}}"></a>
	</li>
	{{# }else{ }}
	<li class="chat-item" cid="{{d.cid}}" time="{{d.ctime}}" view="{{d.status}}" style="display:none;">
		<a class="items-avatar" uid="{{d.uid}}" name="{{d.name}}" lvl="{{d.lvl}}"><img src="{{d.avatar}}"></a>
		<div class="items-content">
			<p class="itemes-author user-name" uid="{{d.uid}}">{{d.name}}</p>
			<div class="items-msg items-text"><span>{{d.content}}</span></div>
		</div>
	</li>
	{{# } }}
</script>
<script type="text/html" id="trackitem-tpl">
	<li class="eyui-timeline-item track-item" time="{{d.time}}"> <a href="{{d.url}}" target="_blank"><i class="eyui-icon eyui-timeline-axis eyui-icon-link"></i></a>
		<div class="eyui-timeline-content eyui-text">
			<div class="eyui-timeline-title">{{d.title}}<em class="track-time">{{d.time2}}</em></div>
		</div> 
	</li>
</script>
<script type="text/heml" id="servicesetting-tpl">
<div class="eyui-tab eyui-tab-brief">
	<ul class="eyui-tab-title">
		<li class="eyui-this"><i class="eyui-icon eyui-icon-speaker"></i> <?php _e($lang['speaker_all']);?></li>
		<li><i class="eyui-icon eyui-icon-notice"></i> <?php _e($lang['offline_reply']);?></li>
	</ul>
	<div class="eyui-tab-content" >
		<div class="eyui-tab-item eyui-show">		
			<div class="eyui-form-item" style="margin:0 30px 20px 30px;">
				<?php _e($lang['speaker_tips']);?><hr>
				<textarea name="speaker_content" id="speaker_content" placeholder="" class="eyui-textarea"></textarea>
			</div>
			 <div class="eyui-form-item">
				<div class="eyui-inline" style="width:100%;">
					<div class="eyui-input-blocke" style="text-align:center;">
						<div class="eyui-btn-group">
							<button type="button" class="eyui-btn" ey-active="EySetSpeaker"> <?php _e($lang['send']);?></button>
						</div>
					</div>
				</div>
			 </div>
		</div>
		<div class="eyui-tab-item">
			<div class="eyui-form-item" style="margin:0 30px 20px 30px;">
				  <?php _e($lang['offline_reply_tips']);?><hr>
				
				<textarea name="offline_reply_content" id="offline_reply_content" placeholder="" class="eyui-textarea"></textarea>
			</div>
			 <div class="eyui-form-item">
				<div class="eyui-inline" style="width:100%;">
					<div class="eyui-input-blocke" style="text-align:center;">
						<div class="eyui-btn-group">
							<button type="button" class="eyui-btn" ey-active="EySetOfflineReply">  <?php _e($lang['set']);?></button>
						</div>
					</div>
				</div>
			 </div>			
		</div>
	</div>
</div>
</script>

<script>
SITEURL = '<?php _e(site_url())?>';
EY_Service = {};
EY_Service.cfg = <?php _e(json_encode($cfg));?>;
EY_Service.comm = {};
EY_Service.comm.v = "<?php _e($check); ?>";
EY_Service.comm.h5 = <?php _e(wp_is_mobile() ? 1 : 0); ?>;
EY_Service.formhash = '<?php _e(wp_create_nonce()) ?>';
EY_Service.plugurl = '<?php _e(EYOUNG_SERVICERONLINE_URL); ?>';
EY_Service.plugjs = '<?php _e(EYOUNG_SERVICERONLINE_JS); ?>';
EY_Service.ajxurl = '<?php _e(admin_url('admin-ajax.php'))?>';
EY_Service.sers = EY_Service.cfg.severinfo;
EY_Service.uinfo = <?php _e(json_encode($uinfo)) ?>;
EY_Service.lg = <?php _e(json_encode($lang)) ?>;
eyui.config({version:2.357,base:"<?php _e(EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS); ?>/"}).use("servicecenter");
</script>
<?php  get_footer();?>
</body>
</html>
