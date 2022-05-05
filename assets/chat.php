<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title></title>
</head>
<body>
<fieldset class="eyui-elem-field eyui-field-title" style="margin-top: 20px;">
  <legend><?php _e($lang['chat'].$lang['manage']);?></legend>
</fieldset>
<table id="tableChat" lay-filter="tableChat"></table>
<script type="text/html" id="chatTools">
	<div class="eyui-btn-container">
		<button class="eyui-btn eyui-btn-sm eyui-btn-danger" lay-event="del"> <i class="eyui-icon">&#xe640;</i><?php _e($lang['del'].$lang['chat']);?></button>
		<button class="eyui-btn eyui-btn-sm" lay-event="refresh"> <i class="eyui-icon">&#xe669;</i> </button>
	</div>
</script>
<script>
SITEURL = "<?php _e(site_url());?>";
EY_Service = {};
EY_Service.formhash = "<?php _e(wp_create_nonce()); ?>";
EY_Service.ajxurl = "<?php _e(admin_url('admin-ajax.php')); ?>";

eyui.use(['table','util'], function(){
	var table = eyui.table
	,$ = eyui.$
	,util = eyui.util;
	postdata = function(post,callback){
		$.ajax({type: "POST",url:EY_Service.ajxurl,dataType: "json",async: true,data:post,
		success: function(d){
			layer.msg("<?php _e($lang['act_success']);?>", {time: 1000},function(d){eval(callback + '((' + d + '))');});
		}});
	}
	replaceContent = function (str){
		str = (str||'').replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
		 .replace(/<br \/>/g, '')
		.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/'/g, '&#39;').replace(/"/g, '&quot;') 
	  .replace(/@(\S+)(\s+?|$)/g, '@<a href="javascript:;">$1</a>$2')
		.replace(/img\[([^\s]+?)\]\([\s\S]+?\)/g, function(img){
		  var thumb = (str.match(/img\[([\s\S]+?)\]\(/)||[])[1];
		  var medium = (str.match(/\]\(([\s\S]*?)\)/)||[])[1];
		  return '<img class="upload-image" layer-src="'+SITEURL+medium+'" src="'+SITEURL+thumb+'">';
		})
		.replace(/file\([\s\S]+?\)\[[\s\S]*?\]/g, function(str){
		  var href = (str.match(/file\(([\s\S]+?)\)\[/)||[])[1];
		  var text = (str.match(/\)\[([\s\S]*?)\]/)||[])[1];
		  if(!href) return str;
		  return '<a class="eyui-layim-file" href="'+ href +'" download target="_blank"><i class="eyui-icon">&#xe61e;</i><cite>'+ (text||href) +'</cite></a>';
		})
		.replace(/audio\[([^\s]+?)\]/g, function(audio){
		  return '<div class="eyui-unselect eyui-layim-audio" layim-event="playAudio" data-src="' + audio.replace(/(^audio\[)|(\]$)/g, '') + '"><i class="eyui-icon">&#xe652;</i><p>音频消息</p></div>';
		})
		.replace(/video\[([^\s]+?)\]/g, function(video){
		  return '<div class="eyui-unselect eyui-layim-video" layim-event="playVideo" data-src="' + video.replace(/(^video\[)|(\]$)/g, '') + '"><i class="eyui-icon">&#xe652;</i></div>';
		})
		.replace(/voice\([\s\S]+?\)\[[\s\S]*?\]/g, function(str){
		  var voicetime = (str.match(/voice\(([\s\S]+?)\)\[/)||[])[1];
		  var url = (str.match(/\)\[([\s\S]*?)\]/)||[])[1];
			return '<div class="ey_voice-item" onClick="voicePlay(this)" data="'+url+'"><span class="ey_voice-time">'+voicetime+'"</span><span class="ey_voice-box"></span></div>';
		})    
		.replace(/a\([\s\S]+?\)\[[\s\S]*?\]/g, function(str){
		  var href = (str.match(/a\(([\s\S]+?)\)\[/)||[])[1];
		  var text = (str.match(/\)\[([\s\S]*?)\]/)||[])[1];
		  if(!href) return str;
		  return '<a href="'+ href +'" target="_blank">'+ (text||href) +'</a>';
		})
		return str;
	}
	refreshChatTable = function(){
		table.reload('tableChat');
	}
	table.render({
		elem: '#tableChat'
		,title:"<?php _e($lang['chat'].$lang['data']);?>"
		,url: EY_Service.ajxurl
		,toolbar: '#chatTools'
		,method: 'post'
		,where: {
			action:'eys_getChatAll'
			,formhash:EY_Service.formhash
		 }
		,page: true
		,parseData: function(res){
			var _d = new Array();
			if (res.code == '1'){
				var i = 0, name = '',oname = '',uid='',oid='';
				for (var k in res.data){
					_d[i] = {};
					_d[i].cid = res.data[k].cid;
					uid = res.data[k].uid;
					oid = res.data[k].oid;
					name = (!!res.uinfo[uid]) ? res.uinfo[uid] : ("<?php _e($lang['visitor']);?>"+uid);
					oname = (!!res.uinfo[oid]) ? res.uinfo[oid] : ("<?php _e($lang['visitor']);?>"+oid);
					_d[i].status = (res.data[k].status == '1') ? "<?php _e($lang['read1']);?>" : "<?php _e($lang['read0']);?>";
					_d[i].content = replaceContent(res.data[k].content);
					_d[i].ctime = util.toDateString(res.data[k].ctime*1000,'yyyy-MM-dd HH:mm');
					if (res.data[k].sendflag == '1'){
						_d[i].name = "[<?php _e($lang['service']);?>:"+name+"] --> "+oname;
					}else{
						_d[i].name = name+" --> [<?php _e($lang['service']);?>:"+oname+"]";
					}
					i++;
				}
			}
		
			return {
				"code": (res.code == '1') ? 0 : 1,
				"msg": res.msg,
				"count": res.count,
				"data": _d
			};
		}
		,cols: [[ //表头
		 {field: 'cid',checkbox: true, LAY_CHECKED: false, fixed: 'left'}
		  ,{field:'name', title: "<?php _e($lang['chat'].$lang['obj']);?>", width:230,fixed: 'left'}
		  ,{field: 'content', title: "<?php _e($lang['content']);?>", width:350}
		  ,{field: 'status', title: "<?php _e($lang['status']);?>", width: 80,sort: true}
		  ,{field: 'ctime', title: "<?php _e($lang['time']);?>", width: 150, sort: true}
		]]
	});
	table.on('toolbar(tableChat)', function(obj){
	  switch(obj.event){
		case 'refresh':
			refreshChatTable();
			break;
		case 'del':
			var checkData = table.checkStatus('tableChat');
 			if (checkData.data.length == 0){
 				layer.msg("<?php _e($lang['tips_check']);?>");
 				return;
 			}
 			post = {};
 			post.delids = '';
 			for (var i=0; i<checkData.data.length;i++){
 				post.delids += checkData.data[i].cid+',';
 			}
			layer.confirm("<?php _e($lang['tip_act_del']);?>",{icon:3}, 
				function(index){
					post.action = 'eys_delChatAll';
					post.formhash =  EY_Service.formhash;
					postdata(post, "refreshChatTable");
				}
			);
			break;
	  };
	});	
});
</script>
</body>
</html>