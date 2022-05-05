<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title></title>
</head>
<body>
              
<fieldset class="eyui-elem-field eyui-field-title" style="margin-top: 20px;">
  <legend>客服设置</legend>
</fieldset>
<form class="eyui-form" action="" lay-filter="formSever">
<div class="eyui-collapse">
  <div class="eyui-colla-item">
    <h2 class="eyui-colla-title">在线客服</h2>
    <div class="eyui-colla-content eyui-show">
		<div class="eyui-form-item">
			<label class="eyui-form-label">客服用户名</label>
			<div class="eyui-input-inline">
			  <input type="text" name="severids" placeholder="请输入成为客服的用户名" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['severids'])) ? $_options['severids'] : '') ?>">
			</div>
			<div class="eyui-form-mid eyui-word-aux">多个在线客服请用半角逗号","隔开</div>
		</div> 
		<div class="eyui-form-item">
		  <div class="eyui-inline"> <label class="eyui-form-label">推流服务</label>
		   <div class="eyui-input-block">
			<input type="text" name="socket" placeholder="请输入推流服务地址" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['socket'])) ? $_options['socket'] : '') ?>">
		   </div>
		  </div>
		  <div class="eyui-inline">
		   <div class="eyui-input-inline" style="width:120px;">
			<button type="button" class="eyui-btn" id="resetsocket"><i class="eyui-icon">&#xe669;</i> 更新推流</button>
		   </div>
		   <div class="eyui-form-mid eyui-word-aux"><a href="<?php _e(self::$api_pay); ?>" target="_blank" style="color:blue;">延期&&扩容</a></div>
		  </div>
		 </div>
    </div>
  </div>
  <div class="eyui-colla-item">
    <h2 class="eyui-colla-title">QQ客服</h2>
    <div class="eyui-colla-content eyui-show">
    	<div class="eyui-form-item">
			<label class="eyui-form-label">QQ客服</label>
			<div class="eyui-input-inline">
			  <input type="text" name="severqq" placeholder="请输入QQ客服" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['severqq'])) ? $_options['severqq'] : '') ?>">
			</div>
			<div class="eyui-form-mid eyui-word-aux">多个QQ客服请用半角逗号","隔开</div>
		</div> 
    </div>
  </div>
  <div class="eyui-colla-item">
    <h2 class="eyui-colla-title">微信客服</h2>
    <div class="eyui-colla-content eyui-show">
		<div class="eyui-form-item">
		  <div class="eyui-inline"> <label class="eyui-form-label">微信客服</label>
		   <div class="eyui-input-block">
			<input type="text" name="severwechat" placeholder="请输入微信图片地址" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['severwechat'])) ? $_options['severwechat'] : '') ?>">
		   </div>
		  </div>
		  <div class="eyui-inline">
		   <div class="eyui-input-inline">
			<button type="button" class="eyui-btn" id="wechatupload"><i class="eyui-icon">&#xe67c;</i>上传微信图片</button>
		   </div>
		  </div>
		 </div>
    </div>
  </div>

  <div class="eyui-colla-item">
    <h2 class="eyui-colla-title">客服电话</h2>
    <div class="eyui-colla-content eyui-show">
    	<div class="eyui-form-item">
			<label class="eyui-form-label">客服电话</label>
			<div class="eyui-input-inline">
			  <input type="text" name="severphone" placeholder="请输入客服电话" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['severphone'])) ? $_options['severphone'] : '') ?>">
			</div>
			<div class="eyui-form-mid eyui-word-aux">多个客服电话请用半角逗号","隔开</div>
		</div> 
    </div>
  </div>
  <div class="eyui-colla-item">
    <h2 class="eyui-colla-title">客服邮箱</h2>
    <div class="eyui-colla-content eyui-show">
    	<div class="eyui-form-item">
			<label class="eyui-form-label">客服邮箱</label>
			<div class="eyui-input-inline">
			  <input type="text" name="severemail" placeholder="请输入客服邮箱" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['severemail'])) ? $_options['severemail'] : '') ?>">
			</div>
			<div class="eyui-form-mid eyui-word-aux">多个客服邮箱请用半角逗号","隔开</div>
		</div> 
    </div>
  </div>
</div>


 <div class="eyui-form-item">
    <div class="eyui-input-block">
      <button class="eyui-btn" lay-submit lay-filter="formSever">立即提交</button>
      <button type="reset" class="eyui-btn eyui-btn-primary">重置</button>
    </div>
  </div>
</form>
<script>
eyui.use(['form','element','upload'], function(){
	var $ = eyui.$
  	,form = eyui.form
  	,upload = eyui.upload
  	,element = eyui.element;
  	
	upload.render({
		elem: '#wechatupload'
		,url: '<?php _e(admin_url('admin-ajax.php'))?>'
		,size: 1024
		,data: {
			action:'eys_imageupload'
			,formhash:'<?php _e(wp_create_nonce()); ?>'
		}
		,accept: 'images'
		,acceptMime: 'image/*'
		,done: function(d){
			if (d.code == '1'){
				$("input[name='severwechat']").val(d.msg);
//				service.setContent(rid, 'img['+d.data.url+']');
			}else{
				layer.msg(d.msg);
			}
		}
	});
  //监听提交
  form.on('submit(formSever)', function(data){
    var pdata = {};
    pdata.data = data.field;
    pdata.action = 'eys_sever';
    pdata.formhash = '<?php _e(wp_create_nonce()) ?>';
    $.ajax({url: '<?php _e(admin_url('admin-ajax.php'))?>',type:'post',data:pdata,dataType:'json',
    	success:function(d){
    		console.log('d=>',d);
    		if (d.code == 1){
				layer.msg('操作成功');
				form.val("formSever", d.data);
    		}
    	}
    });    
    return false;
  });
  $("#resetsocket").on("click", function(){
    var pdata = {};
    pdata.action = 'eys_upsocket';
    pdata.formhash = '<?php _e(wp_create_nonce()); ?>';
    layer.load(2);
    $.ajax({url: '<?php _e(admin_url('admin-ajax.php'))?>',type:'post',data:pdata,dataType:'json',
    	success:function(d){
    		if (d.code == 1){
    			$("input[name='socket']").val(d.msg);
				layer.msg('更新成功，请记得用提交表面来作保存。');
    		}else{
				layer.alert(d.msg);
    		}
    	},
    	complete:function(){
    		layer.closeAll('loading');
    	}
    });    
    return false;  	
  })
});
</script>
</body>
</html>