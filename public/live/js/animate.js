/**
 * Created by Thinkpad on 2017/5/2.
 */
$(function(){
  /*分享*/
  /*$('.share_wx').on('click',function(){
    $('.share_content,.mask-layer').show();
  })
  $('.share_content span').on('click',function(){
    $('.share_content,.mask-layer').hide();
  })*/
  /*分享end*/
  if(localStorage.getItem('code_wei')=='hide'){
    $('.wei_kf').hide();
  }else{
    $('.wei_kf').show();
  }
    $('.wei_close').click(function(){
      $('.wei_kf').hide();
      localStorage.setItem('code_wei','hide')
    })
    $('.float_window .span').mouseover(function(){
       $('.wei_kf').show();
        localStorage.clear()
    })
    if(localStorage.getItem('kf_wei')=='hidden'){
    $('.wei_kf1').hide();
  }else{
    $('.wei_kf1').show();
  }
    $('.wei_kf1 .wei_close').click(function(){
      $('.wei_kf1').hide();
      localStorage.setItem('kf_wei','hidden')
    })
    $('.float_window .span').mouseover(function(){
       $('.wei_kf1').show();
       localStorage.clear()
    })
    /*嘉宾介绍内容显示*/
    $('.part1 a').each(function(index){
       var $techer_right=$('.part1 a').eq(index).find('.teacher');
       var $icon=$techer_right.find('i');
        $icon.attr('rol',index);
    });
 /*   $('.part1').each(function(index){
        var $techer_right=$('.part1 ').eq(index).find('.teacher');
        var $icon=$techer_right.find('i');
        $icon.attr('rol',index);
    });*/
    $('.teacher i') .click(function(e){
        var target =$(e.target);
        var rol=  Number(target.attr('rol'));
        var $t_right=$('.part1 a').eq(rol).find('.teacher');
    /*    var $t_right1=$('.part1 ').eq(rol).find('.teacher');*/
        var $content=$t_right.find('.content');
        var $content1=$t_right.find('.teacher_head_right');
    /*    var $content2=$t_right1.find('.teacher_head_right');*/
        var $icon=$t_right.find('i');
       /* var $icon1=$t_right1.find('i');*/
        if($icon.hasClass('up')/* || $icon1.hasClass('up')*/){
            $icon.removeClass('up');
          /*  $icon1.removeClass('up');*/
            $content.css({height:'42px'});
            $content1.find('.teacher_right_text').css({height:'48px'});
     /*       $content2.find('.content').css({height:'42px'});*/
            $(this).html('&#xe6a6;');
            return false;
        }else {
            $icon.addClass('up');
        /*    $icon1.addClass('up');*/
            $content.css({height:'auto'});
            $content1.find('.teacher_right_text').css({height:'auto'});
       /*     $content2.find('.content').css({height:'auto'});*/
            $(this).html('&#xe6a5;');
            return false;
        }
    });
  /*客服二维码*/
	$('.join_button1').mouseenter(function(e){
        var $top= $('.join_button1').offset().top+46;
        var $left=$('.join_button1').offset().left;
        $('.join_kf').css({top:$top,left:$left,display:'block'})
    });
       $('.join_kf').mouseenter(function(e){
            $('.join_kf').css({display:'block'})
        });
    $('.join_button1,.join_kf').mouseleave(function(){
        $('.join_kf').css({display:'none'})
    });

/*	   if($('.title').height()>28){
        $('.right_contain_free,.time').css({'margin-top':'10px'});
        $('#join_free').css({'margin-top':'53px'});
        $('.freePay_pay').css({'margin-top':'35px'});
        $('.freePay_pay div').css({'font-size':'29px'});
        $('.span_box,.bottom').css({'margin-top':'15px'});

    }else {

        $('.right_contain_free,.time').css({'margin-top':'10px'});
        $('#join_free').css({'margin-top':'55px'});
        $('.freePay_pay').css({'margin-top':'22px'});
        $('.freePay_pay div').css({'font-size':'29px'});
        $('.span_box,.bottom').css({'margin-top':'20px'});
    }*/
    /*收藏*/
	$('.box_favorite').click(function(){
		var useid = $(this).attr('useid');
		if(useid<0){
			alert('您还未登录！');
			return false;
		}
		var courseid=$(this).attr('courseid');
		$.get(_SYS_CONFIG.mainDomain+'/dakashuo/favorite/'+courseid,function(data){

			if(data.success==true && data.favstatus=='fav' ){
				$('.box_favorite').text('已收藏');
				$('.box_favorite').css({background:"url('/assets/dakashuo/member/img/fav_on.png') no-repeat"})
			}else if(data.success==true && data.favstatus=='unfav'){
				$('.box_favorite').text('收藏');
				$('.box_favorite').css({background:"url('/assets/dakashuo/member/img/fav_unon.png') no-repeat"})
			}else if(data.success==false){
				alert(data.msg);
			}
		}).error(function(msg){
			 alert('请求出错！');
		})
	})
	
	$('.m_favorite').click(function(){
		var useid = $(this).attr('useid');
		if(useid<0){
			alert('您还未登录！');
			return false;
		}
		var meetingid=$(this).attr('meetingid');
		$.get(_SYS_CONFIG.mainDomain+'/dakashuo/mfavorite/'+meetingid,function(data){

			if(data.success==true && data.favstatus=='fav' ){
				$('.m_favorite').text('已收藏');
				$('.m_favorite').css({background:"url('/assets/dakashuo/member/img/fav_on.png') no-repeat"})
			}else if(data.success==true && data.favstatus=='unfav'){
				$('.m_favorite').text('收藏');
				$('.m_favorite').css({background:"url('/assets/dakashuo/member/img/fav_unon.png') no-repeat"})
			}else if(data.success==false){
				alert(data.msg);
			}
		}).error(function(msg){
			 alert('请求出错！');
		})
	})
	
	/********关注机构***********/
	$('.btn_follow').click(function(){
		var useid = $(this).attr('useid');
		if(useid<0){
			alert('您还未登录！');
			return false;
		}
		var orgid = $(this).attr('orgid');
		$.get(_SYS_CONFIG.mainDomain+'/dakashuo/follow/'+orgid,function(data){

			if(data.success==true && data.followstatus=='follow' ){
				$('.btn_follow').text('已收藏');
				$('.btn_follow').css({background:"url('/assets/dakashuo/member/img/fav_on.png') no-repeat"})
			}else if(data.success==true && data.favstatus=='unfollow'){
				$('.btn_follow').text('收藏');
				$('.btn_follow').css({background:"url('/assets/dakashuo/member/img/fav_unon.png') no-repeat"})
			}else if(data.success==false){
				alert(data.msg);
			}
		}).error(function(msg){
			 alert('请求出错！');
		})
	})
	/********关注机构***********/
   /* $('.right').click(function(){
       /!* $('.picList li:first').animate({marginLeft:'-227px'},300,function(){
            $('.picList li:first').append('.picList ').css({marginLeft:'10px'})
        })*!/
        $(".picList li:first").animate({
            marginLeft:"-219px"
        },300,function(){
            var temp=$(this).clone();
            $(this).remove();
            temp.css({marginLeft:"10px"});
            $(".picList").append(temp);
            $('.pic_a').mouseenter(function(){
                $(this).children('.intro').addClass('active')
            })
            $('.pic_a').mouseleave(function(){
                $(this).children('.intro').removeClass('active')
            })
        });
    })
    $('.left').click(function(){
       /!* $('.picList li:last').prepend('.picList ').css({marginLeft:'-243px'});
        $('.picList li:first').animate({marginLeft:'0'},300)*!/
        var temp=$(".picList li:last").clone();
        $(".picList li:last").remove();
        temp.css({marginLeft:"-219px"});
        $(".picList").prepend(temp);
        $(".picList li:first").animate({
            marginLeft:"10px"
        },200,function(){
            $('.pic_a').mouseenter(function(){
                $(this).children('.intro').addClass('active')
            })
            $('.pic_a').mouseleave(function(){
                $(this).children('.intro').removeClass('active')
            })
        })

    })*/
    $('.pic_a').mouseenter(function(){
        $(this).children('.intro').addClass('active')
    })
    $('.pic_a').mouseleave(function(){
        $(this).children('.intro').removeClass('active')
    })

    if( document.body.clientWidth>1920){
        $('.container_home').css({width:'1283px'})
        $('.img_center').css({width:'184px'})
    }
/*适配嘉宾介绍*/
if(document.body.clientWidth<=1280 && document.body.clientWidth>1024){
$('.img_center').css({width:''})
}

if(document.body.clientWidth<=1024){
$('.img_center').css({width:'176px'})
$('.home_or_tj_list a').eq(5).children('.li').css({'margin-right':'12px','margin-left':'13px'});
$('.home_or_tj_list a').eq(11).children('.li').css({'margin-right':'12px','margin-left':'13px'});
$('.home_or_tj_list a').eq(17).children('.li').css({'margin-right':'12px','margin-left':'13px'})
}

    });

