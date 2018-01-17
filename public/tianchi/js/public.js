// JavaScript Document
$(document).ready(function(e) {
	//首页轮播调用
    var mySwiper = new Swiper ('.swiper-container', {
		loop: true,
		pagination: '.swiper-pagination',
		autoplay: 2500,
		paginationClickable :true,
		observer:true,//修改swiper自己或子元素时，自动初始化swiper
    	observeParents:true,//修改swiper的父元素时，自动初始化swiper
		autoplayDisableOnInteraction: false
	});
	
	
});