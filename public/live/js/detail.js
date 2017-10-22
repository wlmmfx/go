$(function(){
	jugeIsMember();
	$('.check_head_span').click(
		function(){
			var index=$(this).attr('name');
			$(this).parent().children().removeClass('active');
			$(this).addClass('active');
			$('.des_Box').children().removeClass('active');
			$('.des_Box').find('.part'+index).addClass('active');
		}
	);
	$('.course_head').click(
		function(){
			$(this).parent().find('.lessons').toggle();
			var className=$(this).find('.jianTou').attr('class');
			if(className=='iconfont icon-icon01 jianTou'){
				$(this).find('.jianTou').attr('class','iconfont icon-icon01-copy jianTou');
			}else{
				$(this).find('.jianTou').attr('class','iconfont icon-icon01 jianTou');
			}
		}
	);
	$('.free').click(
		function(){
			$(this).parent().children().removeClass('active_vip');
			$(this).parent().find('i').css('display','none');
			$(this).addClass('active');
			$(this).find('i').css('display','block');
			$('.pay').css('display','none');
			$('.freePay').css('display','block');
			$('#join_vip').css('display','none');
			$('#join_free').css('display','block');
			$('#payNum').html('0.00');
			$('#vipLastPtn').css('display','none');
			$('#freeLastPtn').css('display','block');
		}
	);
	$('.free').hover(
		function(){
			$(this).find('.beizhu').css('display','block');
			$(this).find('.xiaosanjiao').css('display','block');
		},
		function(){
			$(this).find('.beizhu').css('display','none');
			$(this).find('.xiaosanjiao').css('display','none');
		}
	);
	$('.vip').click(
		function(){
			$(this).parent().children().removeClass('active');
			$(this).parent().find('i').css('display','none');
			$(this).addClass('active_vip');
			$(this).find('i').css('display','block');
			$('.pay').css('display','block');
			$('.freePay').css('display','none');
			$('#join_vip').css('display','block');
			$('#join_free').css('display','none');
			$('#payNum').html($('#priceBox').html());
			$('#vipLastPtn').css('display','block');
			$('#freeLastPtn').css('display','none');
		}
	);
	$('.vip').hover(
		function(){
			$(this).find('.beizhu').css('display','block');
			$(this).find('.xiaosanjiao').css('display','block');
		},
		function(){
			$(this).find('.beizhu').css('display','none');
			$(this).find('.xiaosanjiao').css('display','none');
		}
	);
	var price=parseFloat($('#priceBox').html());
	if(price==0){
		$('.vip').css('display','none');
	}
})
function addSubscribe(){
	var trueName=$('#subscribeWarning_truename').val();
	var mobile=$('#subscribeWarning_mobile').val();
	if(trueName==''){
		$('#subscribeWarning').html('请填写真实姓名');
	}else if(mobile==''){
		$('#subscribeWarning').html('请填写手机号');
	}else{
		var pay=parseFloat($('payNum').html());
		var data=$('#subscribeForm').serialize();
		$('#addSubscribe_btn').css('background-color','#ddd');
		$('#addSubscribe_btn').attr('disabled','disabled');
		$.ajax({
			url:'http://www.itgege.com/course/buy/modify_user_info_mingshi',
			type:'post',
			data:data,
			success:function(res){
				var windowBand_title=$('#windowBand_title').html();
				$('#subscribeWarning').html('您已成功预约《'+windowBand_title+'》课程！');
				$('#subscribeWarning').css('color','#82D843');
				$('#addSubscribe_btn').css('display','none');
				window.location.reload();
			},
			error: function (e){
				var warning=e.responseJSON.error.message;
				$('#subscribeWarning').html(warning);
			}
		})
	}
}
function buyVip(){
	var trueName=$('#subscribeWarning_truename').val();
	var mobile=$('#subscribeWarning_mobile').val();
	if(trueName==''){
		$('#subscribeWarning').html('请填写真实姓名');
	}else if(mobile==''){
		$('#subscribeWarning').html('请填写手机号');
	}else{
		$('#subscribeForm').submit();
	}
}
function jugeIsMember(){
	var preview=getPar('previewAs');
	//先判断预览方式
	if(preview=='guest'){
	}else{
		//再判断是否是成员
		var userId=$('#userIdPage').val();
		var members=$('.memberIdThis');
		for(var i=0;i<members.length;i++){
			if($(members[i]).html()==userId){
				var id=$('#courseIdPage').val();
				window.location.href="http://www.itgege.com/course_member/"+id;
			}
		}
	}
}
//获取url中的参数
function getPar(par){
	//获取当前URL
	var local_url = document.location.href;
	//获取要取得的get参数位置
	var get = local_url.indexOf(par + "=");
	if (get == -1) {
		return false;
	}
	//截取字符串
	var get_par = local_url.slice(par.length + get + 1);
	//判断截取后的字符串是否还有其他get参数
	var nextPar = get_par.indexOf("&");
	if (nextPar != -1) {
		get_par = get_par.slice(0, nextPar);
	}
	return get_par;
}