/*直播二维码*/
function qcode(src) {
	/*var src = "http://www.itdks.com" + src;*/
    /*$('.video_qcode').qrcode({width: 150, height: 150, text: src});*/
    $('.video_qcode').append('<img src="'+ src +'">')
    var imgh = $('.video_code_img').innerHeight();
    var imgw = $('.video_code_img').innerWidth();
    $('.video_code').mouseenter(function () {
      if(!src){
        return false;
      }
      $('.video_code_img').show();
        var $top = $('.video_code').offset().top;
        var $left = $('.video_code').offset().left;
        $('.video_code_img').css({top: $top - imgh, left: $left - imgw, display: 'block'})
    })
    $('.video_code').mouseleave(function () {
        $('.video_code_img').css({display: 'none'})
    })
}
function qcode1(src) {
  var src = "http://www.itdks.com" + src;
    $('.video_qcode').qrcode({width: 150, height: 150, text: src});
    var imgh = $('.video_code_img').innerHeight();
    var imgw = $('.video_code_img').innerWidth();
    $('.video_code').mouseenter(function () {
      if(!src){
        return false;
      }
      $('.video_code_img').show();
        var $top = $('.video_code').offset().top;
        var $left = $('.video_code').offset().left;
        $('.video_code_img').css({top: $top - imgh, left: $left - imgw, display: 'block'})
    })
    $('.video_code').mouseleave(function () {
        $('.video_code_img').css({display: 'none'})
    })
}
/*直播二维码结束*/
/*liveevent_detail&嘉宾介绍*/
function changetitle() {
    if($('.right_contain .title').height()==56){
        $('.right_contain').height(155)
    }
    if($('.right_contain .title').height()==84 && $('.owner').length==1){
        $('.right_contain').height(204)
        $('#join_free').css({'margin-top':'20px'})
    }
    if($('.right_contain .title').height()==48 && $('.owner').length==1){
        $('.right_contain').height(174)
        $('#join_free').css({'margin-top':'20px'})
    }
  
    if ($('.time div').length == 3) {
        $('.time div').eq(1).css({'margin-top': '12px'})
    } else if ($('.time div').length == 4) {
        $('.time div').eq(1).css({'margin-top': '0'});
        $('.time div').eq(2).css({'margin-top': '0'});
    }
    $('.guest_title').each(function(index){
        var $span= $(this).find('span');
        var $first=$span.eq(0).width();
        $span.eq(1).width(240-$first-14);
    })
}
var slide=function(){
    /*首页轮播*/
    $('.one-time').slick({
        dots: false,
        slidesToShow: 6,
        slidesToScroll:6,
        touchMove: false
 /*       autoplay: true,
        autoplaySpeed: 5000*/
    });
}
$(window).scroll(function(){
    if(  $(window).scrollTop()>0){
        $('.scrolltop').show();
        $('.scrolltop').click(function(){
          /*  $('body').animate({"scrollTop":0},800);*/
            $('body,html').animate({"scrollTop":0},800,function(){
                $('body,html').stop(true);
            });
            return false;
        })
    }else {
        $('.scrolltop').hide()
    }
})
/*倒计时*/
 var  settime
