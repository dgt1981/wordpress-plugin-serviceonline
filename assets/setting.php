<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title></title>
  <style>.eyui-form-label{width:120px;}</style>
</head>
<body>
<fieldset class="eyui-elem-field eyui-field-title" style="margin-top: 20px;">
  <legend>参数设置</legend>
</fieldset>
<form class="eyui-form" action="" lay-filter="formSetting">
  <div class="eyui-form-item">
    <label class="eyui-form-label">客服标签定义</label>
    <div class="eyui-input-inline">
      <input type="text" name="title" placeholder="请输入客服标签" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['title'])) ? esc_html(wp_unslash($_options['title'])) : '') ?>">
    </div>
    <div class="eyui-form-mid eyui-word-aux"></div>
  </div> 
  <div class="eyui-form-item">
    <label class="eyui-form-label">启用WEB版</label>
    <div class="eyui-input-block">
      <input type="checkbox" name="isopen" lay-skin="switch" lay-text="ON|OFF" <?php if (!empty($_options['isopen'])) _e("checked") ?>>
    </div>
  </div> 
  <div class="eyui-form-item">
    <label class="eyui-form-label">启用手机版</label>
    <div class="eyui-input-block">
      <input type="checkbox" name="mobileopen" lay-skin="switch" lay-text="ON|OFF" <?php if (!empty($_options['mobileopen'])) _e("checked") ?>>
    </div>
  </div> 
	<div class="eyui-form-item">
    <label class="eyui-form-label">开启图片发送</label>
    <div class="eyui-input-block">
      <input type="checkbox" name="imageupload" lay-skin="switch" lay-text="ON|OFF" <?php if (!empty($_options['imageupload'])) _e("checked") ?>>
    </div>
  </div>
  <div class="eyui-form-item">
    <label class="eyui-form-label">图片容量限制</label>
    <div class="eyui-input-inline">
      <input type="text" name="imagelimit" placeholder="请输入图片发送最大容量值" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['imagelimit'])) ? $_options['imagelimit'] : '') ?>">
    </div>
    <div class="eyui-form-mid eyui-word-aux">单位：字节</div>
  </div>
	<div class="eyui-form-item">
    <label class="eyui-form-label">开启文件发送</label>
    <div class="eyui-input-block">
      <input type="checkbox" name="fileupload" lay-skin="switch" lay-text="ON|OFF" <?php if (!empty($_options['fileupload'])) _e("checked") ?>>
    </div>
  </div>    
  <div class="eyui-form-item">
    <label class="eyui-form-label">文件容量限制</label>
    <div class="eyui-input-inline">
      <input type="text" name="filelimit" placeholder="请输入文件发送最大容量值" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['filelimit'])) ? $_options['filelimit'] : '') ?>">
    </div>
    <div class="eyui-form-mid eyui-word-aux">单位：字节</div>
  </div>
  <div class="eyui-form-item">
    <label class="eyui-form-label">文件类型限制</label>
    <div class="eyui-input-inline">
      <input type="text" name="fileext" placeholder="请输入文件发送最大容量值" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['fileext'])) ? $_options['fileext'] : '') ?>">
    </div>
  </div>
  <div class="eyui-form-item">
    <div class="eyui-inline">
      <label class="eyui-form-label">客服标签位置</label>
      <div class="eyui-input-inline">
		  <select name="position" lay-verify="required">
			<option value="t" <?php _e(($_options['position']=='t') ? 'selected' : '') ?>>顶部中间</option>
			<option value="r" <?php _e(($_options['position']=='r') ? 'selected' : '') ?>>右边缘中间</option>
			<option value="b" <?php _e(($_options['position']=='b') ? 'selected' : '') ?>>底部中间</option>
			<option value="l" <?php _e(($_options['position']=='l') ? 'selected' : '') ?>>左边缘中间</option>
			<option value="lt" <?php _e(($_options['position']=='lt') ? 'selected' : '') ?>>左上角</option>
			<option value="lb" <?php _e(($_options['position']=='lb') ? 'selected' : '') ?>>左下角</option>
			<option value="rt" <?php _e(($_options['position']=='rt') ? 'selected' : '') ?>>右上角</option>
			<option value="rb" <?php _e(($_options['position']=='rb') ? 'selected' : '') ?>>右下角</option>
		  </select>
      </div>
    </div>
    </div>
    <div class="eyui-form-item">
    <div class="eyui-inline">
      <label class="eyui-form-label">客服标签大小</label>
      <div class="eyui-input-inline">
		  <select name="width" lay-verify="required">
			<option value="30" <?php _e(($_options['width']=='30') ? 'selected' : '') ?>>小图标显示</option>
			<option value="40" <?php _e(($_options['width']=='40') ? 'selected' : '') ?>>普通图标显示</option>
			<option value="50" <?php _e(($_options['width']=='50') ? 'selected' : '') ?>>大图标显示</option>
		  </select>
      </div>
    </div>
    </div>
	<div class="eyui-form-item">
		<label class="eyui-form-label">客服菜单颜色 </label>
		<div class="eyui-input-inline">
			<input type="text" value="<?php _e((!empty($_options['backcolor'])) ? $_options['backcolor'] : '') ?>" name="backcolor" placeholder="请选择颜色" class="eyui-input" id="backcolor">      
		</div>
		<div id="backcolor-select"></div>
	</div>  
	<div class="eyui-form-item">
		<label class="eyui-form-label">客服ICON颜色</label>
		<div class="eyui-input-inline">
			<input type="text" value="<?php _e((!empty($_options['iconcolor'])) ? $_options['iconcolor'] : '') ?>" name="iconcolor" placeholder="请选择颜色" class="eyui-input" id="iconcolor">      
		</div>
		<div id="iconcolor-select"></div>
	</div>    
  <div class="eyui-form-item">
    <label class="eyui-form-label">客服对话框宽度</label>
    <div class="eyui-input-inline">
      <input type="text" name="onlinewidth" placeholder="请输入客服对话框宽度" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['onlinewidth'])) ? $_options['onlinewidth'] : '') ?>">
    </div>
    <div class="eyui-form-mid eyui-word-aux">单位：像素</div>
  </div>
  <div class="eyui-form-item">
    <label class="eyui-form-label">客服对话框高度</label>
    <div class="eyui-input-inline">
      <input type="text" name="onlineheight" placeholder="请输入客服对话框高度" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['onlineheight'])) ? $_options['onlineheight'] : '') ?>">
    </div>
    <div class="eyui-form-mid eyui-word-aux">单位：像素</div>
  </div>
  <div class="eyui-form-item">
    <div class="eyui-inline">
      <label class="eyui-form-label">客服对话框位置</label>
      <div class="eyui-input-inline">
		  <select name="onlineoffset" lay-verify="required">
			<option value="auto" <?php _e(($_options['onlineoffset']=='auto') ? 'selected' : '') ?>>正中</option>
			<option value="t" <?php _e(($_options['onlineoffset']=='t') ? 'selected' : '') ?>>顶部中间</option>
			<option value="r" <?php _e(($_options['onlineoffset']=='r') ? 'selected' : '') ?>>右边缘中间</option>
			<option value="b" <?php _e(($_options['onlineoffset']=='b') ? 'selected' : '') ?>>底部中间</option>
			<option value="l" <?php _e(($_options['onlineoffset']=='l') ? 'selected' : '') ?>>左边缘中间</option>
			<option value="lt" <?php _e(($_options['onlineoffset']=='lt') ? 'selected' : '') ?>>左上角</option>
			<option value="lb" <?php _e(($_options['onlineoffset']=='lb') ? 'selected' : '') ?>>左下角</option>
			<option value="rt" <?php _e(($_options['onlineoffset']=='rt') ? 'selected' : '') ?>>右上角</option>
			<option value="rb" <?php _e(($_options['onlineoffset']=='rb') ? 'selected' : '') ?>>右下角</option>
		  </select>
      </div>
    </div>
    </div>
 <div class="eyui-form-item">
    <div class="eyui-inline">
      <label class="eyui-form-label">手机版标签位置</label>
      <div class="eyui-input-inline">
		  <select name="mobileposition" lay-verify="required">
			<option value="t" <?php _e(($_options['mobileposition']=='t') ? 'selected' : '') ?>>顶部中间</option>
			<option value="r" <?php _e(($_options['mobileposition']=='r') ? 'selected' : '') ?>>右边缘中间</option>
			<option value="b" <?php _e(($_options['mobileposition']=='b') ? 'selected' : '') ?>>底部中间</option>
			<option value="l" <?php _e(($_options['mobileposition']=='l') ? 'selected' : '') ?>>左边缘中间</option>
			<option value="lt" <?php _e(($_options['mobileposition']=='lt') ? 'selected' : '') ?>>左上角</option>
			<option value="lb" <?php _e(($_options['mobileposition']=='lb') ? 'selected' : '') ?>>左下角</option>
			<option value="rt" <?php _e(($_options['mobileposition']=='rt') ? 'selected' : '') ?>>右上角</option>
			<option value="rb" <?php _e(($_options['mobileposition']=='rb') ? 'selected' : '') ?>>右下角</option>
		  </select>
      </div>
    </div>
    </div>    
