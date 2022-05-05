<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title></title>
</head>
<body>
<fieldset class="eyui-elem-field eyui-field-title" style="margin-top: 20px;">
  <legend><?php _e($lang['customer'].$lang['manage']);?></legend>
</fieldset>
<table id="tableCustomer" lay-filter="tableCustomer"></table>
<script type="text/html" id="customerTools">
	<div class="eyui-btn-container">
		<button class="eyui-btn eyui-btn-sm eyui-btn-danger" lay-event="del"> <i class="eyui-icon">&#xe640;</i><?php _e($lang['del'].$lang['customer']);?></button>
		<button class="eyui-btn eyui-btn-sm" lay-event="refresh"> <i class="eyui-icon">&#xe669;</i> </button>
	</div>
</script>
<script>
EY_Service = {};
EY_Service.formhash = '<?php _e(wp_create_nonce()) ?>';
EY_Service.ajxurl = '<?php _e(admin_url('admin-ajax.php'))?>';

eyui.use(['table','util'], function(){
	var table = eyui.table
	,$ = eyui.$
	,util = eyui.util;

	postdata = function(post,callback){
		$.ajax({type: "POST",url:EY_Service.ajxurl,dataType: "json",data:post,
		success: function(res){
			if (callback != '') {
				var d = $.parseJSON(JSON.stringify(res));
				if (d.code == '1'){
					
				}else if (d.code == '0'){
					layer.alert("<?php _e($lang['tip_err_input']);?>", {icon: 2});
					return;
				}
//				eval(callback+'(('+d+'))');
			}
			layer.msg("<?php _e($lang['act_success']);?>", {time: 1000});
		}});
	}	
	refreshCustomerTable = function(){
		table.reload('tableCustomer');
	}
	
	retProcData = function(d){
		console.log("retProcData=>",d);
		if (d.code == '1'){
			
		}else if (d.code == '0'){
//			layer.alert(d.msg, {icon: 2});
		}
	}
	
	table.render({
		elem: '#tableCustomer'
		//,height: 312
		,title:'<?php _e($lang['customer'].$lang['data']);?>'
		,url: EY_Service.ajxurl
		,toolbar: '#customerTools'
		,method: 'post'
		,where: {
			action:'eys_getCustomer'
			,formhash:EY_Service.formhash
		 }
		,response: {
			statusCode: 1 //规定成功的状态码，默认：0
			//,msgName: 'hint' //规定状态信息的字段名称，默认：msg
			//,dataName: 'rows' //规定数据列表的字段名称，默认：data
		}
		,page: true //开启分页
		,cols: [[ //表头
		 {checkbox: true, LAY_CHECKED: false, fixed: 'left'}
		  ,{field: 'uid', title: 'UID', width:80, sort: true, fixed: 'left'}
		  ,{field: 'name', title: '<?php _e($lang['name']);?>', width:120,fixed: 'left',edit:true}
		  ,{field: 'lvl', title: '<?php _e($lang['lvl']);?>', width:80,templet:function(d){return (d.lvl == '88') ? "<?php _e($lang['service']);?>" : ((d.lvl=='0') ? "<?php _e($lang['member']);?>":"<?php _e($lang['visitor']);?>") }}
		  ,{field: 'rate', title: '<?php _e($lang['rate']);?>', width: 80,sort: true,edit:true}
		  ,{field: 'phone', title: '<?php _e($lang['phone']);?>', width:80,edit:true} 
		  ,{field: 'qq', title: '<?php _e($lang['qq']);?>', width: 80,edit:true}
		  ,{field: 'email', title: '<?php _e($lang['email']);?>', width: 100, edit:true}
		  ,{field: 'address', title: '<?php _e($lang['address']);?>', width: 200,edit:true,hide:true}
		  ,{field: 'mark', title: '<?php _e($lang['mark']);?>', width: 135,sort: true,edit:true}
		  ,{field: 'ctime', title: '<?php _e($lang['time']);?>', width: 150, sort: true, templet:function(d){return util.toDateString(d.ctime*1000,'yyyy-MM-dd HH:mm');}}
		]]
	});
	// 列表中修改某个单元格
	table.on('edit(tableCustomer)', function(obj){ 
		var post = {};
		post.formhash =  EY_Service.formhash;
		post.action = 'eys_setCustomer';
		post.key = obj.field;
		post.val = util.escape(obj.value);
		post.uid = obj.data.uid;
		var _oldval = $(this).prev().text();	// 原值
		if (obj.field == 'name'){
			layer.confirm("<?php _e($lang['tip_act_edit']);?>",{icon:3}, 
			function(index){
				postdata(post);
				layer.close(index);
			},
			function(index){
				obj.update({name: _oldval});
				layer.close(index);
			});
		}else if(obj.field == 'rate'){
			if (isNaN(post.val)){
				layer.alert("<?php _e($lang['tip_err_input']);?>", {icon: 2},function(index){
					obj.update({rate: _oldval});
					layer.close(index);
				});
				return;
			}
			postdata(post);
		}else{
			/*
			$.ajax({type: "POST",url:EY_Service.ajxurl,dataType: "json",data:post,
				success: function(d){
					if (d.code == '1'){
						layer.msg("<?php _e($lang['act_success']);?>", {time: 1500});						
					}else{
						layer.alert("<?php _e($lang['tip_err_input']);?>", {icon: 2});
					}
				}
			});
			*/
			postdata(post,"retProcData");
		}
	});
	table.on('toolbar(tableCustomer)', function(obj){
	  switch(obj.event){
		case 'refresh':
			refreshCustomerTable();
			break;
		case 'del':
			var checkData = table.checkStatus('tableCustomer');
 			if (checkData.data.length == 0){
 				layer.msg("<?php _e($lang['tips_check']);?>");
 				return;
 			}
 			post = {};
 			post.delids = '';
 			for (var i=0; i<checkData.data.length;i++){
 				post.delids += checkData.data[i].uid+',';
 			}
			layer.confirm("<?php _e($lang['tip_act_del']);?>",{icon:3}, 
				function(index){
					post.action = 'eys_delCustomer';
					post.formhash =  EY_Service.formhash;
					postdata(post, "refreshCustomerTable");
				}
			);
			break;
	  };
	});	
});
</script>
</body>
</html>