var counttimr=function(t){
    
    var  $starttime=t;
    var $changtime=$starttime.trim().replace(/-/g,'/');
    var now=Number(new Date($changtime).getTime().toString());
    var newtime=Number(new Date().getTime().toString());
    var $lasttime=(now-newtime)/1000;
   
    if($lasttime<=0){
        clearInterval(settime)
         $('#counttime').html('')
         $('.big-play-button').hide()
        return false;
    }else{
        clearInterval(settime)
         $('#counttime').html('')
         settime=setInterval(function(){      
                var shijian = getTimeText($lasttime);
                $lasttime--;
                if( $lasttime<=0){
                    location.reload();
                }else{
                    $("#counttime").html(shijian);
                }
                
            
        }, 1000);  
        } 
    
}
function getTimeText(v) {

        var day=Math.floor(v/60/60/24);
        var h=Math.floor(v/60/60%24);
        var m=Math.floor(v/60%60);
        var s=Math.floor(v%60);
        if (day < 10 && day >= 0) { day = '0' + day; }
        if (h < 10 && h >= 0) { h = '0' + h; }
        if (s < 10 && s >=0 ) { s = '0' + s; }
        if (m < 10 && m >= 0) { m = '0' + m; }
        var shijian = '<div style="font-size: 18px;text-align: center;color: #fff;  margin-bottom: 20px;margin-left: -15px;"><span >距离直播开始还有</span></div><ul><li><span id="t_d">'+ day+'</span><p>天</p></li>'+
            '<li> <span id="t_h">'+ h+'</span><p>小时</p></li>' +
            '<li><span id="t_m">'+ m+'</span><p>分钟 </p></li>' +
            '<li><span id="t_s">'+ s+'</span><p>秒</p></li></ul>';
        return shijian;
    }
/*兴趣课程*/