<div class="eyui-form-item">
    <div class="eyui-inline">
      <label class="eyui-form-label">手机版对话框位置</label>
      <div class="eyui-input-inline">
		  <select name="mobileoffset" lay-verify="required">
			<option value="full" <?php _e(($_options['mobileoffset']=='full') ? 'selected' : '') ?>>全屏显示</option>
			<option value="auto" <?php _e(($_options['mobileoffset']=='auto') ? 'selected' : '') ?>>中间显示</option>
			<option value="t" <?php _e(($_options['mobileoffset']=='t') ? 'selected' : '') ?>>顶部显示</option>
			<option value="b" <?php _e(($_options['mobileoffset']=='b') ? 'selected' : '') ?>>底部显示</option>
		  </select>
      </div>
    </div>
    </div>
 <div class="eyui-form-item">
    <label class="eyui-form-label">手机版对话框高度</label>
    <div class="eyui-input-inline">
      <input type="text" name="mobileheight" placeholder="请输入手机版对话框高度" autocomplete="off" class="eyui-input" value="<?php _e((!empty($_options['mobileheight'])) ? $_options['mobileheight'] : '60%') ?>">
    </div>
    <div class="eyui-form-mid eyui-word-aux">单位：百分比, 只有在非全屏模式下设置有效。</div>
  </div>            
  <div class="eyui-form-item" style="margin-top:20px;">
    <div class="eyui-input-block">
      <button class="eyui-btn" lay-submit lay-filter="submitSetting">立即提交</button>
      <button type="reset" class="eyui-btn eyui-btn-primary">重置</button>
    </div>
  </div>
