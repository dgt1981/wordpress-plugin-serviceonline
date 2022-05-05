<style>
.ey-eyui-fixbar{position: fixed; right: 5px; bottom: 0px; z-index: 999999999;}
.ey-eyui-fixbar li{width: 50px; height: 50px; line-height: 50px; margin-bottom: -1px; text-align:center; cursor: pointer; font-size:30px; background-color: #9F9F9F; color:#fff; border-radius: 2px; opacity: 0.95;}
.ey-eyui-fixbar li:hover{opacity: 0.85;}
.ey-eyui-fixbar li:active{opacity: 1;}
.ey-eyui-fixbar .ey-eyui-fixbar-top{display: none; font-size: 40px;}		
.eyui-layer-tips i.eyui-layer-TipsG{border-width:0px;}
.eyui-layer-tips .eyui-layer-content{color:<?php _e($cfg['iconcolor']);?>;font-size:16px;}

#serviceframe{margin-bottom:0px;min-height:200px;}
#serviceframe .eyui-tab-brief > .eyui-tab-title .eyui-this{color:<?php _e($cfg['iconcolor']);?>;}
#serviceframe .eyui-tab-brief > .eyui-tab-title .eyui-this:after, #serviceframe .eyui-tab-brief > .eyui-tab-more li.eyui-this:after {
    border: none;
    border-radius: 0;
    border-bottom: 2px solid <?php _e($cfg['iconcolor']);?>;
}

#serviceTab{margin:0px;}
#serviceTab .eyui-tab-content{padding:0px;}
.tools-face-div{width: 370px; padding: 3px;}
</style>
<script type="text/html" id="serviceframe-tpl">
<div class="eyui-row" id="serviceframe">
	<div class="eyui-col-xs12">
		<div class="eyui-tab eyui-tab-brief" lay-filter="serviceTab" id="serviceTab">
			<ul class="eyui-tab-title">
			</ul>
			<div class="eyui-tab-content" style="padding-top:10px;">
			</div>
		</div>
	</div>
</div>
</script>
<script type="text/html" id="chat-frame-tpl">
<div class="eyui-row eyui-col-space5 room-frame">
	<div class="eyui-col-xs12 room-center">
		<div class="eyui-row">
			<div class="eyui-col-xs12" style="text-align:right;">
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
			<div class="eyui-col-xs12">
				<div class="eyui-btn-group">
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsFace" ><i class="eyui-icon eyui-icon-face-smile"></i><?php _e($lang['face']);?></button>
				{{# if (d.conf.imageupload) { }}
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsImage"><i class="eyui-icon eyui-icon-picture"></i><?php _e($lang['image']);?></button>
				{{# } }}
				{{# if (d.conf.fileupload) { }}
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled eyui-btn-xs" ey-active="EyToolsFile"><i class="eyui-icon eyui-icon-file"></i><?php _e($lang['file']);?></button>
				{{# } }}
				</div>
			</div>
			<div class="eyui-col-xs9">
				<textarea name="content" ey-active="setContent" disabled="true" placeholder="<?php _e($lang['tips_content']);?>" class="eyui-textarea eyui-btn-disabled chat-input"></textarea>
			</div>
			<div class="eyui-col-xs3">
				<button type="button" class="eyui-btn eyui-btn-primary eyui-btn-disabled chat-send" ey-active="EyToolsSend" style="height:54px;"><i class="eyui-icon eyui-icon-release" style="font-size: 30px;"></i></button>
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
				<span>{{d.content}} {{# if (d.status == '1') { }}<i class="eyui-icon eyui-icon-ok-circle eyui-font-blue items-status"></i>{{# }else{ }}<i class="eyui-icon eyui-icon-tips eyui-font-gray  items-status"></i>{{# } }}</span>
			</div>
		</div>
		<a class="items-avatar" uid="{{d.uid}}" name="{{d.name}}" lvl="{{d.lvl}}"><img src="{{d.avatar}}"></a>
	</li>
	{{# }else{ }}
	<li class="chat-item" cid="{{d.cid}}" time="{{d.ctime}}" view="{{d.status}}" style="display:none;">
		<a class="items-avatar" uid="{{d.uid}}" name="{{d.name}}" lvl="{{d.lvl}}"><img src="{{d.avatar}}"></a>
		<div class="items-content">
			<p class="itemes-author">{{d.name}}</p>
			<div class="items-msg items-text"><span>{{d.content}}</span></div>
		</div>
	</li>
	{{# } }}
</script>
<script>
SITEURL = '<?php _e(site_url())?>';
EY_Service = {};
EY_Service.cfg = <?php _e(json_encode($cfg));?>;
EY_Service.comm = {};
EY_Service.comm.centerurl = "<?php _e(get_page_link($cfg['servicepageid']));?>";
EY_Service.comm.v = "<?php _e($check); ?>";
EY_Service.formhash = '<?php _e(wp_create_nonce()) ?>';
EY_Service.plugurl = '<?php _e(EYOUNG_SERVICERONLINE_URL); ?>';
EY_Service.plugjs = '<?php _e(EYOUNG_SERVICERONLINE_JS); ?>';
EY_Service.ajxurl = '<?php _e(admin_url('admin-ajax.php'))?>';
EY_Service.sers = EY_Service.cfg.severinfo;
EY_Service.sercfg = <?php _e(json_encode($sercfg)) ?>;
EY_Service.uinfo = <?php _e(json_encode($uinfo)) ?>;
EY_Service.lg = <?php _e(json_encode($lang)) ?>;
eyui.config({version:2.357,base:"<?php _e(EYOUNG_SERVICERONLINE_URL.EYOUNG_SERVICERONLINE_JS); ?>/"}).use("serviceh5");
</script>