$(function(){
  $('.hobbit,.market').show();
    $('.classlist li').each(function(index){
         $('.classlist li').eq(index).attr('rol',index);
         $('.classlist li .classlist_bg').eq(index).attr('pol',index);
    })
    var arr=[];
    var obj={};
     $('.classlist li').click(function(e){
        var target=$(e.target);
        if(target.attr('class')=='classlist_bg' || target.attr('class')=='classlist_bg_img'){
            if(target.attr('class')=='classlist_bg_img'){
                var index=target.parent().attr('pol');
            }else{
               var index=target.attr('pol');
            }
           var $bg=$('.classlist li').eq(index).find('.classlist_bg');    
           var cateId=$('.classlist li').eq(index).attr('cateId');
              var tax=arr.indexOf(cateId);
             arr.splice(tax,1)
           $('.classlist li').eq(index).find('div').hide();          
        }else{
           var index=target.attr('rol');
           var cateId=$('.classlist li').eq(index).attr('cateId');
           var $bg=$('.classlist li').eq(index).find('.classlist_bg');      
           $('.classlist li').eq(index).find('div').show();
           arr.push(cateId)
        }
         if(arr){
         obj=arr.map(function(key){
        return {
              index:key 
        }
     })
    }

    console.log(arr)
   })
    $('.hobbit_submit span').eq(0).click(function () {
        if(arr.length==0){
            $('body').append('<div class="layerout" style="background:rgba(0,0,0,0.7);color:#fff;">请选择您感兴趣的课程！</div>');
            setTimeout(function (){ 
                  $('.layerout').remove();
              },2000)
            return;
        }
        $.post("/dakashuo/interest/save",{cateids:arr},function (data) {
          if(data.success==true){
              $('.hobbit').hide();
              $('body').append('<div class="layerout">'+ data.message+'</div>');
              setTimeout(function () {
                  $('.market').hide();
                  $('.layerout').remove();
              },3000)
           }
        })

    })
     $('.hobbit_submit span').eq(1).click(function(){
        $('.hobbit,.market').hide();
    var newtime=Number(new Date().getTime().toString());
        localStorage.setItem('data',newtime)
     })
     
})

$('.layer_tip_head span').eq(1).click(function(){
    $('.layer_tip').hide();
})
$('.cancel').click(function(){
    $('.layer_tip').hide();
})
$('.contain_content a').each(function(index,value){
    $(this).click(function(){
        alert(index)
    })
})
$(function(){
  var $nowtime=Number(new Date().getTime().toString());

    if($nowtime-localStorage.getItem('data')<24*60*1000){
        $('.hobbit,.market').hide();
    }else{
         $('.hobbit,.market').show();
    }
    setTimeout(function(){
  $('.jiathis_style_24x24').find('.jtico_weixin').css({'background-image':'url(/assets/dakashuo/member/img/share_weixin.png) ','background-position':'0','background-size':'20px','line-height':'27px !important','height':'27px !important','margin-right':'-7px'});
  $('.jiathis_style_24x24').find('.jtico_tsina').css({'background-image':'url(/assets/dakashuo/member/img/share_weibo.png) ','background-position':'0','background-size':'20px','line-height':'27px !important','height':'27px !important','margin-right':'-7px'});
  $('.jiathis_style_24x24').find('.jtico_qzone').css({'background-image':'url(/assets/dakashuo/member/img/share_qzone.png) ','background-position':'0','background-size':'20px','line-height':'27px !important','height':'27px !important','margin-right':'-7px'});
  $('.jiathis_style_24x24').find('.jtico_cqq').css({'background-image':'url(/assets/dakashuo/member/img/share_qq.png) ','background-position':'0','background-size':'20px','line-height':'27px !important','height':'27px !important','margin-right':'-7px'});
  },500)
})
var obj={
  counttimr:function(t){
    var  $starttime=t;
    var $changtime=$starttime.trim().replace(/-/g,'/');
    var now=Number(new Date($changtime).getTime().toString());
    var newtime=Number(new Date().getTime().toString());
    var $lasttime=(now-newtime)/1000;
    var that=this
         settime=setInterval(function(){      
                var text = that.getTime($lasttime);
                $lasttime--;
                if( $lasttime<=0){
                   clearInterval(settime)
                   return false
                }else{
                    $(".videocount").empty().html(text);
                }
               
            
        }, 1000);  

    },
    getTime:function(v) {
        var day=Math.floor(v/60/60/24);
        var h=Math.floor(v/60/60%24);
        var m=Math.floor(v/60%60);
        var s=Math.floor(v%60);
        if (day < 10 && day >= 0) { day = '0' + day; }
        if (h < 10 && h >= 0) { h = '0' + h; }
        if (s < 10 && s >=0 ) { s = '0' + s; }
        if (m < 10 && m >= 0) { m = '0' + m; }
        var text = '<li>'+day+'<span>天</span></li><li>'+ h+'<span>小时</span></li><li>'+ m+'<span>分钟</span></li><li>'+ s+'<span>秒</span></li>';
        return text;
    }
}