</form>
<script>
eyui.use(['form','colorpicker'], function(){
	var $ = eyui.$
  	,form = eyui.form
	,colorpicker = eyui.colorpicker;
	colorpicker.render({
		elem: '#backcolor-select'
		,size: 'xs'
		,color: '<?php _e((!empty($_options['backcolor'])) ? $_options['backcolor'] : '') ?>'
		,done: function(color){
		  $('#backcolor').val(color);
		}
	});	
	colorpicker.render({
		elem: '#iconcolor-select'
		,size: 'xs'
		,color: '<?php _e((!empty($_options['iconcolor'])) ? $_options['iconcolor'] : '') ?>'
		,done: function(color){
		  $('#iconcolor').val(color);
		}
	});		
	colorpicker.render({
		elem: '#tipscolor-select'
		,size: 'xs'
		,color: '<?php _e((!empty($_options['tipscolor'])) ? $_options['tipscolor'] : '') ?>'
		,done: function(color){
		  $('#tipscolor').val(color);
		}
	});			
  //监听提交
  form.on('submit(submitSetting)', function(data){
    var pdata = {};
    pdata.data = data.field;
    pdata.formhash = "<?php _e(wp_create_nonce()); ?>";
    pdata.action = 'eys_setting';
    $.ajax({url: '<?php _e(admin_url('admin-ajax.php'))?>',type:'post',data:pdata,dataType:'json',
    	success:function(d){
    		if (d.code == 1){
				layer.msg('操作成功');
				form.val("formSetting", d.opt);
    		}
    	}
    });    
    return false;
  });
});
</script>
</body>
</html>